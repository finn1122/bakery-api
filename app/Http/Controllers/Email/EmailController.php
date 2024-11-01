<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Services\SendinblueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class EmailController extends Controller
{
    protected $sendinblueService;

    public function __construct(SendinblueService $sendinblueService)
    {
        $this->sendinblueService = $sendinblueService;
    }
    /**
     * Enviar correo de bienvenida.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendWelcomeEmail(User $user): array
    {
        try {
            $htmlContent = view('emails.welcome', [
                'userName' => $user->name
            ])->render();

            $result = $this->sendinblueService->sendEmail(
                [['email' => $user->email, 'name' => $user->name]],
                'Bienvenido a ' . config('app.name'),
                $htmlContent
            );

            return [
                'success' => true,
                'message' => 'Correo de bienvenida enviado exitosamente',
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al enviar el correo de bienvenida: ' . $e->getMessage()
            ];
        }
    }
    public function resendWelcomeEmail(User $user): JsonResponse
    {
        $result = $this->sendWelcomeEmail($user);

        if ($result['success']) {
            return response()->json([
                'message' => 'Correo de bienvenida reenviado exitosamente'
            ], 200);
        }

        return response()->json([
            'message' => 'Error al reenviar el correo de bienvenida',
            'error' => $result['message']
        ], 500);
    }
}
