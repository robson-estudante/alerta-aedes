<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // No XAMPP a senha padrão é em branco
$db   = 'alerta_aedes';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>