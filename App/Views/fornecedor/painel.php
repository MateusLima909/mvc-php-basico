<?php
    $fornecedor = $viewVar['fornecedor'] ?? null;
?>

<div class="container">
    <h3>Painel do Fornecedor</h3>
    <p>Bem-vindo, <?= htmlspecialchars($fornecedor ? $fornecedor->getNome() : 'Fornecedor') ?>!</p>
    <hr>

    <?php
        if (App\Lib\Sessao::existeMensagem()) {
            echo '<div class="alert alert-info" role="alert">' . App\Lib\Sessao::retornaMensagem() . '</div>';
            App\Lib\Sessao::limpaMensagem();
        }
    ?>

    <?php if ($fornecedor): ?>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nome/Razão Social:</strong><br> <?= htmlspecialchars($fornecedor->getNome()) ?></p>
                <p><strong>Nome Fantasia:</strong><br> <?= htmlspecialchars($fornecedor->getNomeFantasia()) ?></p>
                <p><strong>CNPJ:</strong><br> <?= htmlspecialchars($fornecedor->getCnpj()) ?></p>
                <p><strong>Inscrição Estadual:</strong><br> <?= htmlspecialchars($fornecedor->getInscricaoEstadual()) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Endereço:</strong><br> <?= htmlspecialchars($fornecedor->getEndereco()) ?></p>
                <p><strong>Telefone:</strong><br> <?= htmlspecialchars($fornecedor->getTelefone()) ?></p>
                <p><strong>Tipo de Serviço:</strong><br> <?= htmlspecialchars($fornecedor->getTipoDeServico()) ?></p>
            </div>
        </div>
        <hr>
        <a href="http://<?php echo APP_HOST; ?>/fornecedor/editarPainel" class="btn btn-primary">Editar Informações</a>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Não foi possível carregar as informações do seu painel.
        </div>
    <?php endif; ?>
</div>
