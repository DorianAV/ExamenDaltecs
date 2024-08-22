<?php
include 'db.php';
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}
$rol = $_SESSION['rol'];
$iduser = $_SESSION['iduser'];

if ($rol != 'admin') {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    if (!empty($titulo) && !empty($descripcion)) {


        $sql = "INSERT INTO publicaciones (titulo, descripcion, iduser) VALUES ('$titulo', '$descripcion', $iduser)";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "Publicación creada con éxito.";
        } else {
            $mensaje = "Error al crear la publicación: " . $conn->error;
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Crear Publicación</title>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">empresavirtual.mx</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <a href="dashboard.php" class="btn btn-info">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Crear Nueva Publicación</h5>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Publicación
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
