<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF- <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        margin-top: 100px; 
        margin-bottom: 40px
        }

        .form-group {
            margin-bottom: 15px; 
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
        }
       
        button:hover { 
            background-color: #0056b3; 
        }

        .error-message {
            color: red; 
            margin-bottom: 15px; 
        }

        .register-link { 
            margin-top: 15px; 
        }
        </style>
    </head>

<body>
    <div class="login-container">
    <h2>Login do Sistema</h2>
    <?php
            // Para exibir mensagens de erro ou sucesso vindas do controller
            if (App\Lib\Sessao::existeMensagem()) {
                echo '<p class="error-message">' . App\Lib\Sessao::retornaMensagem() . '</p>';
                App\Lib\Sessao::limpaMensagem();
            }

            // Para repopular o email em caso de erro, se você gravou na sessão
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
        <div class="register-link">
            <p>Não tem uma conta? <a href="http://<?php echo APP_HOST; ?>/usuario/cadastro">Cadastre-se aqui</a></p>
        </div>
    </div>
</body>
</html>
