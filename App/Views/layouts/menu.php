<?php
// É crucial que a sessão seja iniciada ANTES de tentar acessar $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://<?php echo APP_HOST; ?>">Projeção Taguatinga</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if($viewVar['nameController'] == "HomeController") { ?> class="active" <?php } ?>>
                    <a href="http://<?php echo APP_HOST; ?>" >Home</a>
                </li>
                
                <?php if (isset($_SESSION['usuario_id'])): // Só mostra se estiver logado ?>
                    <!-- Exemplo para um futuro link de Listar Usuários (se existir e for para UsuarioController->listar) -->
                    <!-- 
                    <li <?php if($viewVar['nameController'] == "UsuarioController" && ($this->app->getAction() ?? '') === 'listar') { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/usuario/listar" >Listar Usuários</a>
                    </li> 
                    -->
                    <li <?php if($viewVar['nameController'] == "FornecedorController") { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/fornecedor/cadastro" >Cadastro de Fornecedor</a>
                    </li>
                    <li <?php if($viewVar['nameController'] == "ClienteController") { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/cliente/cadastro" >Cadastro de Cliente</a>
                    </li>
                <?php endif; // Fim do if para usuário logado ?>

                <?php if (!isset($_SESSION['usuario_id'])): // Só mostra se NÃO estiver logado ?>
                    <li <?php if($viewVar['nameController'] == "UsuarioController" && ($this->app->getAction() ?? '') === 'cadastro') { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/usuario/cadastro" >Cadastro de Usuário</a>
                    </li>
                    <li <?php if($viewVar['nameController'] == "LoginController" && ($this->app->getAction() ?? '') === 'index') { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/login" >Login</a>
                    </li>
                <?php else: // Está logado, mostra link de Logout ?>
                    <li>
                        <a href="http://<?php echo APP_HOST; ?>/login/logout">Sair (<?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>)</a>
                    </li>
                <?php endif; // Fim do if/else para login/logout ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>