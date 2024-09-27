<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Definir los privilegios según el rol
$privilegios = "";

switch ($role) {
    case 'admin_db':
        $privilegios = "
            <ul>
                <li>Gestión de cuentas de usuarios (creación, modificación, eliminación).</li>
                <li>Configuración de cursos y programas académicos.</li>
                <li>Generación de informes de rendimiento académico.</li>
            </ul>";
        break;
    case 'profesor':
        $privilegios = "
            <ul>
                <li>Creación y gestión de cursos y materiales educativos.</li>
                <li>Evaluación de tareas y exámenes.</li>
                <li>Comunicación con estudiantes a través de la aplicación.</li>
            </ul>";
        break;
    case 'estudiante':
        $privilegios = "
            <ul>
                <li>Acceso a materiales de clase.</li>
                <li>Envío de tareas y exámenes.</li>
                <li>Ver calificaciones y retroalimentación.</li>
            </ul>";
        break;
    default:
        $privilegios = "Rol no reconocido.";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
    <div class="welcome-box">
        <h2>Bienvenido, <?php echo $username; ?>!</h2>
        <p><strong>Rol:</strong> <?php echo ucfirst($role); ?></p>
        <h3>Privilegios Mínimos:</h3>
        <?php echo $privilegios; ?>
        
        <!-- Enlace para cerrar sesión -->
        <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
    </div>
</body>
</html>
