<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alerta Aedes - Cadastre-se</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1d4e89 0%, #00b4d8 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            width: 350px;
            text-align: center;
            color: white;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.8);
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: #2ecc71;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        button:hover { background: #27ae60; }
        .link-voltar {
            display: block;
            margin-top: 20px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .link-voltar:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Criar Conta</h2>
        <p style="font-size: 14px; margin-bottom: 20px;">Faça parte do combate à Dengue!</p>
        
        <form action="processar_cadastro.php" method="POST">
            <input type="text" name="nome" placeholder="Seu Nome Completo" required>
            <input type="email" name="email" placeholder="Seu melhor E-mail" required>
            <input type="password" name="senha" placeholder="Crie uma Senha" required>
            
            <button type="submit">Cadastrar</button>
        </form>
        
        <a href="index.php" class="link-voltar">Já tem uma conta? Faça login</a>
    </div>
</body>
</html>