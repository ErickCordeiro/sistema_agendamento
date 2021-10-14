<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Exames Complementares Cadastrados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-complementar");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th width="90%">Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($complementary) :?>
                <?php foreach($complementary as $com):?>
                <tr>
                    <td><?= $com->description ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-complementar/{$com->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-complementar/{$com->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>