<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="pt-br, en">

    <?= $head; ?>

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap"
          rel="stylesheet">
    <link href="<?= theme('/assets/style.css', CONF_VIEW_ADMIN) ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?= theme('/assets/images/favicon.png', CONF_VIEW_ADMIN); ?>">
</head>
<body class="login_body">

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, Carregando...</p>
    </div>
</div>

<main class="main_content_login">
    <?= $v->section("content"); ?>
</main>

<script src="<?= theme("/assets/scripts.js"); ?>"></script>
<?= $v->section("scripts"); ?>
</body>
</html>