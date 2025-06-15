<?php
    $cliente = $viewVar['cliente'] ?? null;
?>
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h3>Editar Meu Perfil</h3>
            <p>Atualize suas informações pessoais abaixo.</p>
            <hr>

            <?php
                if (App\Lib\Sessao::existeMensagem()) {
                    echo '<div class="alert alert-warning" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                    App\Lib\Sessao::limpaMensagem();
                }
            ?>

            <?php if ($cliente): ?>
                <form action="http://<?php echo APP_HOST; ?>/cliente/atualizarPerfil" method="post">
                    <!-- Campo oculto para enviar o ID do cliente -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($cliente->getId()) ?>">

                    <h4>Dados Pessoais</h4>
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($cliente->getNome()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dtnasc">Data de Nascimento</label>
                        <input type="date" class="form-control" name="dtnasc" value="<?= htmlspecialchars($cliente->getDtnasc()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cpf">CPF (não pode ser alterado)</label>
                        <input type="text" class="form-control" name="cpf" value="<?= htmlspecialchars($cliente->getCpf()) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($cliente->getTelefone()) ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    <a href="http://<?php echo APP_HOST; ?>/cliente/perfil" class="btn btn-default">Cancelar</a>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Não foi possível carregar os dados para edição.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
