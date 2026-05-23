<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta Aedes - Login</title>
    <style>
        body {
            background: linear-gradient(135deg, #1d4e89 0%, #00b4d8 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            width: 300px;
            text-align: center;
            color: white;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
            border: none;
        }
        button {
            width: 95%;
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            border: none;
            background-color: #2ecc71;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background-color: #27ae60; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>🦟 Alerta Aedes</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">ENTRAR</button>
            <a href="cadastro.php" style="display: block; margin-top: 20px; color: white; text-decoration: none; font-size: 14px;">Não tem uma conta? Cadastre-se</a>
        </form>
    </div>
</body>
</html>