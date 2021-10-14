<?php $v->layout('_login'); ?>
<aside class="main_content_box">
    <header class="main_content_box_header">
        <h1><img src="<?= theme("/assets/images/logo.png"); ?>" alt="Sistema de Agendamento Online"></h1>
    </header>

    <form class="form_login" action="<?= url("/admin/login") ?>" method="post" enctype="multipart/form-data">
        <div class="ajax_response"><?= flash(); ?></div>
        <br>
        <?= csrf_input(); ?>

        <label class="label_group">
            <div><span class="icon-user"> E-mail</span></div>
            <input type="email" class="underline transition" placeholder="E-mail" name="email" required>
        </label>

        <label class="label_group">
            <div><span class="icon-lock"> Senha</span></div>
            <input type="password" class="underline transition" placeholder="Senha" name="password" required>
        </label>
        <button style="margin-top:20px" class="btn btn-green radius transition"> Acessar o Sistema</button>
    </form>

    <footer class="main_content_box_footer">
        <p>Polimed Saúde</p>
        <p>&copy; Todos os direitos reservados 2020</p>
    </footer>
</aside>
<div class="main_bg">

</div>

