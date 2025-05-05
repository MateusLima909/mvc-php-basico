<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">LISTA DE USUÁRIOS</h1>

        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" id="pdf" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-file-pdf"></i></button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="mensagem">
            <?php if ($Sessao::retornaMensagem()) { ?>
                <div class="alert <?php echo $Sessao::classeRetorno(); ?>" role="alert"><?php echo $Sessao::retornaMensagem(); ?></div>
            <?php } ?>
        </div>

        <button class="btn btn-nv right">
            <a href="http://<?php echo APP_HOST; ?>/usuario/cadastro">CRIAR NOVO<i class="fa-regular fa-square-plus"></i></a>
        </button>

        <div class="panel-body">
            <table class="table usuario" id="printTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>NOME</th>
                        <th>EMAIL</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($viewVar['usuarios'] as $usuario) { ?>
        <tr>
            <td><?php echo $usuario->getId(); ?></td>
            <td><?php echo $usuario->getNome(); ?></td>
            <td><?php echo $usuario->getEmail(); ?></td>
           
            <td align="right" class="acao">
                <a title="editar" href="http://<?php echo APP_HOST; ?>/usuario/editar/<?php echo $usuario->getId(); ?>" class="btn btn-edit edit"><i class="fa-solid fa-pen-to-square"></i></a>
                <a title="excluir" href="http://<?php echo APP_HOST; ?>/usuario/excluir/<?php echo $usuario->getId(); ?>" class="btn btn-delarquivo"><i class="fa-solid fa-trash-arrow-up"></i></a>
            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
        </div>
    </div>
</main>
