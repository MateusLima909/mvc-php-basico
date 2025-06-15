<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">LISTA DE FORNECEDORES</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" id="pdf" class="btn btn-sm btn-outline-secondary"><i class="fa-regular fa-file-pdf"></i> PDF</button>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <?php
            if (App\Lib\Sessao::existeMensagem()) {
                echo '<div class="alert alert-info" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                App\Lib\Sessao::limpaMensagem();
            }
        ?>

        <div class="mb-3">
            <a href="http://<?php echo APP_HOST; ?>/fornecedor/cadastro" class="btn btn-success">
                <i class="fa-regular fa-square-plus"></i> CRIAR NOVO FORNECEDOR
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="printTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome / Razão Social</th>
                        <th>Nome Fantasia</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($viewVar['fornecedores'])): ?>
                        <?php foreach ($viewVar['fornecedores'] as $fornecedor): ?>
                            <tr>
                                <td><?= htmlspecialchars($fornecedor->getId()) ?></td>
                                <td><?= htmlspecialchars($fornecedor->getNome()) ?></td>
                                <td><?= htmlspecialchars($fornecedor->getNomeFantasia()) ?></td>
                                <td><?= htmlspecialchars($fornecedor->getCnpj()) ?></td>
                                <td><?= htmlspecialchars($fornecedor->getTelefone()) ?></td>
                                <td class="text-end">
                                    <a title="Editar" href="http://<?php echo APP_HOST; ?>/fornecedor/editar/<?php echo $fornecedor->getId(); ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a title="Excluir" href="http://<?php echo APP_HOST; ?>/fornecedor/excluir/<?php echo $fornecedor->getId(); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem a certeza que deseja excluir este fornecedor?')"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Nenhum fornecedor encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
