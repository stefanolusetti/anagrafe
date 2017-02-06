$(document).ready(function() {
  window._pivaTimeout = false;

  $.validator.addMethod("data", function(value, element) {
      return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
    },
    "Inserire una data nel formato gg/mm/aaaa"
  );

  $.validator.addMethod('_titolare_rappresentanza', function( v, e){
      if ('Altro' == jQuery("#titolare_rappresentanza").val()){
        if ('' == jQuery("#titolare_rappresentanza_altro").val()) {
          return false;
        }
      }
      return true;
    },
    "Indicare il tipo di rappresentanza."
  );

  $.validator.addMethod('_forma_giuridica', function( v, e){
      if (0 == jQuery("#forma_giuridica_id").val()){
        if ('' == jQuery("#impresa_forma_giuridica_altro").val()) {
          return false;
        }
      }
      return true;
    },
    "Indicare la forma giuridica."
  );

  $.validator.addMethod('_interessi', function(v,e){
      if (
        !jQuery("#interesse_lavori").is(':checked')
        && !jQuery("#interesse_servizi").is(':checked')
        && !jQuery("#interesse_forniture").is(':checked')
        && !jQuery("#interesse_interventi").is(':checked')
      ) {
        return false;
      }
      return true;
    },
    "Selezionare almeno un valore."
  );

  $.validator.addMethod('_thefields', function(v,e){
      if (0 == jQuery(".company_fields:checked").length && jQuery("#impresa_settore_nessuno:checked").length == 0) {
        return false;
      }
      return true;
    },
    "Selezionare almeno un valore."
  );

  $.validator.addMethod('_white_list', function(v,e){
    if ( jQuery('#stmt_wl_si').is(':checked') ) {
      if ( '' == jQuery('#white_list_prefettura').val() ||  '' == jQuery('#white_list_data').val() ) {
        return false;
      }
    }
    return true;
  }, 'Inserire prefettura e data di iscrizione.');

  $.validator.addMethod("anno", function(value, element) {
      return value.match(/^\d\d\d\d$/);
    },
    "Inserire un anno nel formato aaaa"
  );

  $.validator.addMethod(
    "stmt__eligible",
    function(val, el){
      if (jQuery("#interesse_interventi").is(':checked') && false == jQuery("#interesse_interventi_checkbox").is(':checked')){
        return false;
      }
      return true;
    },
    "Campo obbligatorio"
  );
  $.validator.addMethod('_company_type_more', function(val, el){
    if ( '0' == jQuery('#company_shape').val() && '' == jQuery('#company_type_more').val()) {
      return false;
    }
    return true;
  }, 'Compilare il campo <em>Altro</em>');

  $.validator.addMethod('_company_role', function(val, el){
    if ( 'Altro' == jQuery('#company_role').val() && '' == jQuery('#ruolo_richiedente').val() ) {
      return false;
    }
    return true;
  }, 'Compilare il campo <em>Altro</em>');

  $.validator.addMethod("controlla_contratto", function(value, element) {
      return !($('select[name=tipo_contratto]').val() == "Altri settori" && $('input[name=tipo_contratto_altri]').val() == "");
    },
    "Scrivere il settore di riferimento"
  );

  $.validator.addMethod("controlla_tipo_impresa", function(value, element) {
      return !($('select[name=tipo_impresa]').val() == "Altro" && $('input[name=tipo_impresa_altri]').val() == "");
    },
    "Scrivere il tipo di società"
  );

  $.validator.addMethod("ateco", function(value, element) {
      return check_ateco();
    },
    "Scegliere almeno un codice ateco"
  );

  $.validator.addMethod("soas", function(value, element) {
      return check_soas();
    },
    "Avete indicato il possesso di certificato SOA, prego indicare quale/i"
  );

  $.validator.addMethod("dipendenti", function(value, element) {
      return ($('[name=addetti_n_dipendenti]').val() + $('[name=addetti_n_socilav]').val() + $('[name=addetti_n_artigiani]').val()).match(/^\d{1,10}$/);
    },
    "Compilare almeno un campo riguardante il numero di dipendenti (inserendo un numero)"
  );

  $.validator.addMethod("cf", function(value, element) {
    return true; //disabled
      if (value.length == 16 || value.length == 11) {
        return true
      }
      else {
        return false
      }
    },
    "Il codice fiscale deve contenere 16 caratteri, oppure 11 nel caso coincida con la partita iva"
  );
  $.validator.addMethod("_pivacf", function(value, element) {
      if ( 11 <= value.length && 16 >= value.length ) {
        return true;
      }
      else {
        return false
      }
    },
    "La partita iva / codice fiscale deve essere tra gli 11 e i 16 caratteri."
  );

  $.validator.addMethod("cfp", function(value, element) {
      return true;
      // disabled.
      if (value.length == 16) {
        return true
      }
      else {
        return false
      }
    },
    "Il codice fiscale deve contenere 16 caratteri"
  );

    $.validator.addMethod("piva", function(value, element) {
        if (value.length == 11) {
          return true
        }
        else {
            return false
        }
      },
      "La partita IVA deve contenere 11 caratteri"
    );

    $.validator.addMethod("maxlen500", function(value, element) {
        if (value.length <= 500) {
            return true
        }
        else {
          return false
        }
      },
      "il campo deve contenere al massimo 500 caratteri"
    );

    $.validator.addMethod("maxlen1000", function(value, element) {
        if (value.length <= 1000) {
            return true
        }
        else {
          return false
        }
      },
      "il campo deve contenere al massimo 1000 caratteri"
    );

    $.validator.addMethod("maxlen", function(value, element) {
        if (value.length <= 200) {
            return true
        }
        else {
          return false
        }
      },
      "il campo deve contenere al massimo 200 caratteri"
    );

    $.validator.addMethod("inps", function(value, element) {
        if (value.length == 10) {
          return true
        }
        else {
          return false
        }
      },
      "La posizione INPS deve contenere 10 caratteri"
    );

    $("form").validate({
      errorClass: "errormsg",
      rules: {
        cf: {
          required: true,
          rangelength: [16, 16]
        },
        /*
        partita_iva: {
          required: true,
          remote: {
            url: '/domanda/check_piva',
            type: "post",
            data: {
              piva: jQuery("#partita_iva").val()
            }
          }
        }
        */
      },
      errorPlacement: function(error, element) {
        if ($(element).hasClass("ateco")) {
          $('#errorbox_ateco').html(error);
        } else if ($(element).hasClass("soas")) {
          $('#errorbox_soas').html(error);
        }
        else {
          error.appendTo(element.parent());
        }
      }
    });

    $('#operativa').on('click', function() {
      $('[name=so_via]').val($('[name=sl_via]').val())
      $('[name=so_civico]').val($('[name=sl_civico]').val())
      $('[name=so_cap]').val($('[name=sl_cap]').val())
      $('[name=so_comune]').val($('[name=sl_comune]').val())
      $('[name=so_provincia]').val($('[name=sl_prov]').val())
    });

    $('select[name=tipo_contratto]').change(function() {
      if ($(this).val() == "Altri settori") {
        $('input[name=tipo_contratto_altri]').removeAttr('disabled');
        $('[name=pos_cassa_denominazione]').removeClass("required maxlen errormsg");
        $('[for=pos_cassa_denominazione]').text('Denominazione');
        $('[name=pos_cassa_n]').removeClass("required maxlen errormsg");
        $('[for=pos_cassa_n]').text('Posizione n.');
        $('[name=pos_cassa_sede]').removeClass("required maxlen errormsg");
        $('[for=pos_cassa_sede]').text('Ufficio');
      }
      else {
        $('input[name=tipo_contratto_altri]').attr('disabled', 'disabled');
        $('[name=pos_cassa_denominazione]').addClass("required maxlen errormsg");
        $('[for=pos_cassa_denominazione]').text('Denominazione*');
        $('[name=pos_cassa_n]').addClass("required maxlen errormsg");
        $('[for=pos_cassa_n]').text('Posizione n.*');
        $('[name=pos_cassa_sede]').addClass("required maxlen errormsg");
        $('[for=pos_cassa_sede]').text('Ufficio*');
      }
    });

    $('select[name=tipo_impresa]').change(function() {
      if ($(this).val() == "Altro") {
        $('input[name=tipo_impresa_altri]').removeAttr('disabled');
      }
      else {
        $('input[name=tipo_impresa_altri]').attr('disabled', 'disabled');
      }
    });

    $('select[name=forma_giuridica]').change(function() {
      if (($(this).val() == "Gestione Separata - Committente/Associante") || ($(this).val() == "Gestione separata - Titolare di reddito di lavoro autonomo di arte e professione")) {
        $('[for=pos_inps_n]').text('Posizione n.');
        $('[name=pos_inps_n]').removeClass("required inps errormsg");
      }
      else {
        $('[for=pos_inps_n]').text('Posizione n.*');
        $('[name=pos_inps_n]').addClass("required inps errormsg");
      }
    });

    $('select[name=soa_sino]').change(function() {
      if ($(this).val() == "Yes") {
        $('.soas').removeAttr('disabled');
      }
      else {
        $('.soas').attr('disabled', 'disabled');
      }
    });
});

function check_ateco() {
  a = false;
  $(".ateco").each(function(index, element) {
    if (element.checked) {
      a = true;
    }
  });
  return a;
}

function check_soas() {
  result = true;
  if ($('select[name=soa_sino]').val() == "Yes") {
    result = false;
    $(".soas").each(function(index, element) {
      if ($(element).val() != "0") {
        result = true
      }
    });
  }
  return result;
}

var cache = {},
    comuni;

function autocompleta_cache(comune, provincia) {
  $("[name=" + comune + "]").autocomplete({
    source: function(req, add) {
      // controllo se ho già memorizzato questa ricerca
      var term = req.term;
      if (term in cache) {
        add(cache[term]);
        return;
      }
      $.getJSON("/domanda/comune/" + term, function(data) { // da modifcare in relazione all'URL di pubblicazione
        var comuni = []
        $.each(data, function(i, val) {
          val.label = val.comune;
          comuni.push(val);
        });
        cache[term] = comuni;
        add(comuni);
      })
    },
    select: function(event, ui) {
      // sulla base del comune selezionato, imposto la provincia
      $("[name=" + provincia + "]").val(ui.item.provincia);
    },
    minLength: 2
  });
}
