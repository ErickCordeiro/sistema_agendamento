<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Exames Cadastrados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-exame");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>Descrição</th>
                <th>Médico</th>
                <th>Filial</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($exams) :?>
                <?php foreach($exams as $e):?>
                <tr>
                    <td><?= $e->description ?></td>
                    <td><?= $e->doctor()->name ?></td>
                    <td><?= $e->branch()->company ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-exame/{$e->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-exame/{$e->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>