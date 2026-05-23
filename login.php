<?php
session_start();
include 'config.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['usuario_id'] = $row['id'];
    $_SESSION['usuario_nome'] = $row['nome'];
    $_SESSION['usuario_perfil'] = $row['perfil'];
    
    // O redirecionamento atualizado que estava faltando a chave
    if ($_SESSION['usuario_perfil'] == 'morador') {
        header("Location: morador.php");
    } else {
        header("Location: agente.php");
    }
} else {
    echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='index.php';</script>";
}
?>