jQuery(document).ready(function() {
  window._currentModal = null;
  window._currentEsecutore = null;
  window._currentTemplate = 0;
  jQuery("a[data-action]").on('click', function( e ) {
    var elid = jQuery(e.target).attr('data-item');
    var action = jQuery(e.target).attr('data-action');
    window._currentEsecutore = elid;
    // need logic? here is a good place for it.
    if ( 'info-request' == action ) {
      requestInfoModal(true);
    }
  });

  jQuery("#irm-template").on('change', function ( e ) {
    window._currentTemplate = jQuery(e.target).val();
    requestInfoModal(false);
  });

  jQuery("#irm-no").on('click', function() {
    closeModal('infoRequestModal');
  });

  jQuery("#irm-si").on('click', function() {
    // do stuff before submit the form.
    jQuery("#irm").submit();
  });

  jQuery("#modal-fog").on('click', function() {
    closeModal(window._currentModal);
  });

  jQuery( "#irm-to" ).autocomplete({
    source: window._pec_list,
  });

});

function requestInfoModal(updateTos) {
  // Initialize templates, email address, etc..
  jQuery.ajax({
    url: '/admin/mailtemplate/' + window._currentEsecutore + '/0/' + window._currentTemplate
  }).done( function ( data ) {
    openModal('infoRequestModal');
    jQuery("#irm-text").val(data.text);
    if ( true == updateTos ){
      jQuery("#irm-to").val(data.prefettura);
    }
  }).fail( function ( err ) {
    alert('Si è verificato un errore');
    /*
      ██████  ███████ ██████  ██    ██  ██████
      ██   ██ ██      ██   ██ ██    ██ ██
      ██   ██ █████   ██████  ██    ██ ██   ███
      ██   ██ ██      ██   ██ ██    ██ ██    ██
      ██████  ███████ ██████   ██████   ██████
    */
    console.log('██'.repeat(10) + "\t" +  'EEEER!' + "\t" + '██'.repeat(10));
    console.log(err);
  });
}


function openModal( modalID ) {
  if ( null == window._currentModal ) {
    jQuery("#modal-fog").css('display', 'block');
    jQuery("#" + modalID).css('display', 'block');
    window._currentModal = modalID;
  }
  else {
    console.info('██'.repeat(10) + "\t" +  'modal already open.' + "\t" + '██'.repeat(10));
  }
}

function closeModal (modalID) {
  // logic?
  jQuery("#modal-fog").css('display', 'none');
  jQuery("#" + modalID).css('display', 'none');
  window._currentModal = null;
  window._currentEsecutore = null;
}
