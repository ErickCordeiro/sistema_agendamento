<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?= $head; ?>
    <?= $v->section("styles"); ?>
    <link rel="base" href="<?= url(); ?>">
    <link href="<?= theme('/assets/style.css') ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?= theme('/assets/images/favicon.png'); ?>">
</head>
<body>
    <form class="form-reset" action="<?= url("/resetar"); ?>" method="post" enctype="multipart/form-data">
        <div class="ajax_response"><?= flash(); ?></div>
        <input type="hidden" value="<?= $code ?>" name="code">
        <?= csrf_input();?>
        <br>

        <header class="main_content_box_header">
            <h1><img src="<?= theme("/assets/images/logo.png"); ?>" alt="Sistema de Agendamento Online"></h1>
        </header>
        <h1>Recuperar Senha</h1>
        <br>
        <label class="label_group">
            <div><span class="icon-lock"> Senha</span></div>
            <input type="password" class="underline transition" placeholder="Senha" name="password" required maxlength="10">
        </label>

        <label class="label_group">
            <div><span class="icon-lock"> Confirmar Senha</span></div>
            <input type="password" class="underline transition" placeholder="Senha" name="password_re" required>
        </label>        

        <button style="margin-top:20px" class="btn btn-green radius transition"> Alterar Senha</button>
    </form>

    <footer class="main_content_box_footer">
        <p>Polimed Saúde</p>
        <p>&copy; Todos os direitos reservados 2020</p>
    </footer>

    <script src="<?= theme("/assets/scripts.js"); ?>"></script>
</body>
</html>