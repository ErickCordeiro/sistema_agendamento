<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Colaboradores</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-colaborador");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>Função</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($collaborators) :?>
                <?php foreach($collaborators as $co):?>
                <tr>
                    <td><?= $co->clients()->company ?></td>
                    <td><?= $co->name ?></td>
                    <td><?= $co->office ?></td>
                    <td><?= $co->function ?></td>
                    <td><?= $co->mail ?></td>
                    <td class="phone"><?= $co->phone ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-colaborador/{$co->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-colaborador/{$co->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>