<?php $v->layout("_theme"); ?>
<?php $v->start("styles"); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $v->end(); ?>

<section class="main_content_card">
    <form action="<?= url("/admin/editar-colaborador/{$collaborator->id}") ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="update" value="true">
        <?= csrf_input();?>

        <div class="header-clients flex">
            <div>
                <h1>Colaboradores</h1>
            </div>
            <div>
                <button class="btn btn-green">Salvar</button>
                <a href="<?= url("/admin/colaboradores") ?>" class="btn btn-red"> Cancelar</a>
            </div>
        </div>
        <hr>

        <div class="ajax_response my-4"><?= flash(); ?></div>
        <?= csrf_input(); ?>
        <input type="hidden" value="true" name="update">

        <div class="row">
            <div class="col-md-12">
                <label>Cliente</label>
                <select name="clients" class="form-control" required>
                    <?php if ($clients) : ?>
                        <option value="">Selecione um cliente</option>
                        <?php foreach ($clients as $cli) : ?>
                            <option <?= ($cli->id == $collaborator->clients_id) ? "selected" : ""; ?> value="<?= $cli->id ?>"><?= $cli->company ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-12">
                <label>Nome Completo</label>
                <input type="text" name="name" placeholder="Nome Completo" class="form-control" required value="<?= $collaborator->name ?>">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-3">
                <label>CPF</label>
                <input type="text" name="document" placeholder="CPF" class="form-control cpf" value="<?= $collaborator->document ?>">
            </div>

            <div class="col-md-3">
                <label>Data Nasc.</label>
                <input type="text" name="birth_date" placeholder="XX/XX/XXXX" class="form-control birth" value="<?= date_fmt($collaborator->birth_date, "d/m/Y") ?>">
            </div>

            <div class="col-md-3">
                <label>Cargo</label>
                <input type="text" name="office" placeholder="Cargo" class="form-control" required value="<?= $collaborator->office ?>">
            </div>

            <div class="col-md-3">
                <label>Função</label>
                <input type="text" name="function" placeholder="Função" class="form-control" required value="<?= $collaborator->function ?>">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-3">
                <label>E-mail</label>
                <input type="email" name="mail" placeholder="E-mail" class="form-control" value="<?= $collaborator->mail ?>">
            </div>

            <div class="col-md-3">
                <label>Telefone</label>
                <input type="text" name="phone" placeholder="(XX) XXXX-XXXX" class="form-control phone" value="<?= $collaborator->phone ?>">
            </div>

            <div class="col-md-3">
                <label>Celular</label>
                <input type="text" name="celphone" placeholder="(XX) XXXXX-XXXX" class="form-control celphone" value="<?= $collaborator->celphone ?>">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-12">
                <label>Endereço</label>
                <input type="text" name="address" placeholder="Endereço, Número" class="form-control" value="<?= $collaborator->address ?>">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-12">
                <label>Complemento</label>
                <input type="text" name="complement" placeholder="Complemento" class="form-control" value="<?= $collaborator->complement ?>">
            </div>
        </div>

        <div class="row my-4">
            <div class="col-md-3">
                <label>Bairro</label>
                <input type="text" name="neight" placeholder="Bairro" class="form-control" value="<?= $collaborator->neight ?>">
            </div>

            <div class="col-md-3">
                <label>Cidade</label>
                <input type="text" name="city" placeholder="Cidade" class="form-control" value="<?= $collaborator->city ?>">
            </div>

            <div class="col-md-3">
                <label>Estado</label>
                <input type="text" name="uf" placeholder="Estado" class="form-control" value="<?= $collaborator->uf ?>">
            </div>

            <div class="col-md-3">
                <label>CEP</label>
                <input type="text" name="uf_code" placeholder="XXXXX-XXX" class="form-control uf_code" value="<?= $collaborator->uf_code ?>">
            </div>
        </div>
    </form>
</section>