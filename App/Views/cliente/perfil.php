<?php
    // O header.php e menu.php já são incluídos pelo render() do seu Controller base.
    // A variável $viewVar['cliente'] foi definida pelo método meuPerfil() no ClienteController.
    $cliente = $viewVar['cliente'] ?? null;
?>

<div class="container">
    <h3>Meu Perfil</h3>
    <p>Aqui estão as suas informações de cliente.</p>
    <hr>

    <?php if ($cliente): ?>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Nome Completo:</strong><br> <?= htmlspecialchars($cliente->getNome()) ?></p>
                <p><strong>CPF:</strong><br> <?= htmlspecialchars($cliente->getCpf()) ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Data de Nascimento:</strong><br> <?= htmlspecialchars(date('d/m/Y', strtotime($cliente->getDtnasc()))) ?></p>
                <p><strong>Telefone:</strong><br> <?= htmlspecialchars($cliente->getTelefone()) ?></p>
            </div>
        </div>
        <hr>
        <a href="http://<?php echo APP_HOST; ?>/cliente/editarPerfil/" class="btn btn-primary">Editar Meu Perfil</a>
        <!-- Este botão de editar ainda não tem funcionalidade, mas podemos adicioná-la no futuro -->
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Não foi possível carregar as informações do seu perfil.
        </div>
    <?php endif; ?>

</div>
