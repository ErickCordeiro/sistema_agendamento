<?php $v->layout("_theme"); ?>
<?= $v->start("styles"); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link href="<?= theme("/assets/css/main.css"); ?>">
<script src="<?= theme("/assets/js/main.js"); ?>"></script>
<script src="<?= theme("/assets/js/locales-all.js"); ?>"></script>
<?= $v->end(); ?>


<div class="flex">
    <div class="agenda">
        <h3>Marcar Agendamento</h3>
        <div class="ajax_response"><?= flash(); ?></div>
        <form action="<?= url("/admin/marcar-agendamento"); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_input(); ?>
            <label class="label-group">
                <div>Filial</div>
                <select name="branchs" class="form-control" require>
                    <option>Selecione a Filial</option>
                    <?php if ($branchs) : ?>
                        <?php foreach ($branchs as $bra) : ?>
                            <option value="<?= $bra->id ?>"><?= $bra->fantasy ?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                </select>
            </label>

            <label class="label-group">
                <div>Clientes</div>
                <select name="clients" id="clients" class="form-control" data-uri="<?= url("/admin/list-collaborator"); ?>" required>
                    <option>Selecione o Cliente</option>
                    <?php if ($clients) : ?>
                        <?php foreach ($clients as $cli) : ?>
                            <option value="<?= $cli->id ?>"><?= $cli->fantasy ?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                </select>
            </label>

            <label class="label-group">
                <div>Colaborador</div>
                <select class="form-control" name="colaboration" id="collaborators" data-uri="<?= url("/admin/infos-collaborator"); ?>" required readonly>
                    <option>Selecione Primeiro o Cliente</option>
                </select>
            </label>

            <div class="flex">
                <label class="label-group" style="width:47.8%;">
                    <div>Cargo</div>
                    <input type="text" name="office" class="form-control" placeholder="Cargo" readonly required>
                </label>

                <label class="label-group" style="width:47.8%; margin-left: 5px;">
                    <div>Função</div>
                    <input type="text" name="function" class="form-control" required readonly placeholder="Função">
                </label>
            </div>

            <label class="label-group">
                <div>Exames</div>
                <select name="exams" id="exams" class="form-control" required>
                    <option>Selecione o Exame</option>
                    <?php if ($exams) : ?>
                        <?php foreach ($exams as $ex) : ?>
                            <option value="<?= $ex->id ?>"><?= $ex->description ?></option>
                        <?php endforeach; ?>
                    <?php endif ?>
                </select>
            </label>

            <label class="label-group">
                <div>Exames Complementares</div>
                <select class="form-control selectpicker" multiple data-live-search="true" name="complementary[]" required>
                    <?php if ($complementary) : ?>
                        <?php foreach ($complementary as $comp) : ?>
                            <option value="<?= $comp->id ?>"><?= $comp->description ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </label>

            <div class="flex">
                <label class="label-group" style="width:47.8%;">
                    <div>Data Início</div>
                    <input type="date" name="date_initial" class="form-control" required>
                </label>

                <label class="label-group" style="width:47.8%; margin-left: 5px;">
                    <div>Hora Início</div>
                    <input type="time" name="time_initial" class="form-control" required>
                </label>
            </div>


            <label class="label-group">
                <div>Observação</div>
                <textarea name="observation" class="form-control" style="height:150px; resize: none"></textarea>
            </label>

            <button class="icon-floppy-o btn btn-small btn-green radius">Agendar</button>
        </form>
    </div>
    <div class="calendario">
        <div id="calendar"></div>
    </div>
</div>

