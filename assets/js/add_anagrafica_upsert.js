jQuery(document).ready(function() {
  _enableAutocomplete(
    'istanza_luogo',
    ''
  );
  _enableAutocomplete('titolare_nascita_comune', 'titolare_nascita_provincia');
  _enableAutocomplete('titolare_res_comune', 'titolare_res_provincia');
  _enableAutocomplete('sl_comune', 'sl_prov');
  window.ia = jQuery(".anagrafica.anagrafica-box").length;
  window.is = jQuery(".imprese_partecipate.container").length;
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
  jQuery('a.add.addAnagrafica').on('click', function(e){
    e.preventDefault();
    addAnagrafica(e);
  });
  jQuery('a.reset.resetAnagrafiche').on('click', function(e){
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
      jQuery('#forma_giuridica_more').show();
    }
    else {
      jQuery('#forma_giuridica_more').hide();
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
      $("#" + provincia).val(ui.item.provincia).trigger('keyup');
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
    var evenOdd = index % 2 ? 'odd' : 'even';
    var template = '<div id="anel-###" class="anagrafica preview-anagrafica anagrafica-box el-###" data-elid="###"> \
    <a href="#anagrafiche" class="removeFamiliar rm" data-victim="anel-###" data-elid="###">Rimuovi Anagrafica Componente</a> \
      <div class="field"> \
        <label for="antimafia_role">Ruolo</label> \
        <select name="anagrafiche_antimafia[###][antimafia_role]" id="anagrafiche_antimafia[###][antimafia_role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_nome">Nome*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_nome]" value="" id="anagrafiche_antimafia_###_antimafia_nome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_cognome">Cognome*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_cognome]" value="" id="anagrafiche_antimafia_###_antimafia_cognome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_data_nascita">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_data_nascita]" value="" id="anagrafiche_antimafia[###][antimafia_data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_nascita">Comune di nascita*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_comune_nascita]" value="" id="anagrafiche_antimafia_###_antimafia_comune_nascita" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_provincia_residenza">Provincia di nascita</label> \
        <select name="anagrafiche_antimafia[###][antimafia_provincia_nascita]" id="anagrafiche_antimafia_###_antimafia_provincia_nascita"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_cf">Codice Fiscale*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_cf]" value="" id="anagrafiche_antimafia[###][antimafia_cf]" class="required cfp" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_residenza">Comune di Residenza*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_comune_residenza]" value="" id="anagrafiche_antimafia_###_antimafia_comune_residenza" class="required" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_provincia_residenza">Provincia di residenza*</label> \
        <select name="anagrafiche_antimafia[###][antimafia_provincia_residenza]" id="anagrafiche_antimafia_###_antimafia_provincia_residenza"  class="required"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_via_residenza">Via residenza*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_via_residenza]" value="" id="anagrafiche_antimafia[###][antimafia_via_residenza]"  class="required" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_civico_residenza">Civico residenza*</label> \
        <input type="text" name="anagrafiche_antimafia[###][antimafia_civico_residenza]" value="" id="anagrafiche_antimafia[###][antimafia_civico_residenza]"  class="required" /> \
      </div> \
      <a href="#anagrafiche" class="addFamiliar add" data-elid="###">Aggiungi Familiare Convivente Maggiorenne</a> \
      <div class="resizer"></div> \
      <div class="familiars" data-elid="###"></div> \
    </div>';
    var element = template.split('###').join(index);
    jQuery('#elenco_anagrafiche').prepend(jQuery(element));
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
    var template = '<div class="imprese_partecipate container odder" id="ofel-###"><div class="field "><label for="imprese_partecipate###name">Ragione Sociale impresa partecipata*<a href="#" data-elid="###" class="rm removeOffice" data-victim="ofel-###">Rimuovi Impresa</a></label><input type="text" name="imprese_partecipate[###][nome]" value="" id="imprese_partecipate###nome" class="required maxlen"></div><div class="field "><label for="imprese_partecipate###piva">Partita IVA impresa partecipata*</label><input type="text" name="imprese_partecipate[###][piva]" value="" id="imprese_partecipate###piva" class="required maxlen"></div><div class="field "><label for="imprese_partecipate###cf">Codice Fiscale impresa partecipata*</label><input type="text" name="imprese_partecipate[###][cf]" value="" id="imprese_partecipate###cf" class="required maxlen cf"></div><div class="resizer"></div></div>';
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
    var template = '<div class="box-familiare" id="fam-###-@@@"> \
      <a href="#anagrafiche" class="rm removeThisFamiliar" data-victim="fam-###-@@@" data-elid="###" data-elfid="@@@">Rimuovi Familiare Convivente Maggiorenne</a> \
      <div class="field hidden"> \
        <label>Ruolo</label> \
        <select name="anagrafiche_antimafia[###][familiari][@@@][role]" id="anagrafiche_antimafia[###][familiari][@@@][role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][name]">Nome*</label> \
        <input type="text" name="anagrafiche_antimafia[###][familiari][@@@][name]" value="" id="anagrafiche_antimafia_###_familiari_@@@_name" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][titolare_cognome]">Cognome*</label> \
        <input type="text" name="anagrafiche_antimafia[###][familiari][@@@][titolare_cognome]" value="" id="anagrafiche_antimafia_###_familiari_@@@_titolare_cognome" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][data_nascita]">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafiche_antimafia[###][familiari][@@@][data_nascita]" value="" id="anagrafiche_antimafia[###][familiari][@@@][data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][comune]">Comune di nascita*</label> \
        <input type="text" name="anagrafiche_antimafia[###][familiari][@@@][comune]" value="" id="anagrafiche_antimafia_###_familiari_@@@_comune" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][provincia]">Provincia di nascita*</label> \
        <select name="anagrafiche_antimafia[###][familiari][@@@][provincia]" id="anagrafiche_antimafia_###_familiari_@@@_provincia"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafiche_antimafia[###][familiari][@@@][cf]">Codice Fiscale*</label> \
        <input type="text" name="anagrafiche_antimafia[###][familiari][@@@][cf]" value="" id="anagrafiche_antimafia[###][familiari][@@@][cf]" class="required cfp" /> \
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
  var whoIsIt = jQuery('#anagrafiche_antimafia_' + idx + '_antimafia_nome').val() + ' ' + jQuery('#anagrafiche_antimafia_' + idx + '_antimafia_cognome').val();

  var letsGo = window.confirm('Sei sicuro di voler eliminare ' + whoIsIt + '?');
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
  var whoIsIt = jQuery('#anagrafiche_antimafia_'+idx+'_familiari_'+idfx+'_nome').val() + ' ' + jQuery('#anagrafiche_antimafia_'+idx+'_familiari_'+idfx+'_cognome').val();
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
  var whoIsIt = jQuery('#imprese_partecipate'+idx+'nome').val();

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
  jQuery('#elenco_anagrafiche').empty();
}
