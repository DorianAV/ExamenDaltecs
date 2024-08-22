<?php
include 'db.php';
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}
$rol = $_SESSION['rol'];
$iduser = $_SESSION['iduser'];

if ($rol === 'user') {
    $sql = "SELECT * FROM publicaciones";
} elseif ($rol === 'admin') {
    $sql = "SELECT * FROM publicaciones WHERE iduser = $iduser";
} else {
    echo "Rol no válido.";
    exit();
}

$result = $conn->query($sql);
?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title>empresavirtual.mx</title>
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
                <?php if ($rol === 'admin') { ?>
                    <a href="crear_publicacion.php" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> Crear Publicación Nueva
                    </a>
                <?php } ?>
                <a href="consultar_mensajes.php" class="btn btn-info">
                    <i class="fas fa-envelope"></i> Consultar Mensajes
                </a>
            </div>
        </div>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['titulo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['descripcion']); ?></p>
                                <?php if ($rol == 'user'): ?>
                                    <a href="mensajes.php?iduser=<?php echo urlencode($row['iduser']); ?>" class="btn btn-primary">
                                        Mandar mensaje
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {?>
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> No hay publicaciones disponibles.
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    </body>
    </html>

<?php
$conn->close();
?>