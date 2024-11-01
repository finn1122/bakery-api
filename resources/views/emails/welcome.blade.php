<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenido a {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>¡Bienvenido {{ $userName }}!</h1>
</div>
<div class="content">
    <p>¡Gracias por unirte a {{ config('app.name') }}! Estamos emocionados de tenerte con nosotros.</p>
    <p>Tu cuenta ha sido creada exitosamente. Para comenzar a usar nuestros servicios, por favor verifica tu correo electrónico.</p>
    <p>Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.</p>
</div>
</body>
</html>
