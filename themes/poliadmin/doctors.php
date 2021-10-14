<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Médicos Cadastrados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-medico");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th width="60%">Nome</th>
                <th width="20%">CRM</th>
                <th width="20%">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($doctors) :?>
                <?php foreach($doctors as $doctor):?>
                <tr>
                    <td><?= $doctor->name ?></td>
                    <td><?= $doctor->crm ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-medico/{$doctor->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-medico/{$doctor->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>