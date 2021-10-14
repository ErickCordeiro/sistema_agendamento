<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<form action="<?= url("/admin/novo-exame"); ?>" method="post" enctype="multipart/form-data">
    <div class="header-clients flex">
        <div>
            <h1>Cadastro de Exames</h1>
        </div>
        <div>
            <button class="btn btn-green">Salvar</button>
            <a href="<?= url("/admin/exames");?>" class="btn btn-red">Cancelar</a>
        </div>
    </div>
    <hr>

    <div class="ajax_response"><?= flash(); ?></div>
    
    <?= csrf_input();?>
    <div class="row mt-3">
        <div class="col-md-6">
            <label>Descrição</label>
            <input type="text" class="form-control" name="description" placeholder="Descrição do Exame" required>
        </div>
        <div class="col-md-3">
            <label>Médicos</label>
            <select name="doctor" id="doctor" class="form-control" required>
                <?php if($doctors): ?>
                    <?php foreach($doctors as $doctor): ?>
                        <option value="<?= $doctor->id ?>"><?= $doctor->name?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-3">
            <label>Filial</label>
            <select name="branch" id="branch" class="form-control" required>
                <?php if($branchs): ?>
                    <?php foreach($branchs as $branchs): ?>
                        <option value="<?= $branchs->id ?>"><?= $branchs->company ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>
</form>