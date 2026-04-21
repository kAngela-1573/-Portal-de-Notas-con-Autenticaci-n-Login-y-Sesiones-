<?php
// Iniciar sesión para verificar autenticación
session_start();

// 🔒 PROTECCIÓN: Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$promedio = null;
$notas = ['', '', '', ''];
$error = '';

// Procesar el formulario de notas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calcular'])) {
    $notas[0] = floatval($_POST['nota1'] ?? 0);
    $notas[1] = floatval($_POST['nota2'] ?? 0);
    $notas[2] = floatval($_POST['nota3'] ?? 0);
    $notas[3] = floatval($_POST['nota4'] ?? 0);
    
    // Validar que estén en rango 0-20 (sistema educativo peruano)
    $valida = true;
    foreach ($notas as $n) {
        if ($n < 0 || $n > 20) {
            $error = '⚠️ Las notas deben estar entre 0 y 20.';
            $valida = false;
            break;
        }
    }
    
    if ($valida) {
        $promedio = array_sum($notas) / 4;
        
        // Determinar estado académico
        if ($promedio >= 13) {
            $estado = "✅ Aprobado";
        } elseif ($promedio >= 10) {
            $estado = "⚠️ En recuperación";
        } else {
            $estado = "❌ Desaprobado";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal de Notas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f0f2f5; }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .bienvenida { font-size: 1.2em; color: #4a965c; }
        .logout-btn {
            background-color: #dc3545;
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-size: 14px;
        }
        .logout-btn:hover { background-color: #c82333; }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4a965c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        button:hover { background-color: #3d7e4a; }
        .resultado {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 8px;
            text-align: center;
            font-size: 1.2em;
        }
        .error { color: red; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <span class="bienvenida">👋 Bienvenido, <?= htmlspecialchars($usuario) ?></span>
        <a href="logout.php" class="logout-btn">🚪 Cerrar sesión</a>
    </div>
    
    <h2>📊 Registro de Notas</h2>
    <p>Ingresa tus 4 notas (escala 0-20):</p>
    
    <form method="post">
        <input type="number" step="any" name="nota1" placeholder="Nota 1" value="<?= $notas[0] ?>" required>
        <input type="number" step="any" name="nota2" placeholder="Nota 2" value="<?= $notas[1] ?>" required>
        <input type="number" step="any" name="nota3" placeholder="Nota 3" value="<?= $notas[2] ?>" required>
        <input type="number" step="any" name="nota4" placeholder="Nota 4" value="<?= $notas[3] ?>" required>
        <button type="submit" name="calcular">📈 Calcular Promedio</button>
    </form>
    
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($promedio !== null && empty($error)): ?>
        <div class="resultado">
            <strong>📌 Promedio final:</strong> <?= number_format($promedio, 2) ?><br>
            <strong>🎓 Estado:</strong> <?= $estado ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
