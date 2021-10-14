<?php

if (strpos(url(), "localhost")) {
    /**
     * CSS
     */
    $minCSS = new MatthiasMullie\Minify\CSS();
    $minCSS->add(__DIR__ . "/../../../shared/styles/icons.css"); //Acrescentando as Fontes no arquivo minificado
    $minCSS->add(__DIR__ . "/../../../shared/styles/boot.css"); // Acrescentando o Boot no arquivo minificado

    //theme CSS
    $cssDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css");
    foreach ($cssDir as $css) {
        //Vai pegar todos os arquivos que estão dentro da pasta CSS e colocando dentro da Variável.
        $cssFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/css/{$css}";

        //Verificando se é um arquivo com a extensão CSS.
        if (is_file($cssFile) && pathinfo($cssFile)['extension'] == "css") {
            $minCSS->add($cssFile); //Acrescentando o CSS do theme.
        }
    }

    //Minify CSS
    //Criando o arquivo minificado e jogando ele dentro da pasta desejada.
    $minCSS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/style.css");


    // A documentação do Minify do CSS também serve para o JS.

    /**
     * JS
     */
    $minJS = new MatthiasMullie\Minify\JS();
    $minJS->add(__DIR__ . "/../../../shared/scripts/jquery.js");
    $minJS->add(__DIR__ . "/../../../shared/scripts/jquery.form.js");
    $minJS->add(__DIR__ . "/../../../shared/scripts/jquery.mask.js");

    //theme JS
    $jsDir = scandir(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js");
    foreach ($jsDir as $js) {
        $jsFile = __DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/js/{$js}";
        if (is_file($jsFile) && pathinfo($jsFile)['extension'] == "js") {
            $minJS->add($jsFile);
        }
    }

    //Minify JS
    $minJS->minify(__DIR__ . "/../../../themes/" . CONF_VIEW_THEME . "/assets/scripts.js");
}
