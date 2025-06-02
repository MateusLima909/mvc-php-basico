<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">LISTA DE CLIENTE</h1>

        <?php if($Sessao::retornaMensagem()){ ?>
                <div class="alert alert-success" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" id="pdf" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-file-pdf"></i></button>
            </div>
        </div>
    </div>

    <div class="container">
        <button class="btn btn-nv right">
            <a href="http://<?php echo APP_HOST; ?>/cliente/cadastro">CRIAR NOVO<i class="fa-regular fa-square-plus"></i></a>
        </button>

        <div class="panel-body">
            <table class="table cliente" id="printTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>DTNASC</th>
                        <th>CPF</th>
                        <th>TELEFONE</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($viewVar['cliente'] as $cliente) { ?>
        <tr>
            <td><?php echo $cliente->getId(); ?></td>
            <td><?php echo $cliente->getNome(); ?></td>
            <td><?php echo $cliente->getDtnasc(); ?></td>
            <td><?php echo $cliente->getCpf(); ?></td>
            <td><?php echo $cliente->getTelefone(); ?></td>
           
            <td align="right" class="acao">
                <a title="editar" href="http://<?php echo APP_HOST; ?>/cliente/editar/<?php echo $cliente->getId(); ?>" class="btn btn-edit edit"><i class="fa-solid fa-pen-to-square"></i></a>
                <a title="excluir" href="http://<?php echo APP_HOST; ?>/cliente/excluir/<?php echo $cliente->getId(); ?>" class="btn btn-delarquivo"><i class="fa-solid fa-trash-arrow-up"></i></a>
            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
        </div>
    </div>
</main>
