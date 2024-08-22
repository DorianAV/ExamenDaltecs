<?php
include 'db.php';
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['iduser'])) {
    header("Location: login.php");
    exit();
}

$idreceptor = intval($_GET['iduser']);
$idemisor = $_SESSION['iduser'];

$sql = "SELECT * FROM mensajes 
        WHERE (emisor = $idemisor AND receptor = $idreceptor) 
        OR (emisor = $idreceptor AND receptor = $idemisor)
        ORDER BY fecha ASC";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = mysqli_real_escape_string($conn, $_POST['mensaje']);
    $fecha = date('Y-m-d H:i:s');

    $sql = "INSERT INTO mensajes (mensaje, fecha, emisor, receptor) 
            VALUES ('$mensaje', '$fecha', $idemisor, $idreceptor)";
    if (mysqli_query($conn, $sql)) {
        $idmensaje = mysqli_insert_id($conn);

        if (!empty($_FILES['foto']['name']) || !empty($_FILES['audio']['name'])) {
            $uploadDir = 'files/';

            if (!empty($_FILES['foto']['name'])) {
                $fotoFile = $_FILES['foto'];
                $fotoFileName = uniqid() . '-' . basename($fotoFile['name']);
                $fotoFilePath = $uploadDir . $fotoFileName;

                if (move_uploaded_file($fotoFile['tmp_name'], $fotoFilePath)) {
                    $sql = "INSERT INTO files (nombre, tipo, ruta, idmensaje) 
                            VALUES ('$fotoFileName', 'imagen', '$fotoFilePath', $idmensaje)";
                    if (!mysqli_query($conn, $sql)) {
                        echo "Error al insertar imagen en la base de datos: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error al mover el archivo de foto.";
                }
            }

            if (!empty($_FILES['audio']['name'])) {
                $audioFile = $_FILES['audio'];
                $audioFileName = uniqid() . '-' . basename($audioFile['name']);
                $audioFilePath = $uploadDir . $audioFileName;

                if (!move_uploaded_file($audioFile['tmp_name'], $audioFilePath)) {
                    echo "Error al mover el archivo de audio. Código de error: " . $_FILES['audio']['error'];
                } else {
                    $sql = "INSERT INTO files (nombre, tipo, ruta, idmensaje) 
                            VALUES ('$audioFileName', 'audio', '$audioFilePath', $idmensaje)";
                    if (!mysqli_query($conn, $sql)) {
                        echo "Error al insertar audio en la base de datos: " . mysqli_error($conn);
                    }
                }
            }
        }


        header("Location: mensajes.php?iduser=$idreceptor");
        exit();
    } else {
        echo "Error al insertar el mensaje en la base de datos: " . mysqli_error($conn);
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
    <title>Chat - Empresavirtual</title>
    <style>
        .chat-container {
            height: 70vh;
            overflow-y: scroll;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .chat-message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .chat-message.emisor {
            background-color: #d1e7dd;
            text-align: right;
        }

        .chat-message.receptor {
            background-color: #f8d7da;
            text-align: left;
        }

        .message-input {
            margin-top: 10px;
        }

        .textarea-custom {
            width: 50%;
        }

        .file-input-custom {
            width: 20%;
        }

        .chat-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
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
        <div class="col-12">
            <div class="chat-container">
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    // Mostrar el mensaje
                    ?>
                    <div class="chat-message <?php echo $row['emisor'] == $idemisor ? 'emisor' : 'receptor'; ?>">
                        <?php echo htmlspecialchars($row['mensaje']); ?><br>
                        <small><?php echo htmlspecialchars($row['fecha']); ?></small> <br>

                        <?php
                        $idmensaje = $row['idmensaje'];
                        $sqlFiles = "SELECT * FROM files WHERE idmensaje = $idmensaje";
                        $resultFiles = mysqli_query($conn, $sqlFiles);

                        while ($fileRow = mysqli_fetch_assoc($resultFiles)) {
                            if ($fileRow['tipo'] === 'imagen') { ?>
                                <img src="<?php echo $fileRow['ruta']; ?>" alt="Imagen" class="chat-image">
                            <?php } elseif ($fileRow['tipo'] === 'audio') { ?>
                                <audio controls>
                                    <source src="<?php echo $fileRow['ruta']; ?>" type="audio/mpeg">
                                    Tu navegador no soporta el elemento de audio.
                                </audio>
                            <?php }
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="message-input d-flex align-items-center mt-3">
                    <textarea name="mensaje" class="form-control textarea-custom" rows="2"
                              placeholder="Escribe un mensaje..." required></textarea>

                    <div class="file-input-custom mx-2">
                        <label class="file-label">
                            <i class="fas fa-camera"></i> Adjuntar foto
                        </label>
                        <input type="file" name="foto" accept="image/*" class="form-control">
                    </div>

                    <div class="file-input-custom mx-2">
                        <label class="file-label">
                            <i class="fas fa-microphone"></i> Adjuntar audio
                        </label>
                        <input type="file" name="audio" accept="audio/*" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
