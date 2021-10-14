<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<form action="<?= url("/admin/novo-complementar"); ?>" method="post" enctype="multipart/form-data">
    <div class="header-clients flex">
        <div>
            <h1>Cadastro de Exames Complementares</h1>
        </div>
        <div>
            <button class="btn btn-green">Salvar</button>
            <a href="<?= url("/admin/complementares");?>" class="btn btn-red">Cancelar</a>
        </div>
    </div>
    <hr>

    <div class="ajax_response"><?= flash(); ?></div>
    
    <?= csrf_input();?>
    <div class="row mt-3">
        <div class="col-md-12">
            <label>Descrição</label>
            <input type="text" class="form-control" name="description" placeholder="Descrição do Exame Complementar" required>
        </div>
    </div>
</form>