<?php
// Iniciar sesión para poder guardar datos del usuario
session_start();

// Si ya está logueado, redirigir directamente a notas.php
if (isset($_SESSION['usuario'])) {
    header('Location: notas.php');
    exit;
}

$error = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validación simple (en un sistema real se consultaría una base de datos)
    // Usuarios válidos para este ejemplo:
    $usuarios_validos = [
        'admin' => '1234',
        'alumno' => 'notas',
        'juan' => 'juan2024'
    ];
    
    if (isset($usuarios_validos[$usuario]) && $usuarios_validos[$usuario] === $password) {
        // Credenciales correctas: guardar en sesión
        $_SESSION['usuario'] = $usuario;
        header('Location: notas.php');
        exit;
    } else {
        $error = '❌ Usuario o contraseña incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Portal de Notas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f0f2f5; }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; color: #333; }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4a965c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background-color: #3d7e4a; }
        .error { color: red; text-align: center; margin-top: 10px; }
        .info { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
<div class="login-container">
    <h2>🔐 Iniciar Sesión</h2>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <div class="info">
        🔑 Usuarios de prueba: admin/1234 | alumno/notas | juan/juan2024
    </div>
</div>
</body>
</html>
