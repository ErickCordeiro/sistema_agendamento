$(function(){
    $(".cnpj").mask("00.000.000/0000-00");
    $(".cpf").mask("000.000.000-00");
    $(".phone").mask("(00) 0000-0000");
    $(".celphone").mask("(00) 00000-0000");
    $(".uf_code").mask("00000-0000");
    $(".birth").mask("00/00/0000");
    $(".timer").mask("00:00");

    /**
     * CONSULTA CNPJ
     */

    $(".j_consult_document").click(function(){
        var cnpj = $(".cnpj").val();
        var load = $(".ajax_load");
        
        if(cnpj == ""){
            alert("Preencha o campo CNPJ para fazer a consulta!");
        } else {
            cnpj = cnpj.replace(/[^\d]+/g,'');

            $.ajax({
                url: "https://www.receitaws.com.br/v1/cnpj/" + cnpj,
                method: "GET",
                crossDomain: true,
                dataType: "jsonp",
                beforeSend: function (){
                    load.fadeIn(200).css("display", "flex");
                },
                success: function(response){
                    if(response.situacao == "INATIVA"){
                        alert("EMPRESA INATIVA, Empresa impossibilitada de realizar a consulta!");
                        load.fadeOut(200);
                    } else {
                        $("input[name=company]").val(response.nome);
                        $("input[name=fantasy]").val(response.fantasia);
                        $("input[name=phone]").val(response.telefone);
                        $("input[name=mail]").val(response.email);

                        $("input[name=street]").val(response.logradouro);
                        $("input[name=number]").val(response.numero);
                        $("input[name=complement]").val(response.complemento);
                        $("input[name=city]").val(response.municipio);
                        $("input[name=neight]").val(response.bairro);

                        $("input[name=uf]").val(response.uf);
                        $("input[name=uf_code]").val(response.cep);
                
                        load.fadeOut(200);
                    }
                },
                error: function(response){
                    console.log("Erro Warning");
                },
                complete: function(){
                    load.fadeOut(200);
                }
            });
        }
    });

    /**
     * Pesquisa Colaboradores
     */
    $("#clients").on("change", function(){
        var value = $("#clients option:selected").val();
        var cbCollaborators = $("#collaborators");

        cbCollaborators.removeAttr("readonly");
        $.ajax({
            url: $(this).data("uri"),
            data: {code: value},
            method:"POST",
            dataType:"json",
            success: function(response){
                var option = $("#collaborators option");
                option = "<option value=''>Selecione um Colaborador</option>";
                $.each(response, function(i, obj){
                    option += '<option value="'+obj.id+'">'+obj.name+'</option>';
                })
                cbCollaborators.html(option);
            }
        })
    });

     /**
     * Pesquisa Colaboradores
     */
    $("#collaborators").on("change", function(){
        var value = $("#collaborators option:selected").val();
        var office = $("input[name=office]");
        var func = $("input[name=function]");

        office.removeAttr("readonly");
        func.removeAttr("readonly");
        
        $.ajax({
            url: $(this).data("uri"),
            data: {code: value},
            method:"POST",
            dataType:"json",
            success: function(response){
               office.val(response.collaborator.office);
               func.val(response.collaborator.function);
            }
        })

    });

    /*
      * AJAX FORM
      */
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            uploadProgress: function (event, position, total, completed) {
                var loaded = completed;
                var load_title = $(".ajax_load_box_title");
                load_title.text("Enviando (" + loaded + "%)");

                form.find("input[type='file']").val(null);
                if (completed >= 100) {
                    load_title.text("Aguarde, carregando...");
                }
            },
            success: function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    load.fadeOut(200);
                }

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function () {
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            },
            error: function () {
                var message = "<div class='message error icon-warning'>Desculpe mas não foi possível processar a requisição. Favor tente novamente!</div>";
                if (flash.length) {
                    flash.html(message).fadeIn(100).effect("bounce", 300);
                } else {
                    form.prepend("<div class='" + flashClass + "'>" + message + "</div>")
                        .find("." + flashClass).effect("bounce", 300);
                }
            }
        });
    });

    /*
     * REMOVE
     */
    $("[data-remove]").click(function (e) {
        var remove = confirm("ATENÇÃO: Essa ação não pode ser desfeita! Tem certeza que deseja excluir esse lançamento?");

        if (remove === true) {
            $.post($(this).data("remove"), function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            }, "json");
        }
    })
});