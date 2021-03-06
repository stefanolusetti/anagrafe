jQuery(document).ready(function() {
  _enableAutocomplete(
    'istanza_luogo',
    ''
  );
  _enableAutocomplete('titolare_nascita_comune', 'titolare_nascita_provincia');
  _enableAutocomplete('titolare_res_comune', 'titolare_res_provincia');
  _enableAutocomplete('sl_comune', 'sl_prov');
  window.ia = jQuery(".anagrafica.anagrafica-box").length;
  window.is = jQuery(".office.container").length;
  window.familiars = {};
  mainEventHandlers();
  subEventHandlers();
  subOfficeEventHandlers();
});

function buildFamiliarsObject() {
  jQuery(".anagrafica-box").each(function(i, el){
    var elid = jQuery(el).attr('data-elid');
    var numFamiliars = jQuery(el).find('.familiars[data-elid=' + elid + ']').children().length;
    window.familiars[elid] = numFamiliars;
  });
}

/*
██   ██  █████  ███    ██ ██████  ██      ███████ ██████  ███████
██   ██ ██   ██ ████   ██ ██   ██ ██      ██      ██   ██ ██
███████ ███████ ██ ██  ██ ██   ██ ██      █████   ██████  ███████
██   ██ ██   ██ ██  ██ ██ ██   ██ ██      ██      ██   ██      ██
██   ██ ██   ██ ██   ████ ██████  ███████ ███████ ██   ██ ███████
*/
function mainEventHandlers(){
  buildFamiliarsObject();
  jQuery('.container > a.addAnagrafica').on('click', function(e){
    e.preventDefault();
    addAnagrafica(e);
  });
  jQuery('.container > a.resetAnagrafiche').on('click', function(e){
    e.preventDefault();
    resetAnagrafiche(e);
  });

  jQuery('a.addOffice').on('click', function(e){
    e.preventDefault();
    addOffice(e);
  });

  jQuery(".company_fields").change(function(e){
    if (
      jQuery("#impresa_settore_trasporto").is(':checked')
      || jQuery("#impresa_settore_rifiuti").is(':checked')
      || jQuery("#impresa_settore_terra").is(':checked')
      || jQuery("#impresa_settore_bitume").is(':checked')
      || jQuery("#impresa_settore_nolo").is(':checked')
      || jQuery("#impresa_settore_ferro").is(':checked')
      || jQuery("#impresa_settore_autotrasporto").is(':checked')
      || jQuery("#impresa_settore_guardiana").is(':checked')
    ) {
      jQuery("#impresa_settore_nessuno").attr('checked', false);
    }
  });

  jQuery("#impresa_settore_nessuno").change(function(e){
    if (jQuery("#impresa_settore_nessuno").is(':checked')) {
      jQuery(".company_fields").attr('checked', false);
    }
  });

  jQuery("[name=stmt_wl]").change(function(e){
    if (jQuery('#stmt_wl_si').is(':checked')) {
      jQuery('#stmt_wl_more').show();
    }
    else {
      jQuery('#stmt_wl_more').hide();
    }
  });

  jQuery("#titolare_rappresentanza").change(function(e){
    if ('Altro' == jQuery(e.target).val()) {
      jQuery('#titolare_rappresentanza_more').show();
    }
    else {
      jQuery('#titolare_rappresentanza_more').hide();
    }
  });

  jQuery("#forma_giuridica_id").change(function(e){
    if ('0' == jQuery(e.target).val()) {
      jQuery('.controlla_tipo_impresa').show();
    }
    else {
      jQuery('.controlla_tipo_impresa').hide();
    }
  });

  jQuery('.elToggler').change(function(e){
    var target = jQuery(e.target).attr('data-el');
    if (jQuery(e.target).is(':checked')) {
      jQuery('#' + target).show();
    }
    else {
      jQuery('#' + target).hide();
    }
  });
};

function autocompleta_cache_id(comune, provincia) {
  jQuery("#" + comune).autocomplete({
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
      $("#" + provincia).val(ui.item.provincia);
    },
    minLength: 2
  });
}

function _enableAutocomplete(from, to){
  autocompleta_cache_id(from, to);
};

