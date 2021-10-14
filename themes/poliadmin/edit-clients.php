<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<form action="<?= url("/admin/editar-cliente/{$clients->id}"); ?>" method="post" enctype="multipart/form-data">
    <div class="header-clients flex">
        <div>
            <h1>Editar Cliente</h1>
        </div>
        <div>
            <button class="btn btn-green">Salvar</button>
            <a href="<?= url("/admin/clientes");?>" class="btn btn-red">Cancelar</a>
        </div>
    </div>
    <hr>

    <div class="ajax_response"><?= flash(); ?></div>
    <input type="hidden" value="true" name="update">
    <?= csrf_input();?>

    <div class="row flex align-items-center">
        <div class="col-md-3">
            <label>CNPJ</label>
            <input type="text" class="form-control cnpj" name="document" placeholder="CNPJ" required value="<?= $clients->document ?>">
        </div>
        <div class="col-md-3">
            <span class="btn btn-primary j_consult_document">Consulta CNPJ</span>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label>Razão Social</label>
            <input type="text" class="form-control" name="company" placeholder="Razão Social" required value="<?= $clients->company ?>">
        </div>
        <div class="col-md-6">
            <label>Nome Fantasia</label>
            <input type="text" class="form-control" name="fantasy" placeholder="Nome Fantasia" required value="<?= $clients->fantasy ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label>Inscrição Estadual</label>
            <input type="text" class="form-control" name="ie" placeholder="Inscrição Estadual" required value="<?= $clients->ie ?>">
        </div>

        <div class="col-md-3">
            <label>Telefone</label>
            <input type="text" class="form-control phone" name="phone" placeholder="Telefone" required value="<?= $clients->phone ?>">
        </div>

        <div class="col-md-3">
            <label>Celular</label>
            <input type="text" class="form-control celphone" name="celphone" placeholder="Celular" value="<?= $clients->celphone ?>">
        </div>

        <div class="col-md-3">
            <label>E-mail</label>
            <input type="email" class="form-control" name="mail" placeholder="E-mail" required value="<?= $clients->mail ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-10">
            <label>Endereço</label>
            <input type="text" class="form-control" name="street" placeholder="Endereço" required value="<?= $clients->street ?>">
        </div>

        <div class="col-md-2">
            <label>Número</label>
            <input type="text" class="form-control" name="number" placeholder="Nº" required value="<?= $clients->number ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <label>Complemento</label>
            <input type="text" class="form-control" name="complement" placeholder="Complemento" value="<?= $clients->complement ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <label>Bairro</label>
            <input type="text" class="form-control" name="neight" placeholder="Bairro" value="<?= $clients->neight ?>">
        </div>

        <div class="col-md-3">
            <label>Cidade</label>
            <input type="text" class="form-control" name="city" placeholder="Cidade" value="<?= $clients->city ?>">
        </div>

        <div class="col-md-3">
            <label>Estado</label>
            <input type="text" class="form-control" name="uf" placeholder="Estado" value="<?= $clients->uf ?>">
        </div>

        <div class="col-md-3">
            <label>CEP</label>
            <input type="text" class="form-control cep" name="uf_code" placeholder="CEP" value="<?= $clients->uf_code ?>">
        </div>
    </div>
</form>