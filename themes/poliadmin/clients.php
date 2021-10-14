<?php $v->layout("_theme"); ?>

<div class="header-clients flex">
    <div>
        <h1>Clientes Cadastrados</h1>
    </div>
    <div>
        <a href="<?= url("/admin/novo-cliente");?>" class="btn btn-green btn-small radius transition">Adicionar</a>
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
            <?php if($clients) :?>
                <?php foreach($clients as $client):?>
                <tr>
                    <td><?= $client->company ?></td>
                    <td><?= $client->fantasy ?></td>
                    <td class="cnpj"><?= $client->document ?></td>
                    <td><?= $client->mail ?></td>
                    <td class="phone"><?= $client->phone ?></td>
                    <td>
                        <a href="<?= url("/admin/editar-cliente/{$client->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/admin/remover-cliente/{$client->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>
</div>