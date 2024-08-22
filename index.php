<?php
session_start();

if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit();
} elseif ($_SESSION['logged'] == true) {
    header("Location: dashboard.php");
    exit();
}

exit();