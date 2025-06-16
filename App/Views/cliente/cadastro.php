<?php
   
?>

<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h3>Cadastro de Novo BOSTA</h3>
            <p>Preencha seus dados e crie sua conta de acesso ao sistema.</p>
            <hr>

            <?php
                if (App\Lib\Sessao::existeMensagem()) {
                    echo '<div class="alert alert-warning" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                    App\Lib\Sessao::limpaMensagem();
                }
            ?>

            <form action="http://<?php echo APP_HOST; ?>/cliente/salvar" method="post" id="form_cadastro">
                
                <h4>Dados Pessoais</h4>
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" class="form-control" name="nome" placeholder="Digite seu nome completo" value="<?php echo App\Lib\Sessao::retornaValorFormulario('nome'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dtnasc">Data de Nascimento</label>
                    <input type="date" class="form-control" name="dtnasc" value="<?php echo App\Lib\Sessao::retornaValorFormulario('dtnasc'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" class="form-control" name="cpf" placeholder="Digite seu CPF" value="<?php echo App\Lib\Sessao::retornaValorFormulario('cpf'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" name="telefone" placeholder="Digite seu número de telefone" value="<?php echo App\Lib\Sessao::retornaValorFormulario('telefone'); ?>" required>
                </div>
                
                <hr>

                <h4>Dados da Conta de Acesso</h4>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" name="email" placeholder="será seu usuário de login" value="<?php echo App\Lib\Sessao::retornaValorFormulario('email'); ?>" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" name="senha" placeholder="Mínimo de 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" class="form-control" name="confirmar_senha" placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="btn btn-success">Finalizar Cadastro</button>
                <a href="http://<?php echo APP_HOST; ?>/login" class="btn btn-default">Cancelar</a>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<?php
    
?>
