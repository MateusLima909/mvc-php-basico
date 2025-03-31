<div class="container>
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
                    <h3>Cadastro de Fornecedor</h3>
                    <?php if($Sessao::retornaMensagem()){?>
                <div class="alert alert-warning" role= 
                    "alert"> <?php echo $Sessao::retornaMensagem(); ?></div>
                <?php } ?>
                <form action="http://<?php echo APP_HOST; ?>/fornecedor/salvar" metodh="post" id="form_cadastro">
                <div class="form-group">
                <label for="nome">NOME</label>
                <INput type="text" class="form-control" name="nome" 
                placeholder="Seu nome" values="<?php echo $Sessao:: 
                retornaValorFormulario('nome');?>" required> </div>
                <div class="form-group">
                <label for="nome">NOMEFANTASIA</label>
                <INput type="text" class="form-control" name="nomefantasia" 
                placeholder="Seu nome fantasia" values="<?php echo $Sessao:: 
                retornaValorFormulario('nomefantasia');?>" required> </div>
               <div class="form-group">
               <label for="cnpj">CNPJ</label>
               <input type="text" class="form-control" name="cnpj" 
                placeholder="Seu CNPJ" value="<?php echo $Sessao::retornaValorFormulario('cnpj');?>" required>
                </div>
                <div class="form-group">
               <label for="inscricaoEstadual">inscricaoEstadual</label>
               <input type="text" class="form-control" name="inscricaoEstadual" 
                placeholder="Sua inscricaoEstadual" value="<?php echo $Sessao::retornaValorFormulario('inscricaoEstadual');?>" required>
                </div>
                <div class="form-group">
               <label for="endereco">ENDERECO</label>
               <input type="text" class="form-control" name="endereco" 
                placeholder="Seu ENDERECO" value="<?php echo $Sessao::retornaValorFormulario('ENDERECO');?>" required>
                </div>
                <div class="form-group">
               <label for="tipoDeServico">TIPODESERVICO</label>
               <input type="text" class="form-control" name="tipoDeServico" 
                placeholder="Seu tipoDeServico" value="<?php echo $Sessao::retornaValorFormulario('tipoDeServico');?>" required>
                </div> 
                <div class="form-group">
               <label for="telefone">TELEFONE</label>
               <input type="text" class="form-control" name="telefone" 
                placeholder="Seu telefone" value="<?php echo $Sessao::retornaValorFormulario('telefone');?>" required>
                </div>





                <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class=" form-control" name="email" 
                placeholder="" value="<?php echo $Sessao::
                retornaValorFormulario ('email'); ?>" required>
    </div>
         <button type="submit" class="btn btn-success btn-sm">Salvar</button> 
         </INput>
            </div></form>
                </div>
                <div class="col-md-3"></div>

            </div> </div>
