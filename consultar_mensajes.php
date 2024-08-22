<?php
include 'db.php';
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}

$idemisor = $_SESSION['iduser'];

$sql = "SELECT DISTINCT u.iduser, u.username 
        FROM users u 
        JOIN mensajes m ON (m.emisor = u.iduser OR m.receptor = u.iduser) 
        WHERE (m.emisor = $idemisor OR m.receptor = $idemisor) 
        AND u.iduser != $idemisor";
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
    <title>Consultar Mensajes - Empresavirtual</title>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Empresavirtual.mx</a>
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
                <i class="fas fa-arrow-left"></i> Inicio
            </a>
        </div>
    </div>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['username']); ?></h5>
                        <a href="mensajes.php?iduser=<?php echo urlencode($row['iduser']); ?>" class="btn btn-primary">
                            Ir a Mensajes
                        </a>
                    </div>
                </div>
            </div>
        <?php } if(!$result->num_rows > 0) {?>
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> No hay mensajes disponibles.
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
