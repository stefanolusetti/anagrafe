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
    else if ( 'proc-request' == action ) {
      requestProcModal(true);
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
    var formData = new FormData(document.getElementById('irm'));
    jQuery(".loading." + window._currentModal).addClass('active');
    jQuery.ajax({
        url: '/admin/ajax_irm',
        type: 'POST',
        data: formData,
        success: function (data) {
          jQuery(".loading." + window._currentModal + " .inner").html(data.msg);
          jQuery("#" + window._currentEsecutore + "-dia").addClass('alreadysent');
          window.setTimeout(closeModal, 2000, window._currentModal);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
  });


  jQuery("#psm-template").on('change', function ( e ) {
    window._currentTemplate = jQuery(e.target).val();
    requestProcModal(true);
  });

  jQuery("#psm-no").on('click', function() {
    closeModal('procSendModal');
  });

  jQuery("#psm-si").on('click', function() {
    var formData = new FormData(document.getElementById('psm'));
    jQuery(".loading." + window._currentModal).addClass('active');
    jQuery.ajax({
        url: '/admin/ajax_irm',
        type: 'POST',
        data: formData,
        success: function (data) {
          jQuery(".loading." + window._currentModal + " .inner").html(data.msg);
          jQuery("#" + window._currentEsecutore + "-oe").addClass('alreadysent');
          window.setTimeout(closeModal, 2000, window._currentModal);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
  });

  jQuery("#modal-fog").on('click', function() {
    closeModal(window._currentModal);
  });



  jQuery( "#irm-to" ).on( "keydown", function( event ) {
    if ( event.keyCode === $.ui.keyCode.TAB &&
      jQuery( this ).autocomplete( "instance" ).menu.active ) {
        event.preventDefault();
      }
    }).autocomplete({
      minLength: 0,
      focus: function() {
        // prevent value inserted on focus
        return false;
      },
      source: function( request, response ) {
        // delegate back to autocomplete, but extract the last term
        response( $.ui.autocomplete.filter(
          window._pec_list, extractLast( request.term ) ) );
      },
      select: function( event, ui ) {
        var terms = split( this.value );
        // remove the current input
        terms.pop();
        // add the selected item
        terms.push( ui.item.value );
        // add placeholder to get the comma-and-space at the end
        terms.push( "" );
        this.value = terms.join( ", " );
        return false;
      }
  });
});
function split( val ) {
  return val.split( /,\s*/ );
}

function extractLast( term ) {
   return split( term ).pop();
}

function requestProcModal(updateTos) {
  var type = 1;
  // Initialize templates, email address, etc..
  var t = jQuery("#psm-template").val();
  jQuery.ajax({
    url: '/admin/mailtemplate/' + window._currentEsecutore + '/' + type + '/' + t
  }).done( function ( data ) {
    if ( 1 == type && data.info.avvio_proc_oe ) {
      jQuery("#psm-warning").html('<div class="action-message error">Attenzione, la comunicazione di avvio istruttoria è già stata inviata.</div>');
    }
    else {
      jQuery("#psm-warning").html('');
    }
    openModal('procSendModal');
    //jQuery("#psm-text").val(data.text);
    jQuery("#psm-subject").val(data.subject);
    if ( true == updateTos ) {
      if ( data.to ) {
        jQuery("#psm-to").val(data.to);
      }
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

function requestInfoModal(updateTos) {
  var type = 0;
  // Initialize templates, email address, etc..
  jQuery.ajax({
    url: '/admin/mailtemplate/' + window._currentEsecutore + '/' + type + '/' + window._currentTemplate
  }).done( function ( data ) {
    if ( 0 == type && "1" == data.info.avvio_proc_dia ) {
      jQuery("#irm-warning").html('<div class="action-message error">Attenzione, la comunicazione di avvio istruttoria è già stata inviata.<br>Reinviandola di nuovo, il campo scadenza sarà aggiornato al <strong>' + data.info.dia_scadenza + '</strong>.</div>');
    }
    else {
      jQuery("#irm-warning").html('');
    }
    openModal('infoRequestModal');
    //jQuery("#irm-text").val(data.text);
    jQuery("#irm-subject").val(data.subject);
    if ( true == updateTos ) {
      if ( data.to ) {
        jQuery("#irm-to").val(data.to + ', ');
      }
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
    jQuery("#modal-fog").css({ display: 'block', opacity: 0 });
    jQuery("#" + modalID).css({ display: 'block', opacity: 0 });

    jQuery("#modal-fog, #" + modalID).animate({ opacity: 1 }, 250, function(){
      jQuery(".loading." + modalID).removeClass('active');
      window._currentModal = modalID;
      jQuery(".esid").val(window._currentEsecutore);
    });
  }
  else {
    console.info('██'.repeat(10) + "\t" +  'modal already open.' + "\t" + '██'.repeat(10));
  }
}
function closeModal (modalID) {
  // logic?
  jQuery("#modal-fog, #" + modalID).animate({ opacity: 0 }, 250, function(){
    jQuery("#modal-fog").css('display', 'none');
    jQuery("#" + modalID).css('display', 'none');
    jQuery(".loading." + modalID + " .inner").html('Invio in corso<hr />Attendere prego...');
    window._currentModal = null;
    window._currentEsecutore = null;
  });
  jQuery(".esid").val('');
}
