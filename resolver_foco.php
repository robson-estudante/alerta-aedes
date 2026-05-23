<?php
session_start();
include 'config.php';

if(!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_perfil'] != 'agente') {
    header("Location: index.php");
    exit();
}

$id_denuncia = $_POST['id_denuncia'];

// Comando SQL para ATUALIZAR (Update do CRUD)
$sql = "UPDATE denuncias SET status = 'Resolvido' WHERE id = '$id_denuncia'";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Ocorrência atualizada para Resolvida!'); window.location.href='agente.php';</script>";
} else {
    echo "<script>alert('Erro ao atualizar o status.'); window.location.href='detalhes.php?id=$id_denuncia';</script>";
}
?>