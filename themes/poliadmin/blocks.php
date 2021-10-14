<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Horários Bloqueados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-bloqueio");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th width="10%">Data</th>
                <th width="10%">H. Início</th>
                <th width="10%">H. Final</th>
                <th width="60%">Motivo</th>
                <th width="10%">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($blocks) :?>
                <?php foreach($blocks as $block):?>
                <tr>
                    <td><?= date_fmt($block->start, "d/m/Y") ?></td>
                    <td><?= date_fmt($block->start, "H:i") ?></td>
                    <td><?= date_fmt($block->end, "H:i") ?></td>
                    <td><?= $block->observation ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-bloqueio/{$block->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-bloqueio/{$block->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>