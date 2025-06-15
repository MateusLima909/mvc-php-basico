<?php
    // A variável $viewVar['fornecedor'] foi definida pelo método editarPainel() no seu controller.
    $fornecedor = $viewVar['fornecedor'] ?? null;
?>
<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h3>Editar Informações do Fornecedor</h3>
            <hr>

            <?php if ($fornecedor): ?>
                <form action="http://<?php echo APP_HOST; ?>/fornecedor/atualizarPainel" method="post">
                    <!-- Campo oculto para enviar o ID do fornecedor, importante para o método de atualização -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($fornecedor->getId()) ?>">

                    <div class="form-group">
                        <label for="nome">Nome / Razão Social</label>
                        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($fornecedor->getNome()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nomeFantasia">Nome Fantasia</label>
                        <input type="text" class="form-control" name="nomeFantasia" value="<?= htmlspecialchars($fornecedor->getNomeFantasia()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cnpj">CNPJ (não pode ser alterado)</label>
                        <input type="text" class="form-control" name="cnpj" value="<?= htmlspecialchars($fornecedor->getCnpj()) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="inscricaoEstadual">Inscrição Estadual</label>
                        <input type="text" class="form-control" name="inscricaoEstadual" value="<?= htmlspecialchars($fornecedor->getInscricaoEstadual()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="endereco">Endereço</label>
                        <input type="text" class="form-control" name="endereco" value="<?= htmlspecialchars($fornecedor->getEndereco()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoDeServico">Tipo de Serviço</label>
                        <input type="text" class="form-control" name="tipoDeServico" value="<?= htmlspecialchars($fornecedor->getTipoDeServico()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" name="telefone" value="<?= htmlspecialchars($fornecedor->getTelefone()) ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Guardar Alterações</button>
                    <a href="http://<?php echo APP_HOST; ?>/fornecedor/painel" class="btn btn-default">Cancelar</a>
                </form>
            <?php else: ?>
                <div class="alert alert-danger">Não foi possível carregar os dados para edição.</div>
            <?php endif; ?>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
