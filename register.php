<?php
include 'db.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql_check_user = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql_check_user);

    if ($result->num_rows > 0) {
        $message = "El nombre de usuario ya está registrado.";
        $message_type = 'danger';
    } else {
        // Insertar el nuevo usuario
        $sql_user = "INSERT INTO users (username, password, rol) VALUES ('$username', '$password', 'user')";
        if ($conn->query($sql_user) === TRUE) {
            $message = "Registro exitoso. <a href='login.php'>Inicia sesión</a>";
            $message_type = 'success';
        } else {
            $message = "Error: " . $conn->error;
            $message_type = 'danger';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.bundle.min.js"></script>
    <title>Registro de Usuario</title>
    <style>
        .register-container {
            margin-top: 100px;
        }
        .register-form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .alert-custom {
            margin-top: 20px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container register-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="register-form">
                <h2 class="text-center">Registro de Administrador</h2>
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?> alert-custom" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
                <div class="text-center mt-3">
                    <p>¿Ya tienes una cuenta? <a href="login.php" class="btn btn-link">Inicia Sesión</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
