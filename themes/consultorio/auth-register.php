<?php $v->layout('_login'); ?>
<aside class="main_content_box">
    <header class="main_content_box_header">
        <h1><img src="<?= theme("/assets/images/logo.png"); ?>" alt="Sistema de Agendamento Online"></h1>
    </header>

    <form class="form_login" action="<?= url("/registrar") ?>" method="post" enctype="multipart/form-data">
        <div class="ajax_response"><?= flash(); ?></div>
        <br>
        <?= csrf_input(); ?>

        <label class="label_group">
            <div><span class="icon-user-plus"> E-mail</span></div>
            <input type="text" class="underline transition" placeholder="Nome" name="fullname" required>
        </label>

        <label class="label_group">
            <div><span class="icon-envelope"> E-mail</span></div>
            <input type="email" class="underline transition" placeholder="E-mail" name="email" required>
        </label>

        <label class="label_group">
            <div><span class="icon-lock"> Senha</span></div>
            <input type="password" class="underline transition" placeholder="Senha" name="password" required>
        </label>

        <label class="label_group">
            <div><span class="icon-lock"> Confirmar Senha</span></div>
            <input type="password" class="underline transition" placeholder="Confirma Senha" name="password_re" required>
        </label>

        <button class="btn btn-green-out radius transition"> Cadastrar Usuário</button>
    </form>

    <footer class="main_content_box_footer">
        <p>Polimed Saúde</p>
        <p>&copy; Todos os direitos reservados 2020</p>
    </footer>
</aside>

<div class="main_bg"></div>
