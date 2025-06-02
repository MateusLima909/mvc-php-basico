<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">LISTA DE FORNECEDORES</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" id="pdf" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-file-pdf"></i></button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="mensagem">
            <?php if ($Sessao::retornaMensagem()) { ?>
                <div class="alert alert-success" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>
        </div>

        <button class="btn btn-nv right">
            <a href="http://<?php echo APP_HOST; ?>/fornecedor/cadastro">CRIAR NOVO<i class="fa-regular fa-square-plus"></i></a>
        </button>

        <div class="panel-body">
            <table class="table fornecedor" id="printTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>NOME FANTASIA</th>
                        <th>CNPJ</th>
                        <th>INSCRIÇÃO ESTADUAL</th>
                        <th>ENDEREÇO</th>
                        <th>TIPO DE SERVIÇO</th>
                        <th>TELEFONE</th>
                        <th>AÇÕES</th>                       
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($viewVar['fornecedor'] as $fornecedor) { ?>
        <tr>
            <td><?php echo $fornecedor->getId(); ?></td>
            <td><?php echo $fornecedor->getNome(); ?></td>
            <td><?php echo $fornecedor->getNomeFantasia(); ?></td>
            <td><?php echo $fornecedor->getCnpj(); ?></td>
            <td><?php echo $fornecedor->getInscricaoEstadual(); ?></td>
            <td><?php echo $fornecedor->getEndereco(); ?></td>
            <td><?php echo $fornecedor->getTipoDeServico(); ?></td>
            <td><?php echo $fornecedor->getTelefone(); ?></td>
           
            <td align="right" class="acao">
                <a title="editar" href="http://<?php echo APP_HOST; ?>/fornecedor/editar/<?php echo $fornecedor->getId(); ?>" class="btn btn-edit edit"><i class="fa-solid fa-pen-to-square"></i></a>
                <a title="excluir" href="http://<?php echo APP_HOST; ?>/fornecedor/excluir/<?php echo $fornecedor->getId(); ?>" class="btn btn-delarquivo"><i class="fa-solid fa-trash-arrow-up"></i></a>
            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
        </div>
    </div>
</main>
