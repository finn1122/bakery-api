<?php

namespace App\Http\Controllers\Api\V1\Bakery;

use App\Features\Ftp\Domain\Repositories\FtpRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\BakeryResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BakeryController extends Controller
{
    private $ftpInterface;

    public function __construct(FtpRepositoryInterface $ftpInterface)
    {
        $this->ftpInterface = $ftpInterface;
    }


    public function getBakery(): JsonResponse
    {
        Log::info('getBakery');
        $user = Auth::user();

        if ($user->bakery()->exists()) {
            // Devolver el recurso BakeryResource en lugar de una respuesta JSON básica
            return response()->json(new BakeryResource($user->bakery), 200);
        }

        return response()->json([], 200);
    }

    public function createBakeryByUserId(Request $request, $userId)
    {
        try {
            Log::info('createBakeryByUserId');
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

            // Obtener el usuario
            $user = User::findOrFail($userId);

            // Verificar si el usuario ya tiene una panadería
            $bakery = $user->bakery;

            // Manejar el archivo de imagen si se proporciona
            $profilePicturePath = $bakery ? $bakery->profile_picture : null;
            if ($request->hasFile('profilePicture')) {
                $profilePicturePath = $this->ftpInterface->saveBakeryProfileFile($userId, $request->file('profilePicture'));
            }

            if ($bakery) {
                // Actualizar la panadería existente
                $bakery->name = $name;
                $bakery->address = $address;
                $bakery->opening_hours = $openingHours; // Ajustar al nombre de la columna en la base de datos
                if ($profilePicture !== null) {
                    $bakery->profile_picture = $profilePicturePath; // Ajustar al nombre de la columna en la base de datos
                }
                $bakery->active = $active;
                $bakery->save();
            } else {
                // Crear una nueva panadería
                $user->bakery()->create([
                    'name' => $name,
                    'address' => $address,
                    'opening_hours' => $openingHours, // Ajustar al nombre de la columna en la base de datos
                    'profile_picture' => $profilePicturePath, // Ajustar al nombre de la columna en la base de datos
                    'active' => $active,
                ]);
            }
            Log::debug('success');

            return response()->json('success', 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the bakery.'], 500);
        }
    }


}
