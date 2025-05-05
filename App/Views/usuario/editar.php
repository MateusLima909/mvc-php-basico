<div class="container">
    <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <h3>Editar Usuário</h3>

            <?php if($Sessao::retornaMensagem()){ ?>
                <div class="alert alert-warning" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>

            <form action="http://<?php echo APP_HOST; ?>/usuario/atualizar" method="post" id="form_editar">
                <input type="hidden" name="id" value="<?php echo $viewVar['usuario']->getId(); ?>">

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $viewVar['usuario']->getNome(); ?>">
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $viewVar['usuario']->getEmail(); ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                <a href="http://<?php echo APP_HOST; ?>/usuario/listar" class="btn btn-secondary btn-sm">Cancelar</a>
            </form>
        </div>

        <div class="col-md-3"></div>
    </div>
</div>