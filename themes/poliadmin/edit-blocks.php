<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<form action="<?= url("/admin/editar-bloqueio/{$block->id}"); ?>" method="post" enctype="multipart/form-data">
    <div class="header-clients flex">
        <div>
            <h1>Cadastro de Bloqueio</h1>
        </div>
        <div>
            <button class="btn btn-green">Salvar</button>
            <a href="<?= url("/admin/horarios-bloqueados"); ?>" class="btn btn-red">Cancelar</a>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="ajax_response col-md-9"><?= flash(); ?></div>
    </div>

    <input type="hidden" value="true" name="update">
    <?= csrf_input(); ?>
    <div class="row mt-3">
        <div class="col-md-3">
            <label>Data</label>
            <input type="text" class="form-control birth" name="date" required placeholder="xx/xx/xxxx" value="<?= date_fmt($block->start, "d/m/Y") ?>">
        </div>

        <div class="col-md-3">
            <label>H. Inicial</label>
            <input type="text" class="form-control timer" name="timer_initial" placeholder="00:00" required value="<?= date_fmt($block->start, "H:i"); ?>">
        </div>

        <div class="col-md-3">
            <label>H. Final</label>
            <input type="text" class="form-control timer" name="timer_final" placeholder="00:00" required value="<?= date_fmt($block->end, "H:i"); ?>">
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-9">
            <label>Motivo</label>
            <textarea style="resize: none;" name="observation" class="form-control" rows="10" required><?= $block->observation ?></textarea>
        </div>
    </div>
</form>