function subEventHandlers(){
  // avoid duplicate handlers
  jQuery('a.addFamiliar').off('click');
  jQuery('a.removeFamiliar').off('click');
  jQuery('a.removeThisFamiliar').off('click');

  jQuery('a.addFamiliar').on('click', function(e){
    e.preventDefault();
    addFamiliar(e);
  });
  jQuery('a.removeFamiliar').on('click', function(e){
    e.preventDefault();
    removeFamiliar(e);
  });
  jQuery('a.removeThisFamiliar').on('click', function(e){
    e.preventDefault();
    removeThisFamiliar(e);
  });
};

function subOfficeEventHandlers(){
  // avoid duplicate handlers
  jQuery('a.removeOffice').off('click');

  jQuery('a.removeOffice').on('click', function(e){
    e.preventDefault();
    removeOffice(e);
  });
};

/*
 █████  ██████  ██████
██   ██ ██   ██ ██   ██
███████ ██   ██ ██   ██
██   ██ ██   ██ ██   ██
██   ██ ██████  ██████
*/
function addAnagrafica(e){
  (function(index){
    var provincie_options = '';
    for (p in window._provincie) {
      provincie_options += '<option value="'+p+'">' + window._provincie[p] + '</option>';
    }
    var roles_options = '';
    for (p in window._key_roles) {
      roles_options += '<option value="'+[p]+'">' + window._key_roles[p] + '</option>';
    }
    var template = '<div id="anel-###" class="anagrafica anagrafica-box el-###" data-elid="###"> \
      <h2>Anagrafica del componente</h2> \
      <div class="field"> \
        <label for="antimafia_role">Ruolo</label> \
        <select name="anagrafica[###][antimafia_role]" id="anagrafica[###][antimafia_role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_nome">Nome*</label> \
        <input type="text" name="anagrafica[###][antimafia_nome]" value="" id="anagrafica_###_antimafia_nome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_cognome">Cognome*</label> \
        <input type="text" name="anagrafica[###][antimafia_cognome]" value="" id="anagrafica_###_antimafia_cognome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_data_nascita">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafica[###][antimafia_data_nascita]" value="" id="anagrafica[###][antimafia_data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_nascita">Comune di nascita*</label> \
        <input type="text" name="anagrafica[###][antimafia_comune_nascita]" value="" id="anagrafica_###_antimafia_comune_nascita" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_provincia_residenza">Provincia di nascita</label> \
        <select name="anagrafica[###][antimafia_provincia_nascita]" id="anagrafica_###_antimafia_provincia_nascita"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_cf">Codice Fiscale*</label> \
        <input type="text" name="anagrafica[###][antimafia_cf]" value="" id="anagrafica[###][antimafia_cf]" class="required cfp" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_residenza">Comune di Residenza*</label> \
        <input type="text" name="anagrafica[###][antimafia_comune_residenza]" value="" id="anagrafica_###_antimafia_comune_residenza" class="required" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_provincia_residenza">Provincia di residenza*</label> \
        <select name="anagrafica[###][antimafia_provincia_residenza]" id="anagrafica_###_antimafia_provincia_residenza"  class="required"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_via_residenza">Via residenza*</label> \
        <input type="text" name="anagrafica[###][antimafia_via_residenza]" value="" id="anagrafica[###][antimafia_via_residenza]"  class="required" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_civico_residenza">Civico residenza*</label> \
        <input type="text" name="anagrafica[###][antimafia_civico_residenza]" value="" id="anagrafica[###][antimafia_civico_residenza]"  class="required" /> \
      </div> \
      <a href="#anagrafiche" class="addFamiliar add" data-elid="###">Aggiungi Familiare Convivente Maggiorenne</a> | <a href="#anagrafiche" class="removeFamiliar rm" data-victim="anel-###" data-elid="###">Rimuovi Anagrafica</a> \
      <div class="familiars" data-elid="###"></div> \
    </div>';
    var element = template.split('###').join(index);
    jQuery('.inputs').prepend(jQuery(element));
    subEventHandlers();
    _enableAutocomplete(
      'anagrafica_'+index+'_antimafia_comune_nascita',
      'anagrafica_'+index+'_antimafia_provincia_nascita'
    );
    _enableAutocomplete(
      'anagrafica_'+index+'_antimafia_comune_residenza',
      'anagrafica_'+index+'_antimafia_provincia_residenza'
    );
  })(window.ia);
  window.ia++;
}

