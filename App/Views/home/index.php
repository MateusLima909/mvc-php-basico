<?php
// No topo do seu arquivo App/Views/home/index.php (ou onde for apropriado)
// Garante que a sessão esteja iniciada se for verificar variáveis de sessão aqui.
// Sua classe Sessao::iniciar() ou o construtor do Controller podem já ter feito isso.
// Exemplo: App\Lib\Sessao::iniciar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Inicial</title>
    </head>
<body> <?php
        // O header.php e menu.php são incluídos pelo método render() do seu Controller base ANTES desta view.
        // Portanto, a navbar já estará presente.
    ?>

    <div class="container"> <?php // Movido para DENTRO do body ?>
        <h1>Bem-vindo à sua Página Inicial!</h1>

        <?php if (isset($_SESSION['usuario_id'])): // Só mostra se o usuário estiver logado ?>
            <p>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</p>
            <p>Seu nível de acesso é: <?= htmlspecialchars($_SESSION['usuario_nivel']) ?></p>
            
            <hr>
            
            <p>
                <a href="http://<?php echo APP_HOST; ?>/login/logout">Sair do Sistema</a>
            </p>
            
        <?php else: ?>
            <p>Você não está logado. <a href="http://<?php echo APP_HOST; ?>/login">Faça o login</a>.</p>
        <?php endif; ?>

        <div class="starter-template" style="padding-top: 20px;"> <?php // Adicionei um padding para separar do conteúdo acima ?>
            <h1>Primeira aplicação MVC em PHP</h1>
        </div>

    </div> <?php
        // O footer.php é incluído pelo método render() do seu Controller base DEPOIS desta view.
    ?>

</body> 
</html>
