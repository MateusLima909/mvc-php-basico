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
                
                <?php if (isset($_SESSION['usuario_id'])): // Menu para UTILIZADORES LOGADOS ?>
                    
                    <?php if ($_SESSION['usuario_nivel'] === 'admin'): // Links específicos para Admin ?>
                        <li <?php if($viewVar['nameController'] == "UsuarioController") { ?> class="active" <?php } ?>>
                            <a href="http://<?php echo APP_HOST; ?>/usuario/listarGeral">Gerir Utilizadores</a>
                        </li>
                        <li <?php if($viewVar['nameController'] == "ClienteController") { ?> class="active" <?php } ?>>
                            <a href="http://<?php echo APP_HOST; ?>/cliente/listar">Listar Clientes</a>
                        </li>
                         <li <?php if($viewVar['nameController'] == "FornecedorController") { ?> class="active" <?php } ?>>
                            <a href="http://<?php echo APP_HOST; ?>/fornecedor/listar">Listar Fornecedores</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['usuario_nivel'] === 'cliente'): // Links específicos para Cliente ?>
                        <li <?php if(($this->app->getAction() ?? '') === 'perfil') { ?> class="active" <?php } ?>>
                            <a href="http://<?php echo APP_HOST; ?>/cliente/perfil">Meu Perfil</a>
                        </li>
                    <?php endif; ?>

                     <?php if ($_SESSION['usuario_nivel'] === 'fornecedor'): // Links específicos para Fornecedor ?>
                        <li <?php if(($this->app->getAction() ?? '') === 'painel') { ?> class="active" <?php } ?>>
                            <a href="http://<?php echo APP_HOST; ?>/fornecedor/painel">Meu Painel</a>
                        </li>
                    <?php endif; ?>

                <?php endif; // Fim do menu para utilizadores logados ?>

                <?php if (!isset($_SESSION['usuario_id'])): // Menu para VISITANTES (NÃO LOGADOS) ?>
                    <li <?php if($viewVar['nameController'] == "LoginController") { ?> class="active" <?php } ?>>
                        <a href="http://<?php echo APP_HOST; ?>/login" >Login</a>
                    </li>
                <?php else: // Link de Logout para utilizadores logados ?>
                    <li>
                        <a href="http://<?php echo APP_HOST; ?>/login/logout">Sair (<?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Utilizador') ?>)</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