/*
 █████  ██████  ██████   ██████  ███████ ███████ ██  ██████ ███████
██   ██ ██   ██ ██   ██ ██    ██ ██      ██      ██ ██      ██
███████ ██   ██ ██   ██ ██    ██ █████   █████   ██ ██      █████
██   ██ ██   ██ ██   ██ ██    ██ ██      ██      ██ ██      ██
██   ██ ██████  ██████   ██████  ██      ██      ██  ██████ ███████
*/
function addOffice(e){
  (function(index){
    var template = '<div class="office container" id="ofel-###"><div class="field "><label for="office###name">Ragione Sociale impresa partecipata*<a href="#" data-elid="###" class="rm removeOffice" data-victim="ofel-###">Rimuovi Impresa</a></label><input type="text" name="office[###][name]" value="" id="office###name" class="required maxlen"></div><div class="field "><label for="office###vat">Partita IVA impresa partecipata*</label><input type="text" name="office[###][vat]" value="" id="office###vat" class="required maxlen piva"></div><div class="field "><label for="office###cf">Codice Fiscale impresa partecipata*</label><input type="text" name="office[###][cf]" value="" id="office###cf" class="required maxlen cf"></div><div class="resizer"></div></div>';
    var element = template.split('###').join(index);
    jQuery('.offices').prepend(jQuery(element));
    subOfficeEventHandlers();
  })(window.is);
  window.is++;
}

/*
 █████  ██████  ██████  ███████  █████  ███    ███ ██ ██      ██  █████  ██████
██   ██ ██   ██ ██   ██ ██      ██   ██ ████  ████ ██ ██      ██ ██   ██ ██   ██
███████ ██   ██ ██   ██ █████   ███████ ██ ████ ██ ██ ██      ██ ███████ ██████
██   ██ ██   ██ ██   ██ ██      ██   ██ ██  ██  ██ ██ ██      ██ ██   ██ ██   ██
██   ██ ██████  ██████  ██      ██   ██ ██      ██ ██ ███████ ██ ██   ██ ██   ██
*/
function addFamiliar(e){
  var idx = jQuery(e.target).attr('data-elid');
  if ('undefined' == typeof window.familiars[idx]) {
    window.familiars[idx] = 0;
  }
  (function(idx){
    var cidx = window.familiars[idx];
    var idv = parseInt(cidx) + 1;
    var roles_options = '';
    for (p in window._fam_roles) {
      roles_options += '<option value="'+p+'">' + window._fam_roles[p] + '</option>';
    }
    var provincie_options = '';
    for (p in window._provincie) {
      provincie_options += '<option value="'+p+'">' + window._provincie[p] + '</option>';
    }
    // ### = numero anagrafica
    // @@@ = numero Familiare
    // *** = numero familiare da visualizzare
    var template = '<div class="box-familiare" id="fam-###-@@@"><hr />\
      <a href="#anagrafiche" class="rm removeThisFamiliar" data-victim="fam-###-@@@" data-elid="###" data-elfid="@@@">Rimuovi Familiare Convivente Maggiorenne</a> \
      <div class="field hidden"> \
        <label>Ruolo</label> \
        <select name="anagrafica[###][f][@@@][role]" id="anagrafica[###][f][@@@][role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][name]">Nome*</label> \
        <input type="text" name="anagrafica[###][f][@@@][name]" value="" id="anagrafica_###_f_@@@_name" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][titolare_cognome]">Cognome*</label> \
        <input type="text" name="anagrafica[###][f][@@@][titolare_cognome]" value="" id="anagrafica_###_f_@@@_titolare_cognome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][data_nascita]">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafica[###][f][@@@][data_nascita]" value="" id="anagrafica[###][f][@@@][data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][comune]">Comune di nascita*</label> \
        <input type="text" name="anagrafica[###][f][@@@][comune]" value="" id="anagrafica_###_f_@@@_comune" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][provincia]">Provincia di nascita*</label> \
        <select name="anagrafica[###][f][@@@][provincia]" id="anagrafica_###_f_@@@_provincia"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][cf]">Codice Fiscale*</label> \
        <input type="text" name="anagrafica[###][f][@@@][cf]" value="" id="anagrafica[###][f][@@@][cf]" class="required cfp" /> \
      </div> \
      <div class="resizer"></div> \
    </div>';
    var element = template.split('###').join(idx);
    element = element.split('@@@').join(cidx);
    element = element.split('***').join(idv);
    jQuery(".familiars[data-elid=" + idx + "]").prepend(jQuery(element));
    subEventHandlers();
    _enableAutocomplete(
      'anagrafica_' + idx + '_f_' + cidx + '_comune',
      'anagrafica_' + idx + '_f_' + cidx + '_provincia'
    );
  })(idx);
  window.familiars[idx]++;
}

