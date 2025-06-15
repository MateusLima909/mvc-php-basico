<div class="container">
     <div class= "d-flex justify-content-between flex-wrap 
     flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
         <div class="starter-template">
            <h1>CADASTRO DE FORNECEDOR</h1>
         </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Excel</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">PDF</button>
            </div>
    </div>
    </div>
    </div>

        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                <h3>Novo Cadastro de Fornecedor</h3>
            <p>Preencha os dados da sua empresa e crie sua conta de acesso.</p>
            <hr>

            <?php
                if (App\Lib\Sessao::existeMensagem()) {
                    echo '<div class="alert alert-warning" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                    App\Lib\Sessao::limpaMensagem();
                }
            ?>

            <form action="http://<?php echo APP_HOST; ?>/fornecedor/salvar" method="post" id="form_cadastro">
                
                <h4>Dados da Empresa</h4>
                <form action="http://<?php echo APP_HOST; ?>/fornecedor/salvar" method="post" id="form_cadastro">
                <div class="form-group">
                <label for="nome">NOME</label>
                <INput type="text" class="form-control" name="nome" 
                placeholder="Digite seu nome" values="<?php echo $Sessao:: 
                retornaValorFormulario('nome');?>" required> </div>
                <div class="form-group">
                <label for="nome">NOME FANTASIA</label>
                <INput type="text" class="form-control" name="nomeFantasia" 
                placeholder="Digite o nome fantasia" values="<?php echo $Sessao:: 
                retornaValorFormulario('nomeFantasia');?>" required> </div>
               <div class="form-group">
               <label for="cnpj">CNPJ</label>
               <input type="text" class="form-control" name="cnpj" 
                placeholder="Digite seu CNPJ" value="<?php echo $Sessao::retornaValorFormulario('cnpj');?>" required>
                </div>
                <div class="form-group">
               <label for="inscricaoEstadual">INSCRIÇÃO ESTADUAL</label>
               <input type="text" class="form-control" name="inscricaoEstadual" 
                placeholder="Digite sua inscrição estadual" value="<?php echo $Sessao::retornaValorFormulario('inscricaoEstadual');?>" required>
                </div>
                <div class="form-group">
               <label for="endereco">ENDEREÇO</label>
               <input type="text" class="form-control" name="endereco" 
                placeholder="Digite seu endereço" value="<?php echo $Sessao::retornaValorFormulario('endereco');?>" required>
                </div>
                <div class="form-group">
               <label for="tipoDeServico">TIPO DE SERVIÇO</label>
               <input type="text" class="form-control" name="tipoDeServico" 
                placeholder="Digite o tipo de serviço" value="<?php echo $Sessao::retornaValorFormulario('tipoDeServico');?>" required>
                </div> 
                <div class="form-group">
               <label for="telefone">TELEFONE</label>
               <input type="text" class="form-control" name="telefone" 
                placeholder="Digite seu telefone" value="<?php echo $Sessao::retornaValorFormulario('telefone');?>" required>
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
            </div>
            </form>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>
