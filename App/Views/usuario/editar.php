<?php
    $usuario = $viewVar['usuario'] ?? null;
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">EDITAR UTILIZADOR</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                
                <?php
                    if (App\Lib\Sessao::existeMensagem()) {
                        echo '<div class="alert alert-danger" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                        App\Lib\Sessao::limpaMensagem();
                    }
                ?>

                <?php if($usuario): ?>
                    <form action="http://<?php echo APP_HOST; ?>/usuario/atualizar" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario->getId()) ?>">

                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nivel_acesso">Nível de Acesso</label>
                            <select name="nivel_acesso" class="form-control" required>
                                <option value="cliente" <?= ($usuario->getNivelAcesso() == 'cliente') ? 'selected' : '' ?>>Cliente</option>
                                <option value="fornecedor" <?= ($usuario->getNivelAcesso() == 'fornecedor') ? 'selected' : '' ?>>Fornecedor</option>
                                <option value="admin" <?= ($usuario->getNivelAcesso() == 'admin') ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="senha">Nova Senha</label>
                            <input type="password" class="form-control" name="senha" placeholder="Deixe em branco para não alterar">
                            <small class="form-text text-muted">Se preenchido, a senha atual será substituída.</small>
                        </div>
                        
                        <br>

                        <button type="submit" class="btn btn-success">Guardar Alterações</button>
                        <a href="http://<?php echo APP_HOST; ?>/usuario/listarGeral" class="btn btn-default">Cancelar</a>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">Utilizador não encontrado.</div>
                    <a href="http://<?php echo APP_HOST; ?>/usuario/listarGeral" class="btn btn-info">Voltar para a Lista</a>
                <?php endif; ?>

            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</main>
