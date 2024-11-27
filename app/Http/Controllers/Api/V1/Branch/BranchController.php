<?php

namespace App\Http\Controllers\Api\V1\Branch;

use App\Features\Ftp\Domain\Repositories\FtpRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Bakery;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BranchController extends Controller
{
    private $ftpInterface;

    public function __construct(FtpRepositoryInterface $ftpInterface)
    {
        $this->ftpInterface = $ftpInterface;
    }
    public function addBranchByBakeryId(Request $request, $bakery_id)
    {
        DB::beginTransaction();

        try {
            Log::info('addBranchByBakeryId');
            Log::debug($request->all());

            // Verificar errores de subida de archivos
            if ($request->hasFile('profilePicture')) {
                $file = $request->file('profilePicture');
                if ($file->getError() !== UPLOAD_ERR_OK) {
                    Log::error('File upload error: ' . $file->getError());
                    return response()->json(['error' => 'Error uploading the file. Please check the file size and try again.'], 400);
                }
            }

            // Manipular datos antes de la validación
            $request->merge([
                'active' => filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);

            // Validar datos
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'openingHours' => 'required|string|max:255',
                'profilePicture' => 'nullable|file|mimes:jpg,jpeg,png|max:16384',
                'active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validatedData = $validator->validated();

            // Almacenamiento de datos en variables individuales
            $name = $validatedData['name'];
            $address = $validatedData['address'];
            $openingHours = $validatedData['openingHours'];
            $profilePicture = $request->hasFile('profilePicture') ? $request->file('profilePicture')->getClientOriginalName() : null;
            $active = $validatedData['active'];

            // Obtener la panadería
            $bakery = Bakery::findOrFail($bakery_id);

            if (!$bakery->active) {
                return response()->json(['error' => 'The bakery is not active.'], 400);
            }

            // Verificar si el usuario ya tiene una panadería
            $user = $bakery->owner;

            Log::debug($user);

            if (!$user || !$user->active) {
                return response()->json(['error' => 'The user is not active.'], 400);
            }

            // Crear una nueva sucursal
            $branch = $bakery->branches()->create([
                'name' => $name,
                'address' => $address,
                'opening_hours' => $openingHours,
                'active' => $active,
            ]);

            if ($request->hasFile('profilePicture')) {
                $profilePicturePath = $this->ftpInterface->saveBranchProfileFile($bakery->id, $branch->id, $request->file('profilePicture'));
                $branch->update(['profile_picture' => $profilePicturePath]);
            }

            DB::commit();
            Log::debug('success');
            return response()->json('success', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the bakery.'], 500);
        }
    }

    public function getAllBranchesByBakeryId($bakery_id): JsonResponse
    {
        Log::info('getAllBranchesByBakeryId');
        Log::debug($bakery_id);

        // Encontrar la panadería por ID
        $bakery = Bakery::find($bakery_id);

        if (!$bakery) {
            return response()->json([
                'success' => false,
                'message' => 'Bakery not found',
            ], 404);
        }

        // Obtener todas las ramas asociadas con la panadería
        $branches = $bakery->branches;

        // Transformar las ramas usando BranchResource
        $branchesResource = BranchResource::collection($branches);

        // Devolver las ramas como respuesta JSON
        return response()->json([
            'success' => true,
            'data' => $branchesResource,
        ], 200);
    }
}
