jQuery(function() {
  mainEventHandlers();
  window.ia = 0;
  window.familiars = {};
});

/*
██   ██  █████  ███    ██ ██████  ██      ███████ ██████  ███████
██   ██ ██   ██ ████   ██ ██   ██ ██      ██      ██   ██ ██
███████ ███████ ██ ██  ██ ██   ██ ██      █████   ██████  ███████
██   ██ ██   ██ ██  ██ ██ ██   ██ ██      ██      ██   ██      ██
██   ██ ██   ██ ██   ████ ██████  ███████ ███████ ██   ██ ███████
*/
function mainEventHandlers(){
  jQuery('.container > a.addAnagrafica').on('click', function(e){
    e.preventDefault();
    addAnagrafica(e);
  });
  jQuery('.container > a.resetAnagrafiche').on('click', function(e){
    e.preventDefault();
    resetAnagrafiche(e);
  });
};

function subEventHandlers(){
  // avoid duplicate handlers
  jQuery('a.addFamiliar').off('click');
  jQuery('a.removeMe').off('click');

  jQuery('a.addFamiliar').on('click', function(e){
    e.preventDefault();
    addFamiliar(e);
  });
  jQuery('a.removeMe').on('click', function(e){
    e.preventDefault();
    removeMe(e);
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
      roles_options += '<option value="'+window._key_roles[p].rid+'">' + window._key_roles[p].value + '</option>';
    }
    var template = '<div class="anagrafica el-###" data-elid="###"> \
      <input type="hidden" name="anagrafica[]" value="anagrafica" id="anagrafica"> \
      <h2>Anagrafica del componente</h2> \
      <div class="field"> \
        <label for="antimafia_role">Ruolo</label> \
        <select name="anagrafica[###][antimafia_role]" id="anagrafica[###][antimafia_role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_nome">Nome e Cognome*</label> \
        <input type="text" name="anagrafica[###][antimafia_nome]" value="" id="anagrafica[###][antimafia_nome]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_nascita">Comune di nascita*</label> \
        <input type="text" name="anagrafica[###][antimafia_comune_nascita]" value="" id="anagrafica[###][antimafia_comune_nascita]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_data_nascita">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafica[###][antimafia_data_nascita]" value="" id="anagrafica[###][antimafia_data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_cf">Codice Fiscale*</label> \
        <input type="text" name="anagrafica[###][antimafia_cf]" value="" id="anagrafica[###][antimafia_cf]" class="required cf" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_carica_sociale">Carica sociale*</label> \
        <input type="text" name="anagrafica[###][antimafia_carica_sociale]" value="" id="anagrafica[###][antimafia_carica_sociale]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_comune_residenza">Residente a</label> \
        <input type="text" name="anagrafica[###][antimafia_comune_residenza]" value="" id="anagrafica[###][antimafia_comune_residenza]" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_provincia_residenza">Provincia di residenza</label> \
        <select name="anagrafica[###][antimafia_provincia_residenza]" id="anagrafica[###][antimafia_provincia_residenza]"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="antimafia_via_residenza">Via</label> \
        <input type="text" name="anagrafica[###][antimafia_via_residenza]" value="" id="anagrafica[###][antimafia_via_residenza]" /> \
      </div> \
      <div class="field"> \
        <label for="antimafia_civico_residenza">Civico</label> \
        <input type="text" name="anagrafica[###][antimafia_civico_residenza]" value="" id="anagrafica[###][antimafia_civico_residenza]" /> \
      </div> \
      <a href="#anagrafiche" class="addFamiliar" data-elid="###">Aggiungi Familiare</a> | <a href="#anagrafiche" class="removeMe" data-elid="###">Rimuovi Anagrafica</a> \
      <div class="familiars" data-elid="###"></div> \
    </div>';
    var element = template.split('###').join(index);
    jQuery('.inputs').prepend(jQuery(element));
    subEventHandlers();
  })(window.ia);
  window.ia++;
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
      roles_options += '<option value="'+window._fam_roles[p].rid+'">' + window._fam_roles[p].value + '</option>';
    }
    var provincie_options = '';
    for (p in window._provincie) {
      provincie_options += '<option value="'+p+'">' + window._provincie[p] + '</option>';
    }
    // ### = numero anagrafica
    // @@@ = numero Familiare
    // *** = numero familiare da visualizzare
    var template = '<div id="fam-###-@@@">\
      <h2>Familiare Convivente n.***</h2>\
      <div class="field"> \
        <label>Ruolo</label> \
        <select name="anagrafica[###][f][@@@][role]" id="anagrafica[###][f][@@@][role]"> \
        ' + roles_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][name]">Nome*</label> \
        <input type="text" name="anagrafica[###][f][@@@][name]" value="" id="anagrafica[###][f][@@@][name]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][surname]">Cognome*</label> \
        <input type="text" name="anagrafica[###][f][@@@][surname]" value="" id="anagrafica[###][f][@@@][surname]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][comune]">Comune di nascita*</label> \
        <input type="text" name="anagrafica[###][f][@@@][comune]" value="" id="anagrafica[###][f][@@@][comune]" class="required maxlen" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][provincia]">Provincia di residenza</label> \
        <select name="anagrafica[###][f][@@@][provincia]" id="anagrafica[###][f][@@@][provincia]"> \
        ' + provincie_options + '\
        </select>\
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][data_nascita]">Data di nascita (nel formato gg/mm/aaaa)*</label> \
        <input type="text" name="anagrafica[###][f][@@@][data_nascita]" value="" id="anagrafica[###][f][@@@][data_nascita]" class="required data" /> \
      </div> \
      <div class="field"> \
        <label for="anagrafica[###][f][@@@][cf]">Codice Fiscale*</label> \
        <input type="text" name="anagrafica[###][f][@@@][cf]" value="" id="anagrafica[###][f][@@@][cf]" class="required cf" /> \
      </div> \
    </div>';


    var element = template.split('###').join(idx);
    element = element.split('@@@').join(cidx);
    element = element.split('***').join(idv);

    jQuery(".familiars[data-elid=" + idx + "]").prepend(jQuery(element));
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
function removeMe(e){
  var idx = jQuery(e.target).attr('data-elid');
  if ('undefined' == typeof window.familiars[idx]) {
    window.familiars[idx] = 0;
  }
  jQuery(".anagrafica[data-elid=" + idx + "]").remove();
  subEventHandlers();
}

/*
██████  ███████ ███████ ███████ ████████
██   ██ ██      ██      ██         ██
██████  █████   ███████ █████      ██
██   ██ ██           ██ ██         ██
██   ██ ███████ ███████ ███████    ██
*/
function resetAnagrafiche(e) {
  window.ia = 0;
  window.familiars = {};
  jQuery('.inputs').empty();
}
