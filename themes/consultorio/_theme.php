<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?= $head; ?>

    <?= $v->section("styles"); ?>

    <link rel="base" href="<?= url(); ?>">

    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.semanticui.min.css">

    <link href="<?= theme('/assets/style.css') ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?= theme('/assets/images/favicon.png'); ?>">
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, Carregando...</p>
    </div>
</div>

<nav class="menu-top">
    <ul class="menu-top-itens flex">
        <div class="left flex">
            <li><a href="#" class="img"><img src="<?= theme("/assets/images/logo.png") ?>" alt=""></a></li>
            <li><a href="<?= url("/painel") ?>" <?= ($menu == "home")?"class='active'":""; ?>>Agendamentos</a></li>
            <li><a href="<?= url("/funcionarios")?>" <?= ($menu == "func")?"class='active'":""; ?>>Funcionários</a></li>
        </div>

        <div class="right flex">
            <li><a href="<?= url("/sair") ?>" class="icon-sign-out">Sair </a></li>
        </div>
    </ul>
</nav>

<main class="main_content">
    <?= $v->section("content"); ?>
</main>

<script src="<?= theme("/assets/scripts.js"); ?>"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.semanticui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                },
                "select": {
                    "rows": {
                        "_": "Selecionado %d linhas",
                        "0": "Nenhuma linha selecionada",
                        "1": "Selecionado 1 linha"
                    }
                }
            }
        });
    } );
</script>
<?= $v->section("scripts"); ?>
</body>
</html>