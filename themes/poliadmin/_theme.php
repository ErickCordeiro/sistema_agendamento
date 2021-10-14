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
    <link rel="shortcut icon" href="<?= theme('/assets/images/favicon.png'); ?>">

    <!-- DATATABLE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.semanticui.min.css">

    <link href="<?= theme('/assets/style.css', CONF_VIEW_ADMIN) ?>" rel="stylesheet">
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, Carregando...</p>
    </div>
</div>

<aside class="menu-left">
    <nav>
        <header>
            <div class="icon-img">
                <img src="<?= theme("/assets/images/images.png", CONF_VIEW_ADMIN)?>" alt="Usuário">
            </div>
            <div class="icon-infos">
                <h2>Bem-vindo,</h2>
                <h3><?= $user ?></h3>
            </div>
        </header>

        <ul>
            <li><a href="<?= url("/admin/dashboard")?>" <?= ($menu == "dash")? "class='active'" : ''; ?>>Agendamentos</a></li>
            <li><a href="<?= url("/admin/horarios-bloqueados")?>" <?= ($menu == "block")? "class='active'" : ''; ?>>Horários Bloqueados</a></li>
            <li><a href="<?= url("/admin/clientes")?>" <?= ($menu == "clients")? "class='active'" : ''; ?>>Clientes</a></li>
            <li><a href="<?= url("/admin/colaboradores")?>" <?= ($menu == "collaborator")? "class='active'" : ''; ?>>Colaboradores</a></li>
            <li><a href="<?= url("/admin/medicos")?>" <?= ($menu == "medicos")? "class='active'" : ''; ?>>Médicos</a></li>
            <li><a href="<?= url("/admin/exames")?>" <?= ($menu == "exams")? "class='active'" : ''; ?> >Exames</a></li>
            <li><a href="<?= url("/admin/complementares")?>" <?= ($menu == "comp")? "class='active'" : ''; ?>>Complementares</a></li>
            <li><a href="<?= url("/admin/filiais")?>" <?= ($menu == "branch")? "class='active'" : ''; ?>>Filiais</a></li>
            <li><a href="<?= url("/admin/usuarios")?>" <?= ($menu == "users")? "class='active'" : ''; ?>>Usuários</a></li>
            <li><a href="<?= url("/admin/sair")?>">Sair do Sistema</a></li>
        </ul>
    </nav>
</aside>


<main class="main_content">
    <?= $v->section("content"); ?>
</main>

<script src="<?= theme("/assets/scripts.js", CONF_VIEW_ADMIN); ?>"></script>
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