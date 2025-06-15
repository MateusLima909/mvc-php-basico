<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">GESTÃO DE UTILIZADORES</h1>
        
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Exportar PDF</button>
            </div>
        </div>
    </div>

    <div class="container">

        <?php
            if (App\Lib\Sessao::existeMensagem()) {
                echo '<div class="alert alert-info" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
                App\Lib\Sessao::limpaMensagem();
            }
        ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
                        <th>CPF / CNPJ</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($viewVar['usuariosDetalhados'])): ?>
                        <?php foreach ($viewVar['usuariosDetalhados'] as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id']) ?></td>
                                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                                <td><?= htmlspecialchars($usuario['email']) ?></td>
                                <td>
                                    <span class="label 
                                        <?php 
                                            switch ($usuario['nivel_acesso']) {
                                                case 'admin': echo 'label-danger'; break;
                                                case 'fornecedor': echo 'label-success'; break;
                                                case 'cliente': echo 'label-primary'; break;
                                                default: echo 'label-default';
                                            }
                                        ?>
                                    "><?= htmlspecialchars(ucfirst($usuario['nivel_acesso'])) ?></span>
                                </td>
                                <td>
                                    <?php 
                                        // Exibe CPF se for cliente, ou CNPJ se for fornecedor
                                        if (!empty($usuario['cpf'])) {
                                            echo 'CPF: ' . htmlspecialchars($usuario['cpf']);
                                        } elseif (!empty($usuario['cnpj'])) {
                                            echo 'CNPJ: ' . htmlspecialchars($usuario['cnpj']);
                                        } else {
                                            echo 'N/A';
                                        }
                                    ?>
                                </td>
                                <td class="text-end">
                                    <a href="http://<?php echo APP_HOST; ?>/usuario/editar/<?= $usuario['id'] ?>" class="btn btn-info btn-sm" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <?php if ($_SESSION['usuario_id'] != $usuario['id']): // Impede o admin de se auto-excluir ?>
                                        <a href="http://<?php echo APP_HOST; ?>/usuario/excluir/<?= $usuario['id'] ?>" class="btn btn-danger btn-sm" title="Excluir" onclick="return confirm('Tem a certeza que deseja excluir este utilizador? Esta ação não pode ser desfeita.')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Nenhum utilizador encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
