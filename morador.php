<?php
session_start();
include 'config.php';

if(!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_perfil'] != 'morador') {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$sql_minhas = "SELECT * FROM denuncias WHERE usuario_id = '$usuario_id' ORDER BY data_envio DESC";
$result_minhas = $conn->query($sql_minhas);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Foco - Alerta Aedes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <style>
        body { background-color: #f4f7f6; font-family: 'Poppins', sans-serif; margin: 0; display: flex; flex-direction: column; align-items: center; padding: 40px 20px; }
        .container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); width: 100%; max-width: 550px; margin-bottom: 20px; }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .btn-sair { color: #e74c3c; text-decoration: none; font-weight: bold; font-size: 14px; }
        h2 { text-align: center; color: #333; margin-top: 0; }
        .box-upload { border: 2px dashed #ccc; border-radius: 10px; padding: 30px; text-align: center; background-color: #fafafa; margin-bottom: 20px; cursor: pointer; }
        .box-upload:hover { border-color: #3498db; }
        input[type="file"] { display: none; }
        .custom-file-upload { display: inline-block; cursor: pointer; color: #555; font-weight: 500; }
        label { font-weight: 500; font-size: 14px; color: #444; }
        input[type="text"], textarea { width: 100%; padding: 12px; margin: 8px 0 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: 'Poppins', sans-serif; background-color: #fcfcfc; }
        input[readonly] { background-color: #e9ecef; color: #333; font-weight: 500; cursor: not-allowed; border: 2px solid #3498db; }
        button { width: 100%; padding: 15px; background-color: #555; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        button:hover { background-color: #333; }
        #mapa { height: 300px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #ddd; z-index: 1; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background-color: #f9fbfd; color: #555; }
        .status-pendente { color: #e67e22; font-weight: bold; background: #fdf3e8; padding: 4px 8px; border-radius: 4px; }
        .status-resolvido { color: #27ae60; font-weight: bold; background: #e9f7ef; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-top">
        <span style="font-size: 14px; color: #666;">Morador: <strong><?php echo $_SESSION['usuario_nome']; ?></strong></span>
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>

    <h2>Registrar Foco</h2>

    <form action="salvar_foco.php" method="POST" enctype="multipart/form-data">
        <label for="foto-upload" class="box-upload" style="display: block;">
            <div class="custom-file-upload">📷 Clique para Anexar Foto do Local</div>
            <input id="foto-upload" type="file" name="foto" accept="image/*" required onchange="document.querySelector('.custom-file-upload').innerHTML = '✅ Arquivo: ' + this.files[0].name">
        </label>

        <label>🗺️ Clique no mapa ou arraste o pino para o local:</label>
        <div id="mapa"></div>
        
        <input type="hidden" id="lat" name="latitude" required>
        <input type="hidden" id="lng" name="longitude" required>

        <label>Endereço Encontrado:</label>
        <input type="text" id="endereco" name="endereco" placeholder="Aguardando marcação no mapa..." readonly required>

        <label>Descrição do problema</label>
        <textarea name="descricao" rows="3" placeholder="Ex: Água parada em pneus velhos no terreno..." required></textarea>

        <button type="submit">Enviar Denúncia</button>
    </form>
</div>

<div class="container">
    <h3 style="margin-top: 0; color: #333; font-size: 18px;">Acompanhar Minhas Denúncias</h3>
    <?php if ($result_minhas->num_rows > 0): ?>
        <table>
            <thead><tr><th>Data</th><th>Endereço/Local</th><th>Status</th></tr></thead>
            <tbody>
                <?php while($row = $result_minhas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($row['data_envio'])); ?></td>
                        <td><?php echo empty($row['endereco']) ? 'Localização via Mapa' : substr($row['endereco'], 0, 35).'...'; ?></td>
                        <td><span class="<?php echo $row['status'] == 'Pendente' ? 'status-pendente' : 'status-resolvido'; ?>"><?php echo $row['status']; ?></span></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #777; font-size: 14px;">Você ainda não registrou nenhum foco.</p>
    <?php endif; ?>
</div>

<script>
   // Define as duas opções de mapa do Google
    var mapaConvencional = L.tileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20, attribution: '© Google Maps'
    });

    var mapaSatelite = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20, attribution: '© Google Maps (Satélite)'
    });

    // Inicia o mapa com a visão 'Convencional' por padrão
    var map = L.map('mapa', {
        center: [-10.1844, -48.3336],
        zoom: 13,
        layers: [mapaConvencional]
    });

    // Cria o botão flutuante de controle para o usuário escolher
    var opcoesMapa = {
        "Mapa Convencional": mapaConvencional,
        "Satélite": mapaSatelite
    };
    L.control.layers(opcoesMapa).addTo(map);

    var marker = L.marker([-10.1844, -48.3336], {draggable: true}).addTo(map);

    marker.on('dragend', function (e) { atualizarInputs(marker.getLatLng()); });
    map.on('click', function(e) { marker.setLatLng(e.latlng); atualizarInputs(e.latlng); });

    var geocoder = L.Control.geocoder({ defaultMarkGeocode: false, placeholder: "Buscar rua, cidade..." })
    .on('markgeocode', function(e) {
        var center = e.geocode.center;
        map.setView(center, 16); 
        marker.setLatLng(center); 
        atualizarInputs(center);
    }).addTo(map);

    map.locate({setView: true, maxZoom: 16});
    map.on('locationfound', function(e) {
        marker.setLatLng(e.latlng); 
        atualizarInputs(e.latlng);
    });

    // A MÁGICA ACONTECE AQUI: Transforma as coordenadas em nome da rua
    function atualizarInputs(posicao) {
        document.getElementById('lat').value = posicao.lat.toFixed(6);
        document.getElementById('lng').value = posicao.lng.toFixed(6);
        
        document.getElementById('endereco').value = "Buscando endereço...";

        // Faz uma requisição na API gratuita de mapas para ler a rua
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${posicao.lat}&lon=${posicao.lng}`)
            .then(response => response.json())
            .then(data => {
                if(data && data.display_name) {
                    document.getElementById('endereco').value = data.display_name;
                } else {
                    document.getElementById('endereco').value = "Endereço exato não encontrado, mas a localização foi salva.";
                }
            })
            .catch(error => {
                document.getElementById('endereco').value = "Erro ao buscar nome da rua. A localização no mapa será usada.";
            });
    }
</script>

</body>
</html>