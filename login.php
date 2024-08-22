<?php
include 'db.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['iduser'] = $row['iduser'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['rol'] = $row['rol'];
        $_SESSION['logged'] = true;
        header("Location: index.php");
    }
    else {
        $message = "Nombre de usuario o contraseña incorrectos.";

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
    <title>Inicio de Sesión</title>
    <style>
        .login-container {
            margin-top: 100px;
        }

        .login-form {
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
<body>
<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="login-form">
                <h2 class="text-center">Inicio de Sesión</h2>
                <?php if ($message): ?>
                    <div class="alert alert-danger alert-custom" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                </form>
                <div class="text-center mt-3">
                    <p>¿No tienes una cuenta? <a href="register.php" class="btn btn-link">Regístrate</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
