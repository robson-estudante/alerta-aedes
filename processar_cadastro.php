<?php
session_start();
include 'config.php';

$nome  = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Tenta inserir no banco de dados. O perfil 'morador' vai por padrão.
$sql = "INSERT INTO usuarios (nome, email, senha, perfil) VALUES ('$nome', '$email', '$senha', 'morador')";

if ($conn->query($sql) === TRUE) {
    // Se der certo, avisa e manda para a tela de login
    echo "<script>alert('Cadastro realizado com sucesso! Você já pode fazer o seu login.'); window.location.href='index.php';</script>";
} else {
    // Se der erro (ex: e-mail já cadastrado)
    echo "<script>alert('Erro ao cadastrar. Este e-mail pode já estar em uso.'); window.location.href='cadastro.php';</script>";
}
?>