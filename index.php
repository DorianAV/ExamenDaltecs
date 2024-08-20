<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
}

$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;

switch ($rol) {
    case 'admin':
        header("Location: dashboard_admin.php");
        break;
    case 'user':
        header("Location: dashboard_user.php");
        break;

}

exit();
?>
