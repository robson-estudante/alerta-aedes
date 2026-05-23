<?php
session_start();
include 'config.php';

if(!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_perfil'] != 'agente') {
    header("Location: index.php");
    exit();
}

// Pega o ID da denúncia que foi clicada
$id_denuncia = $_GET['id'];

// Busca os detalhes específicos dessa denúncia
$sql = "SELECT d.*, u.nome, u.email FROM denuncias d JOIN usuarios u ON d.usuario_id = u.id WHERE d.id = '$id_denuncia'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Denúncia não encontrada.";
    exit();
}
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Denúncia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; display: flex; justify-content: center; padding: 40px 20px; }
        .card-detalhes { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 600px; }
        .foto-foco { width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ddd; }
        .info-box { background: #fafafa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee; }
        .btn-voltar { color: #7f8c8d; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 20px; }
        .btn-resolver { background-color: #27ae60; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-resolver:hover { background-color: #219653; }
        .resolvido-badge { background-color: #27ae60; color: white; padding: 10px; text-align: center; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>

<div class="card-detalhes">
    <a href="agente.php" class="btn-voltar">← Voltar para o Painel</a>
    
    <h2 style="margin-top: 0;">Detalhes da Ocorrência #<?php echo $row['id']; ?></h2>

    <?php 
        if(!empty($row['foto']) && file_exists($row['foto'])): 
    ?>
        <div style="text-align: center;">
            <img src="<?php echo $row['foto']; ?>" alt="Foto do Foco" class="foto-foco">
        </div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; justify-content:center; align-items:center; background:#eee; color:#999; height: 200px; border-radius: 10px; margin-bottom: 20px;">
            <span>📷 Foto não encontrada no servidor</span>
            <p style="font-size: 12px; margin-top: 5px;">Caminho buscado: <?php echo empty($row['foto']) ? 'Nenhum' : $row['foto']; ?></p>
        </div>
    <?php endif; ?>

    <div class="info-box">
        <p><strong>Enviado por:</strong> <?php echo $row['nome']; ?> (<?php echo date('d/m/Y H:i', strtotime($row['data_envio'])); ?>)</p>
        <p><strong>Localização (Lat/Lng):</strong> <?php echo $row['latitude']; ?>, <?php echo $row['longitude']; ?></p>
        <p><strong>Descrição do Morador:</strong> <br> <?php echo $row['descricao']; ?></p>
    </div>

    <?php if($row['status'] == 'Pendente'): ?>
        <form action="resolver_foco.php" method="POST">
            <input type="hidden" name="id_denuncia" value="<?php echo $row['id']; ?>">
            <button type="submit" class="btn-resolver">✔️ Marcar como Resolvido</button>
        </form>
    <?php else: ?>
        <div class="resolvido-badge">
            ✅ Esta ocorrência já foi resolvida por um agente.
        </div>
    <?php endif; ?>

</div>

</body>
</html>