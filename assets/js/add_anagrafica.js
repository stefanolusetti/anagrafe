$(function() {
  var i = 0;
  $('.container > a').click(function(e) {
    e.preventDefault();
    var $this = $(this),
      prnt = $this.parent();
      //i = prnt.find('input').length;
    if ($this.hasClass('add')) {
      $('<div class="anagrafica"><input type="hidden" name="anagrafica[]" value="anagrafica" id="anagrafica"><h2>Anagrafica del componente</h2><div class="field"><label for="antimafia_nome">Nome e Cognome*</label><input type="text" name="anagrafica[' + i + '][antimafia_nome]" value="" id="anagrafica[' + i + '][antimafia_nome]" class="required maxlen" /></div><div class="field"><label for="antimafia_comune_nascita">Comune di nascita*</label><input type="text" name="anagrafica[' + i + '][antimafia_comune_nascita]" value="" id="anagrafica[' + i + '][antimafia_comune_nascita]" class="required maxlen" /></div><div class="field"><label for="antimafia_data_nascita">Data di nascita (nel formato gg/mm/aaaa)*</label><input type="text" name="anagrafica[' + i + '][antimafia_data_nascita]" value="" id="anagrafica[' + i + '][antimafia_data_nascita]" class="required data"/></div><div class="field"><label for="antimafia_cf">Codice Fiscale*</label><input type="text" name="anagrafica[' + i + '][antimafia_cf]" value="" id="anagrafica[' + i + '][antimafia_cf]" class="required cf"/></div><div class="field"><label for="antimafia_carica_sociale">Carica sociale*</label><input type="text" name="anagrafica[' + i + '][antimafia_carica_sociale]" value="" id="anagrafica[' + i + '][antimafia_carica_sociale]" class="required maxlen"/></div><div class="field"><label for="antimafia_comune_residenza">Residente a</label><input type="text" name="anagrafica[' + i + '][antimafia_comune_residenza]" value="" id="anagrafica[' + i + '][antimafia_comune_residenza]"/></div><div class="field"><label for="antimafia_provincia_residenza">Provincia di residenza</label><input type="text" name="anagrafica[' + i + '][antimafia_provincia_residenza]" value="" id="anagrafica[' + i + '][antimafia_provincia_residenza]"/></div><div class="field"><label for="antimafia_via_residenza">Via</label><input type="text" name="anagrafica[' + i + '][antimafia_via_residenza]" value="" id="anagrafica[' + i + '][antimafia_via_residenza]"/></div><div class="field"><label for="antimafia_civico_residenza">Civico</label><input type="text" name="anagrafica[' + i + '][antimafia_civico_residenza]" value="" id="anagrafica[' + i + '][antimafia_civico_residenza]"/></div></>').hide().fadeIn('slow').appendTo($('.inputs', prnt));
      i += 1;
    }
    else if ($this.hasClass('remove')) {
      prnt.find('.anagrafica:last').remove();
      i--;
    }
    else if ($this.hasClass('reset')) {
      prnt.find('.anagrafica').remove();
      i = 0;
    }
  });
});
