<div class="container">
    <div class="row">
        <div class="col-md-3"></div>

        <div class="col-md-6">
            <h3>Editar Fornecedor</h3>

            <?php if($Sessao::retornaMensagem()){ ?>
                <div class="alert alert-warning" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>

            <form action="http://<?php echo APP_HOST; ?>/fornecedor/atualizar" method="post" id="form_editar">
                <input type="hidden" name="id" value="<?php echo $viewVar['fornecedor']->getId(); ?>">

                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo $viewVar['fornecedor']->getNome(); ?>">
                </div>

                <div class="form-group">
                    <label for="nomeFantasia">Nome Fantasia</label>
                    <input type="text" class="form-control" name="nomeFantasia" value="<?php echo $viewVar['fornecedor']->getNomeFantasia(); ?>">
                </div>

                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" class="form-control" name="cnpj" value="<?php echo $viewVar['fornecedor']->getCnpj(); ?>">
                </div>

                 <div class="form-group">
                    <label for="inscricaoEstadual">Inscrição Estadual</label>
                    <input type="text" class="form-control" name="inscricaoEstadual" value="<?php echo $viewVar['fornecedor']->getInscricaoEstadual(); ?>">
                </div>

                 <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" class="form-control" name="endereco" value="<?php echo $viewVar['fornecedor']->getEndereco(); ?>">
                </div>

                 <div class="form-group">
                    <label for="tipoDeServico">Tipo de Serviço</label>
                    <input type="text" class="form-control" name="tipoDeServico" value="<?php echo $viewVar['fornecedor']->getTipoDeServico(); ?>">
                </div>

                 <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" name="telefone" value="<?php echo $viewVar['fornecedor']->getTelefone(); ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                <a href="http://<?php echo APP_HOST; ?>/fornecedor/listar" class="btn btn-secondary btn-sm">Cancelar</a>
            </form>
        </div>

        <div class="col-md-3"></div>
    </div>
</div> 
