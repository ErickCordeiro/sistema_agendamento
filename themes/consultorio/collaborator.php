<?php $v->layout("_theme"); ?>

<section class="main_content_card">
    <div class="flex">
        <div class="text">
            <h1>Cadastro de Funcionários</h1>
        </div>
        <div>
            <a href="<?= url("/novo-funcionario")?>" class="btn btn-green btn-sm radius transition">Adicionar</a>
        </div>
    </div>
    <hr>
    <div class="ajax-response" style="margin-bottom: 20px"><?= flash();?></div>
    <table id="example" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>Nome Completo</th>
                <th>Função</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if($collaborator) :?>
                <?php foreach($collaborator as $col):?>
                <tr>
                    <td><?= $col->name ?></td>
                    <td><?= $col->office ?></td>
                    <td class="cpf"><?= $col->document ?></td>
                    <td><?= $col->mail ?></td>
                    <td class="phone"><?= $col->phone ?></td>
                    <td>
                        <a href="<?= url("/editar-funcionario/{$col->id}")?>" class="btn-icon icon-pencil icon-notext btn-blue radius transition"></a>
                        <span  data-remove="<?= url("/remover-funcionario/{$col->id}") ?>" class="btn-icon icon-trash icon-notext btn-red radius transition"></span>
                    </td>
                </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </tbody>
    </table>

</section>