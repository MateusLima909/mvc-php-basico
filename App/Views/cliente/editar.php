<div class="container">
    <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <h3>Editar Cliente</h3>

            <?php if($Sessao::retornaMensagem()){ ?>
                <div class="alert alert-warning" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>

            <form action="http://<?php echo APP_HOST; ?>/cliente/atualizar" method="post" id="form_editar">
                <input type="hidden" name="id" value="<?php echo $viewVar['cliente']->getId(); ?>">

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $viewVar['cliente']->getNome(); ?>">
                </div>

                <div class="form-group">
                    <label for="dtnasc">Data de Nascimento</label>
                    <input type="dtnasc" class="form-control" name="dtnasc" value="<?php echo $viewVar['cliente']->getDtnasc(); ?>">
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="cpf" class="form-control" name="cpf" value="<?php echo $viewVar['cliente']->getCpf(); ?>">
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="telefone" class="form-control" name="telefone" value="<?php echo $viewVar['cliente']->getTelefone(); ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                <a href="http://<?php echo APP_HOST; ?>/cliente/listar" class="btn btn-secondary btn-sm">Cancelar</a>
            </form>
        </div>

        <div class="col-md-3"></div>
    </div>
</div>