<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h3>Cadastro de Cliente</h3>

            <?php if($Sessao::retornaMensagem()){ ?>
                <div class="alert alert-warning" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>

            <form action="http://<?php echo APP_HOST; ?>/cliente/salvar" method="post" id="form_cadastro">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control"  name="nome" placeholder="Digite seu nome" value="<?php echo $Sessao::retornaValorFormulario('nome'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" name="telefone" placeholder="Digite seu número de telefone" value="<?php echo $Sessao::retornaValorFormulario('telefone'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="date">Data de Nascimento</label>
                    <input type="date" class="form-control" name="dtnasc" placeholder="Digite sua data de nascimento" value="<?php echo $Sessao::retornaValorFormulario('dtnasc'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" name="cpf" placeholder="Digite seu cpf" value="<?php echo $Sessao::retornaValorFormulario('cpf'); ?>" required>
                </div>

                <button type="submit" class="btn btn-success btn-sm">Salvar</button>
                <a href="http://<?php echo APP_HOST; ?>/cliente/listar" class="btn btn-info">Listar cliente</a>
            </form>
        </div>
        <div class=" col-md-3"></div>
    </div>
</div>