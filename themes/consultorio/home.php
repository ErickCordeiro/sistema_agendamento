<?php $v->layout("_theme"); ?>
<?= $v->start("styles"); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link href="<?= theme("/assets/css/main.css"); ?>">
<script src="<?= theme("/assets/js/main.js"); ?>"></script>
<script src="<?= theme("/assets/js/locales-all.js"); ?>"></script>
<?= $v->end(); ?>
<section class="main_content_card">
    <div class="ajax_response"><?= flash(); ?></div><br>
    <div class="flex">
        <div class="calendario">
            <div id="calendar"></div>
        </div>
    </div>
</section>

<!-- Modal Visualizar -->
<div class="modal j_fullcalendar_visualizar fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Informações do Agendamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mt-3">
                    <label for="branch">Filial</label>
                    <input type="text" readonly placeholder="Filial" id="branch" class="form-control">
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
                    <textarea id="complementary" readonly class="form-control" rows="4"></textarea>
                </div>

                <div class="mt-3">
                    <label for="collaborator"><b>Horário Agendamento</b></label><br>
                    <span id="start" class="text-danger" style="font-size:22px"></span>
                </div>

                <div class="mt-3">
                    <label for="collaborator">Observação</label>
                    <textarea id="observation" readonly class="form-control" rows="10"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <a href class="btn btn-red" id="delete">Excluir Agendamento</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agendar -->
<div class="modal j_fullcalendar_agendar fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="ajax_response"><?= flash(); ?></div>
            <form action="<?= url("/agendar"); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_input(); ?>
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle">Marcar Agendamento</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="branch">Filial</label>
                        <select name="branch" class="form-control" required>
                            <?php if ($branch) : ?>
                                <?php foreach ($branch as $bra) : ?>
                                    <option value="<?= $bra->id ?>"><?= $bra->company ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="collaborator">Colaborador</label>
                        <select name="collaborator" class="form-control" id="collaborators" data-uri="<?= url("/admin/infos-collaborator"); ?>" required>
                            <?php if ($colaborator) : ?>
                                <option value="">Selecione um Colaborador</option>
                                <?php foreach ($colaborator as $col) : ?>
                                    <option value="<?= $col->id ?>"><?= $col->name ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label>Cargo</label>
                        <input type="text" name="office" placeholder="Cargo" class="form-control" required>
                    </div>

                    <div class="mt-3">
                        <label>Função</label>
                        <input type="text" name="function" placeholder="Função" class="form-control" required>
                    </div>

                    <div class="mt-3">
                        <label for="exame">Exame</label>
                        <select name="exams" class="form-control" required>
                            <?php if ($exams) : ?>
                                <?php foreach ($exams as $ex) : ?>
                                    <option value="<?= $ex->id ?>"><?= $ex->description ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="collaborator"><b>Horário Agendamento</b></label><br>
                        <input class="form-control" type="date" name="date_initial">
                        <input class="form-control" type="time" name="time_initial">
                    </div>

                    <div class="mt-3">
                        <label class="label-group" style="width:100%">
                            <div>Exames Complementares</div>
                            <select class="form-control selectpicker" multiple data-live-search="true" name="complementary[]" required>
                                <?php if ($complementary) : ?>
                                    <?php foreach ($complementary as $comp) : ?>
                                        <option value="<?= $comp->id ?>"><?= $comp->description ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </label>
                    </div>

                    <div class="mt-3">
                        <label for="collaborator">Observação</label>
                        <textarea name="observation" id="observation" class="form-control" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-green">Marcar Agendamento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $v->start("scripts"); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var date = new Date();

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            defaultTimedEventDuration: '00:10:00',
            slotMinTime: "08:00:00",
            slotMaxTime: "18:00:00",
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
            events: "<?= url("/lista-eventos"); ?>",
            editable: false,
            eventClick: function(info) {

                if (info.event.extendedProps.status == "block") {
                    swal({
                        title: "Oops....",
                        text: "Este horário esta bloqueado para agendamento!",
                        icon: "error",
                        button: "Ok",
                    });
                } else {
                    $(".j_fullcalendar_visualizar a#delete").attr("href", "<?= url('/remover-agendamento'); ?>" + "/" + info.event.id);
                    $(".j_fullcalendar_visualizar input#branch").val(info.event.extendedProps.branch);
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
            },
            selectable: true,
            select: function(info) {
                $(".j_fullcalendar_agendar").modal("show");
            }
        });

        calendar.render();

    });
</script>
<?= $v->end(); ?>