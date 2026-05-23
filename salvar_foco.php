<?php
session_start();
include 'config.php';

if(!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$descricao  = $_POST['descricao'];
$latitude   = $_POST['latitude'];
$longitude  = $_POST['longitude'];
$endereco   = $_POST['endereco']; // Pegando o nome da rua novo!

$diretorio_destino = "uploads/";

if (!is_dir($diretorio_destino)) {
    mkdir($diretorio_destino, 0777, true);
}

$nome_arquivo = time() . "_" . basename($_FILES["foto"]["name"]);
$caminho_banco = $diretorio_destino . $nome_arquivo;

if (move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho_banco)) {
    // Inserindo o endereço junto no banco de dados
    $sql = "INSERT INTO denuncias (usuario_id, descricao, latitude, longitude, endereco, foto) 
            VALUES ('$usuario_id', '$descricao', '$latitude', '$longitude', '$endereco', '$caminho_banco')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Denúncia enviada com sucesso!'); window.location.href='morador.php';</script>";
    } else {
        echo "<script>alert('Erro ao gravar no banco de dados.'); window.location.href='morador.php';</script>";
    }
} else {
    echo "<script>alert('Erro ao fazer o upload da imagem.'); window.location.href='morador.php';</script>";
}
?>