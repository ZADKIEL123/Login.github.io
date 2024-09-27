<?php
session_start();

// Conexión a la base de datos
$conn = new mysqli('localhost', 'root', '', 'login');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error_message = "";

// Si se envía el formulario por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Obtener la información del usuario
    $sql = "SELECT * FROM usuarios WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar si el usuario está bloqueado
        if ($user['failed_attempts'] >= 3) {
            $error_message = "Usuario bloqueado por múltiples intentos fallidos.";
        } else {
            // Verificar si la contraseña es correcta
            if ($user['password'] === $password) {
                // Restablecer intentos fallidos en caso de login exitoso
                $sql_reset_attempts = "UPDATE usuarios SET failed_attempts = 0 WHERE username='$username'";
                $conn->query($sql_reset_attempts);

                // Login exitoso
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'];  // Almacenar el rol en la sesión

                header("Location: bienvenido.php");
                exit();
            } else {
                // Aumentar el contador de intentos fallidos
                $failed_attempts = $user['failed_attempts'] + 1;
                $sql_update_attempts = "UPDATE usuarios SET failed_attempts = $failed_attempts WHERE username='$username'";
                $conn->query($sql_update_attempts);

                if ($failed_attempts >= 3) {
                    $error_message = "Usuario bloqueado después de 3 intentos fallidos.";
                } else {
                    $error_message = "Usuario o contraseña incorrectos. Intentos fallidos: $failed_attempts/3.";
                }
            }
        }
    } else {
        $error_message = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body>
    <div class="login-box">
        <h2>Member Login</h2>
        <form method="POST" action="">
            <div class="textbox">
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <div class="textbox">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <input type="submit" class="btn" value="Login">
        </form>

        <!-- Mostrar el mensaje de error aquí si existe -->
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