<!-- Modal Visualizar -->
<div class="modal j_fullcalendar_visualizar fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Informações do Agendamento</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <input type="hidden" name="id_events" id="id_events">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" data-uri="<?= url("/admin/altera-status") ?>">
                        <option value="pending">Pendente</option>
                        <option value="success">Concluído</option>
                        <option value="occurrence">Não Compareceu</option>
                    </select>
                    <!-- <input type="text" readonly placeholder="Status" id="status" class="form-control"> -->
                </div>

                <div class="mt-3">
                    <label for="branch">Filial</label>
                    <input type="text" readonly placeholder="Filial" id="branch" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="client">Cliente</label>
                    <input type="text" readonly placeholder="Cliente" id="client" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="collaborator">Colaborador</label>
                    <input type="text" readonly placeholder="Colaborador" id="collaborator" class="form-control">
                </div>

                <div class="mt-3">
                    <label>Cargo</label>
                    <input type="text" readonly placeholder="Cargo" id="office" class="form-control">
                </div>

                <div class="mt-3">
                    <label>Função</label>
                    <input type="text" readonly placeholder="Função" id="function" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="collaborator">Médico</label>
                    <input type="text" readonly placeholder="Médico" id="doctor" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="collaborator">Exame</label>
                    <input type="text" readonly placeholder="Exame" id="exam" class="form-control">
                </div>

                <div class="mt-3">
                    <label for="complementary">Complementar</label>
                    <textarea id="complementary" readonly class="form-control" style="height: 100px"></textarea>
                </div>

                <div class="mt-3">
                    <label for="collaborator"><b>Horário Agendamento</b></label><br>
                    <span id="start" class="text-danger" style="font-size:22px"></span>
                </div>

                <div class="mt-3">
                    <label for="collaborator">Observação</label>
                    <textarea id="observation" class="form-control" readonly style="height: 200px; resize: none;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a href class="btn btn-red" id="delete">Excluir Agendamento</a>
            </div>
        </div>
    </div>
</div>

<?= $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Calendário -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            validRange: {
                start: Date.now()
            },
            weekends: false,
            locale: "pt-br",
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            buttonText: {
                today: "Hoje",
                month: "Mês",
                week: "Semana",
                day: "Dia"
            },
            businessHours: {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [1, 2, 3, 4, 5], // Monday - Thursday
                startTime: '08:00', // a start time (10am in this example)
                endTime: '18:00', // an end time (6pm in this example)
            },
            events: "<?= url("/admin/list-events"); ?>",
            editable: false,
            droppable: false,
            eventLimit: true,
            extraParams: function() {
                return {
                    cachebuster: new Date().valueOf()
                };
            },
            eventClick: function(info) {

                if (info.event.extendedProps.status == "block") {
                    swal({
                        title: "Oops....",
                        text: "Este horário esta bloqueado para agendamento!",
                        icon: "error",
                        button: "Ok",
                    });
                } else {

                    $(".j_fullcalendar_visualizar a#delete").attr("href", "<?= url('/admin/remover-agendamento'); ?>" + "/" + info.event.id);

                    $(".j_fullcalendar_visualizar input#branch").val(info.event.extendedProps.branch);
                    $(".j_fullcalendar_visualizar input#id_events").val(info.event.id);

                    if (info.event.extendedProps.status == "pending") {
                        $(".j_fullcalendar_visualizar select#status").val("pending");
                    } else if (info.event.extendedProps.status == "success") {
                        $(".j_fullcalendar_visualizar select#status").val("success");
                    } else {
                        $(".j_fullcalendar_visualizar select#status").val("occurrence");
                    }

                    $(".j_fullcalendar_visualizar input#client").val(info.event.extendedProps.client);
                    $(".j_fullcalendar_visualizar input#collaborator").val(info.event.extendedProps.collaborator);
                    $(".j_fullcalendar_visualizar input#office").val(info.event.extendedProps.office);
                    $(".j_fullcalendar_visualizar input#function").val(info.event.extendedProps.function);
                    $(".j_fullcalendar_visualizar input#doctor").val(info.event.extendedProps.doctor);
                    $(".j_fullcalendar_visualizar input#exam").val(info.event.extendedProps.exam);
                    $(".j_fullcalendar_visualizar textarea#complementary").val(info.event.extendedProps.complementary);
                    $(".j_fullcalendar_visualizar span#start").text(info.event.start.toLocaleString());
                    $(".j_fullcalendar_visualizar textarea#observation").val(info.event.extendedProps.observation);
                    $(".j_fullcalendar_visualizar").modal("show");
                }
            }
        });

        calendar.render();

    });
</script>

<!-- Alteração de Status -->
<script>
    $(".j_fullcalendar_visualizar select#status").change(function() {
        var cbValue = $(this).val();
        var code = $("#id_events").val();

        $.ajax({
            url: $(this).data("uri"),
            method: "POST",
            data: {
                status: cbValue,
                code: code
            },
            dataType: "json",
            success: function(response) {
                window.location = response.redirect;
            }
        });
    });
</script>
<?= $v->end(); ?>