/*
██████  ███████ ███    ███  ██████  ██    ██ ███████ ███    ███ ███████
██   ██ ██      ████  ████ ██    ██ ██    ██ ██      ████  ████ ██
██████  █████   ██ ████ ██ ██    ██ ██    ██ █████   ██ ████ ██ █████
██   ██ ██      ██  ██  ██ ██    ██  ██  ██  ██      ██  ██  ██ ██
██   ██ ███████ ██      ██  ██████    ████   ███████ ██      ██ ███████
*/
function removeFamiliar(e){
  var idx = jQuery(e.target).attr('data-elid');
  var whoIsIt = jQuery('#anagrafica_' + idx + '_antimafia_nome').val() + ' ' + jQuery('#anagrafica_' + idx + '_antimafia_cognome').val();

  var letsGo = window.confirm('Sei sicuro di voler eliminare ' + whoIsIt + ' ?');
  if(true === letsGo) {
    _removeFamiliar(e);
  }
}

function _removeFamiliar(e){
  var idx = jQuery(e.target).attr('data-elid');
  var domId = jQuery(e.target).attr('data-victim');
  if ('undefined' == typeof window.familiars[idx]) {
    window.familiars[idx] = 0;
  }
  jQuery("#" + domId).remove();
  subEventHandlers();
};



function removeThisFamiliar(e){
  var idx = jQuery(e.target).attr('data-elid');
  var idfx = jQuery(e.target).attr('data-elfid');
  var whoIsIt = jQuery('#anagrafica_'+idx+'_f_'+idfx+'_name').val() + ' ' + jQuery('#anagrafica_'+idx+'_f_'+idfx+'_titolare_cognome').val();

  var letsGo = window.confirm('Sei sicuro di voler eliminare ' + whoIsIt + ' ?');
  if(true === letsGo) {
    _removeThisFamiliar(e);
  }
}

function _removeThisFamiliar(e){
  var domId = jQuery(e.target).attr('data-victim');
  jQuery("#" + domId).remove();
}

function removeOffice(e){
  var idx = jQuery(e.target).attr('data-elid');
  var whoIsIt = jQuery('#office'+idx+'name').val();

  var letsGo = window.confirm('Sei sicuro di voler eliminare ' + whoIsIt + ' ?');
  if(true === letsGo) {
    _removeOffice(e);
  }
}

function _removeOffice(e){
  var domId = jQuery(e.target).attr('data-victim');
  jQuery("#" + domId).remove();
  subOfficeEventHandlers();
}

/*
██████  ███████ ███████ ███████ ████████
██   ██ ██      ██      ██         ██
██████  █████   ███████ █████      ██
██   ██ ██           ██ ██         ██
██   ██ ███████ ███████ ███████    ██
*/
function resetAnagrafiche(e) {
  if(window.confirm('Sei sicuro di voler eliminare TUTTE le anagrafiche?')) {
    _resetAnagrafiche(e);
  }
}

function _resetAnagrafiche(e) {
  window.is = 0;
  window.familiars = {};
  jQuery('.inputs').empty();
}
