<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Filiais Cadastrados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/nova-filial");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
    </div>
</div>
<hr>
<div class="ajax_response mt-3"><?= flash(); ?></div>
<div class="main">
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>Razão Social</th>
                <th>Nome Fantasita</th>
                <th>CNPJ</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($branchs) :?>
                <?php foreach($branchs as $branch):?>
                <tr>
                    <td><?= $branch->company ?></td>
                    <td><?= $branch->fantasy ?></td>
                    <td class="cnpj"><?= $branch->document ?></td>
                    <td><?= $branch->mail ?></td>
                    <td class="phone"><?= $branch->phone ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-filial/{$branch->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-filial/{$branch->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>