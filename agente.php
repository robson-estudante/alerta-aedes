<?php
session_start();
include 'config.php';

// Segurança: Só entra Agente logado
if(!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_perfil'] != 'agente') {
    header("Location: index.php");
    exit();
}

// Busca todas as denúncias para listar e para colocar no mapa
$sql = "SELECT d.id, d.descricao, d.latitude, d.longitude, d.status, d.data_envio, u.nome 
        FROM denuncias d 
        JOIN usuarios u ON d.usuario_id = u.id 
        ORDER BY d.data_envio DESC";
$result = $conn->query($sql);

$pontos_mapa = [];
if ($result->num_rows > 0) {
    while($row_mapa = $result->fetch_assoc()) {
        $pontos_mapa[] = $row_mapa;
    }
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Agente - Alerta Aedes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .btn-sair { background-color: #e74c3c; color: white; text-decoration: none; padding: 8px 15px; border-radius: 6px; font-weight: bold; }
        .dashboard-container { display: flex; gap: 20px; height: 80vh; }
        
        .mapa-container { flex: 1.5; background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        #mapa-agente { height: 100%; border-radius: 10px; border: 1px solid #ddd; }

        .lista-container { flex: 1; background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow-y: auto; }
        .card-ocorrencia { border: 1px solid #eee; padding: 15px; border-radius: 8px; margin-bottom: 10px; background: #fafafa; }
        .btn-detalhes { background-color: #34495e; color: white; text-decoration: none; padding: 8px 12px; border-radius: 6px; font-size: 13px; display: inline-block; margin-top: 10px; }
        .status-pendente { color: #e67e22; font-weight: bold; }
        .status-resolvido { color: #27ae60; font-weight: bold; }
        
        /* Estilos da Busca e Filtros */
        .busca-input { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc; font-family: 'Poppins', sans-serif; margin-bottom: 10px; box-sizing: border-box; }
        .btn-filtro { padding: 6px 12px; cursor: pointer; border-radius: 6px; border: 1px solid #ccc; font-family: 'Poppins', sans-serif; font-size: 13px; background: white; transition: 0.2s; }
        .btn-filtro.ativo-todos { background: #34495e; color: white; border-color: #34495e; }
        .btn-filtro.ativo-pendente { background: #fdf3e8; color: #e67e22; border-color: #e67e22; font-weight: bold; }
        .btn-filtro.ativo-resolvido { background: #e9f7ef; color: #27ae60; border-color: #27ae60; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Painel de Controle (Agente de Endemias)</h2>
        <div>
            <span>Agente: <strong><?php echo $_SESSION['usuario_nome']; ?></strong></span>
            <a href="logout.php" class="btn-sair" style="margin-left: 15px;">Sair</a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="mapa-container">
            <div id="mapa-agente"></div>
        </div>

        <div class="lista-container">
            <h3 style="margin-top: 0; margin-bottom: 15px;">Ocorrências Recentes</h3>
            
            <div style="margin-bottom: 20px;">
                <input type="text" id="campoBusca" class="busca-input" placeholder="🔍 Buscar por morador, ID ou descrição..." onkeyup="aplicarFiltros()">
                
                <div style="display: flex; gap: 8px;">
                    <button id="btn-Todos" class="btn-filtro ativo-todos" onclick="setFiltroStatus('Todos')">Todos</button>
                    <button id="btn-Pendente" class="btn-filtro" onclick="setFiltroStatus('Pendente')">Pendentes</button>
                    <button id="btn-Resolvido" class="btn-filtro" onclick="setFiltroStatus('Resolvido')">Resolvidos</button>
                </div>
            </div>

            <div id="lista-cards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="card-ocorrencia">
                            <p style="margin:0"><strong>ID #<?php echo $row['id']; ?> - <?php echo $row['nome']; ?></strong></p>
                            <p style="font-size: 13px; color: #666; margin: 5px 0;"><?php echo substr($row['descricao'], 0, 50); ?>...</p>
                            <span class="<?php echo $row['status'] == 'Pendente' ? 'status-pendente' : 'status-resolvido'; ?>" data-status="<?php echo $row['status']; ?>">
                                ● <?php echo $row['status']; ?>
                            </span>
                            <br>
                            <a href="detalhes.php?id=<?php echo $row['id']; ?>" class="btn-detalhes">Ver Detalhes / Resolver</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhuma denúncia cadastrada.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<script>
    let statusAtual = 'Todos';

    function setFiltroStatus(status) {
        statusAtual = status;
        
        // Remove as classes ativas de todos os botões
        document.getElementById('btn-Todos').className = 'btn-filtro';
        document.getElementById('btn-Pendente').className = 'btn-filtro';
        document.getElementById('btn-Resolvido').className = 'btn-filtro';
        
        // Adiciona a classe visual correta no botão clicado
        if(status === 'Todos') document.getElementById('btn-Todos').classList.add('ativo-todos');
        if(status === 'Pendente') document.getElementById('btn-Pendente').classList.add('ativo-pendente');
        if(status === 'Resolvido') document.getElementById('btn-Resolvido').classList.add('ativo-resolvido');

        aplicarFiltros();
    }

    function aplicarFiltros() {
        let termoBusca = document.getElementById('campoBusca').value.toLowerCase();
        let cards = document.querySelectorAll('.card-ocorrencia');
        
        cards.forEach(card => {
            let textoCard = card.innerText.toLowerCase();
            let correspondeBusca = textoCard.includes(termoBusca);
            
            // Pega o status exato lendo o atributo data-status escondido no HTML
            let spanStatus = card.querySelector('span[data-status]');
            let statusDoCard = spanStatus ? spanStatus.getAttribute('data-status') : '';
            
            let correspondeStatus = (statusAtual === 'Todos') || (statusDoCard === statusAtual);
            
            if (correspondeBusca && correspondeStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // MAPA
    // Define as duas opções de mapa do Google
    var mapaConvencional = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20, attribution: '© Google Maps'
    });

    var mapaSatelite = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20, attribution: '© Google Maps (Satélite)'
    });

    // Inicia o mapa com a visão 'Convencional' por padrão
    var map = L.map('mapa-agente', {
        center: [-10.1844, -48.3336],
        zoom: 12,
        layers: [mapaConvencional]
    });

    // Cria o botão flutuante de controle para o agente escolher
    var opcoesMapa = {
        "Mapa Convencional": mapaConvencional,
        "Satélite": mapaSatelite
    };
    L.control.layers(opcoesMapa).addTo(map);

    var iconeVermelho = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    var iconeVerde = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    var pontos = <?php echo json_encode($pontos_mapa); ?>;

    pontos.forEach(function(ponto) {
        var icone = (ponto.status === 'Pendente') ? iconeVermelho : iconeVerde;
        
        L.marker([ponto.latitude, ponto.longitude], {icon: icone})
            .addTo(map)
            .bindPopup(
                "<strong>Ocorrência #" + ponto.id + "</strong><br>" +
                "Morador: " + ponto.nome + "<br>" +
                "Status: " + ponto.status + "<br><br>" +
                "<a href='detalhes.php?id=" + ponto.id + "'>Ver detalhes desta denúncia</a>"
            );
    });

    if(pontos.length > 0) {
        var group = new L.featureGroup(pontos.map(p => L.marker([p.latitude, p.longitude])));
        map.fitBounds(group.getBounds().pad(0.1));
    }
</script>

</body>
</html>