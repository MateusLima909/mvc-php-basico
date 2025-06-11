<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Corrigido o charset -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .login-container {
            border: 1px solid #ccc;
            padding: 20px 30px; 
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            max-width: 400px; 
            margin-left: auto;
            margin-right: auto;
            margin-top: 50px; /* Você pode ajustar conforme o layout final */
            margin-bottom: 40px;
            text-align: center; /* Centraliza o conteúdo do container */
        }
        .form-group {
            margin-bottom: 15px; 
            text-align: left; /* Alinha labels e inputs à esquerda dentro do form-group */
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
        }
        input[type="email"], input[type="password"] { 
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box; 
            border: 1px solid #ddd; 
            border-radius: 3px; 
        }
        button { 
            padding: 10px 15px; 
            background-color: #007bff; 
            color: white; 
            border: none; 
            border-radius: 3px; 
            cursor: pointer; 
            width: 100%; /* Botão ocupa a largura total */
            margin-bottom: 15px; /* Espaço abaixo do botão de entrar */
        }
        button:hover { 
            background-color: #0056b3; 
        }
        .error-message {
            color: red; 
            margin-bottom: 15px; 
        }
        .register-options p {
            margin-bottom: 8px; /* Menor espaço entre as opções de cadastro */
        }
        .register-options { 
            margin-top: 20px; /* Espaço acima das opções de cadastro */
            border-top: 1px solid #eee; /* Linha separadora */
            padding-top: 15px; /* Espaço após a linha */
        }
    </style>
</head>
<body>
    <?php
        // O header.php e menu.php (com a navbar) já são incluídos 
        // pelo método render() do seu Controller base ANTES desta view.
    ?>
    <div class="login-container">
        <h2>Login do Sistema</h2>
        <?php
            if (App\Lib\Sessao::existeMensagem()) {
                echo '<p class="error-message">' . App\Lib\Sessao::retornaMensagem() . '</p>';
                App\Lib\Sessao::limpaMensagem();
            }
            $emailPreenchido = '';
            if (App\Lib\Sessao::existeFormulario()) {
                $emailPreenchido = App\Lib\Sessao::retornaValorFormulario('email_login'); 
            }
        ?>

        <form action="http://<?php echo APP_HOST; ?>/login/autenticar" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($emailPreenchido) ?>" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>

        <div class="register-options">
            <p>Ainda não tem uma conta?</p>
            <p><a href="http://<?php echo APP_HOST; ?>/cliente/cadastro">Quero me cadastrar como Cliente</a></p>
            <p><a href="http://<?php echo APP_HOST; ?>/fornecedor/cadastro">Quero me cadastrar como Fornecedor</a></p>
        </div>
    </div>
</body>
</html>
