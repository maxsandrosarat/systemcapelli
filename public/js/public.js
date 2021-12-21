function id(campo){
    return document.getElementById(campo);
}

$(document).ready(function(){
    //OPÇÕES DE LOGIN
    $('#principal').children('div').hide();
    $('#tipoLogin').on('change', function(){
        var selectValor = '#'+$(this).val();
        $('#principal').children('div').hide();
        $('#principal').children(selectValor).show();
    });
});

//view(auth/register)
function validarSenhaForca(){
    var senha = id('senhaForca').value;
    var forca = 0;
    if((senha.length >= 4) && (senha.length <= 8)){
        forca += 10;
    }else if(senha.length > 8){
        forca += 25;
    }
    if((senha.length >= 5) && (senha.match(/[a-z]+/))){
        forca += 10;
    }
    if((senha.length >= 6) && (senha.match(/[A-Z]+/))){
        forca += 20;
    }
    if((senha.length >= 7) && (senha.match(/[@#$%&;*]/))){
        forca += 25;
    }
    if(senha.match(/([1-9]+)\1{1,}/)){
        forca += -25;
    }
    mostrarForca(forca);
}

function mostrarForca(forca){

    if(forca < 30 ){
        id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div>';
    }else if((forca >= 30) && (forca < 50)){
        id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div></div>';
    }else if((forca >= 50) && (forca < 70)){
        id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div></div>';
    }else if((forca >= 70) && (forca < 100)){
        id('erroSenhaForca').innerHTML = '<div class="progress"><div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>';
    }
}

//view(auth/login)
function mostrarSenha(password, button, icon){
    var tipo = id(password);
    if(tipo.type=="password"){
        tipo.type = "text";
        id(icon).innerHTML = "visibility_off";
        id(button).className = "badge bg-warning rounded-pill";
        id(button).title = "Ocultar Senha";
    } else {
        tipo.type = "password";
        id(icon).innerHTML = "visibility";
        id(button).className = "badge bg-success rounded-pill";
        id(button).title = "Mostrar Senha";
    }
}


//view(vendas/venda_servicos)
function valorServico(){
    var d = id('servico');
    var displaytext = d.options[d.selectedIndex].title;
    id('valor').value = displaytext;
}

function getValor(campo){
    var valor = id(campo).value.replace(',','.');
    var length = valor.length;
    if(length>0){
        id(campo).value = parseFloat(valor);
    } else {
        $('#'+ campo +'').val("");
    }
}

function formataNumeroTelefone(id) {
    var numero = $('#telefone'+ id +'').val();
    var length = numero.length;
    var telefoneFormatado;
    if(length>0){
        if (length == 10) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
        } else if (length == 11) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
        } else {
            $('#telefone'+ id +'').val("");
            alert("Número inválido, digite apenas os números com DDD.");
        }
    } else{
        $('#telefone'+ id +'').val("");
    }
    $('#telefone'+ id +'').val(telefoneFormatado);
}

function formataNumeroWhatsapp(id) {
    var numero = $('#whatsapp'+ id +'').val();
    var length = numero.length;
    var telefoneFormatado;
    if(length>0){
        if (length == 10) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 6) + '-' + numero.substring(6, 10);
        } else if (length == 11) {
        telefoneFormatado = '(' + numero.substring(0, 2) + ') ' + numero.substring(2, 7) + '-' + numero.substring(7, 11);
        } else {
            $('#whatsapp'+ id +'').val("");
            alert("Número inválido, digite apenas os números com DDD.");
        }
    } else{
        $('#whatsapp'+ id +'').val("");
    }
    $('#whatsapp'+ id +'').val(telefoneFormatado);
}

$(document).on('change', '#servico', function(){
    var id = this.value;
    if(id=='' || id==null){
        $('#func>option').remove();
        s = "";
        s = '<option value="">Selecione o serviço</option>'
        $('#func').append(s);
    } else {
        $.post("/funcs",{id: id,_token:$('input[name="_token"]').attr("value")},function (data) {
            $('#func>option').remove();
            s = "";
            s = '<option value="">Selecione</option>'
            $('#func').append(s);
            for(i=0; i<data.length; i++){
                s = "";
                s = '<option value="' + data[i].id + '">' + data[i].name + '</option>'
                $('#func').append(s);
            }
        });
    }
});

$(document).on('change', '#func', function(){
    var id = this.value;
    var dia = $('#data').val();
    var hora = $('#hora').val();
    $.post("/agends/func",{id: id, dia: dia, hora: hora, _token:$('input[name="_token"]').attr("value")},
        function (data) {
            $('#select-func>span').remove();
            $('#func').removeClass("is-invalid");
            if(data>0){
                s = "";
                s = '<span class="invalid-feedback" role="alert">'+
                            '<strong> Funcionário já possui '+ data +' Agendamento(s)</strong>'+
                    '</span>'
                $('#select-func').append(s);
                $('#func').addClass("is-invalid");
            }
        }
    );
});

$(document).on('change', '#cliente', function(){
    var id = this.value;
    var dia = $('#data').val();
    var hora = $('#hora').val();
    $.post("/agends/cliente",{id: id, dia: dia, hora: hora, _token:$('input[name="_token"]').attr("value")},
        function (data) {
            $('#input-cliente>div').remove();
            $('#cliente').removeClass("is-invalid");
            if(data>0){
                s = "";
                s = '<div class="invalid-tooltip">Cliente já possui '+ data +' Agendamento(s)</div>'
                $('#input-cliente').append(s);
                $('#cliente').addClass("is-invalid");
            }
        }
    );
});