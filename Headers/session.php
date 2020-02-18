<?php
include('config.php');
session_start();

if(!isset($_SESSION['login_user'])){
  header("location:/Headers/login.php");
  die();
}

$user_check = $_SESSION['login_user'];
$tipo = $_SESSION['tipo'];

$sql = "select usuario from usuarios where usuario = '$user_check' and tipo = '$tipo'";
$result = $conn->query($sql);

if ($result != 1) {
    exit("Algo anduvo mal.");
}

if ($result->num_rows == 0) {
    session_destroy();
    header("location:/Headers/login.php");
    die();
}

if ($result->num_rows > 1) {
    exit("Error: hay dos usuarios con datos iguales.");
}

$row = $result->fetch_assoc();
$login_session = $row['usuario'];





?>