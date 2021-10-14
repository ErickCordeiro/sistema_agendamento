<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<form action="<?= url("/admin/novo-medico"); ?>" method="post" enctype="multipart/form-data">
    <div class="header-clients flex">
        <div>
            <h1>Cadastro de Médicos</h1>
        </div>
        <div>
            <button class="btn btn-green">Salvar</button>
            <a href="<?= url("/admin/medicos");?>" class="btn btn-red">Cancelar</a>
        </div>
    </div>
    <hr>

    <div class="ajax_response"><?= flash(); ?></div>
    
    <?= csrf_input();?>
    <div class="row mt-3">
        <div class="col-md-6">
            <label>Nome Completo</label>
            <input type="text" class="form-control" name="name" placeholder="Nome Completo" required>
        </div>
        <div class="col-md-3">
            <label>CRM</label>
            <input type="text" class="form-control" name="crm" placeholder="CRM" required>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-10">
            <label>Endereço</label>
            <input type="text" class="form-control" name="street" placeholder="Endereço">
        </div>

        <div class="col-md-2">
            <label>Número</label>
            <input type="text" class="form-control" name="number" placeholder="Nº">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label>Bairro</label>
            <input type="text" class="form-control" name="neight" placeholder="Bairro">
        </div>

        <div class="col-md-3">
            <label>Cidade</label>
            <input type="text" class="form-control" name="city" placeholder="Cidade">
        </div>

        <div class="col-md-3">
            <label>Estado</label>
            <input type="text" class="form-control" name="uf" placeholder="Estado">
        </div>

        <div class="col-md-3">
            <label>CEP</label>
            <input type="text" class="form-control cep" name="uf_code" placeholder="CEP">
        </div>
    </div>
</form>