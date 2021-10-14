<?php $v->layout("_theme"); ?>
<main class="main_content notfound">
    <div class="container">
        <span class="icon-chain-broken icon-notext notfound_icon"></span>
        <h1 class="notfound_title">Whooops, não econtrado!</h1>
        <p>Desculpe, mas a página que você tentou acessar não existe ou foi removida. Não se preocupe, estamos aqui para
            te ajudar!</p>
        <p>Você pode <a href="<?= url("/"); ?>" title=" <?= CONF_SITE_NAME ?> | Home">voltar a página inicial </a> ou
            usar
            a nossa pesquisa para encontrar os produtos que procura.</p>
    </div>
</main>