$(document).ready(function() {
    aggiorna_select('uploaded')
    aggiorna_select('durc')
    aggiorna_select('protesti')
    aggiorna_select('antimafia')
    aggiorna_select('5bis')
    aggiorna_checkbox('pubblicato')
	
	
	$('.pec').each(function() {
		send_pec_pubblicazione(this);
		
		}
	
	)
		
function send_pec_pubblicazione(button) {
	
		$(button).click(function(){
		
		var options = {}
		//name = $(button).attr('name')
		//id = $(button).attr('id')
		//options[id] = name
        
        name = $(this).parents('tr').find('textarea').attr('name')
        data = $(this).parents('tr').find('textarea').val();
        options[name] = data
		
		
		
		//$.post("/merito/index.php/admin/costruisci_finestra",options);
		
		$('#finestra_pec').load("/merito/index.php/admin/costruisci_finestra",options);
	
		
		$('#finestra_pec').dialog("open");
		});
		
		
		
		
		$('#finestra_pec').dialog({
		
		
        modal: true,
        autoOpen: false,
		width:'500px',
        buttons: {
            "Chiudi": function() {
                $( '#finestra_pec' ).dialog( "close" );
                },
            'Invia PEC': function(){
				
				var options = {}
				options[name] = data
				
				$.post("/merito/index.php/admin/pec_pubblicazione",options, function(dati){
                if(dati == "MESSAGGIO_OK"){
					
					$('#finestra_messaggio').html('La mail è stata inviata correttamente');
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
					
					} else {
					$('#finestra_messaggio').html("Si sono verificati errori nell'invio della mail");
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
                   
                }
				})
                
				
				$( '#finestra_pec' ).dialog( "close" );
				
               
				
                }
				
            }
			
        });
		
			  
	
	}
	
	$('.spec').each(function() {
		send_pec_spubblicazione(this);
		
		}
	
	)
	
	$('.pecnp').each(function() {
		send_pec_non_pubblicazione(this);
		
		}
	
	)

	
	function send_pec_spubblicazione(button) {
	
		$(button).click(function(){
		
		var options = {}
		name = $(this).parents('tr').find('textarea').attr('name')
        data = $(this).parents('tr').find('textarea').val();
        options[name] = data
		
		window.console.log(options);
		
		$('#finestra_spec').load("/merito/index.php/admin/costruisci_finestra",options);
	
		
		$('#finestra_spec').dialog("open");
		});
		
		$('#finestra_spec').dialog({
		
		
        modal: true,
        autoOpen: false,
		width:'500px',
        buttons: {
            "Chiudi": function() {
                $( '#finestra_spec' ).dialog( "close" );
                },
            'Invia PEC': function(){
				
				var options = {}
				options[name] = data
				window.console.log(options);
				$.post("/merito/index.php/admin/pec_spubblicazione",options, function(dati){
                if(dati == "MESSAGGIO_OK"){
					
					$('#finestra_messaggio').html('La mail è stata inviata correttamente');
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
					
					} else {
					$('#finestra_messaggio').html("Si sono verificati errori nell'invio della mail");
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
                   
                }
				})
                
				
				$( '#finestra_spec' ).dialog( "close" );
				
                }
            }
        });
	
	}
	
	function send_pec_non_pubblicazione(button) {
	
		$(button).click(function(){
		
		var options = {}
		name = $(this).parents('tr').find('textarea').attr('name')
        data = $(this).parents('tr').find('textarea').val();
        options[name] = data
		
		window.console.log(options);
		
		$('#finestra_pec_np').load("/merito/index.php/admin/costruisci_finestra",options);
	
		
		$('#finestra_pec_np').dialog("open");
		});
		
		$('#finestra_pec_np').dialog({
		
		
        modal: true,
        autoOpen: false,
		width:'500px',
        buttons: {
            "Chiudi": function() {
                $( '#finestra_pec_np' ).dialog( "close" );
                },
            'Procedi': function(){
				
				var options = {}
				options[name] = data
				window.console.log(options);
				$.post("/merito/index.php/admin/pec_non_pubblicazione",options, function(dati){
                if(dati == "MESSAGGIO_OK"){
					
					$('#finestra_messaggio').html('La mail è stata inviata correttamente');
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
					
					} else {
					$('#finestra_messaggio').html("Si sono verificati errori nell'invio della mail");
					$('#finestra_messaggio').dialog({
							modal: true,
							autoOpen: true,
							width:'500px'
					})
                   
                }
				})
                
				
				$( '#finestra_pec_np' ).dialog( "close" );
				
                }
            }
        });
	
	}
	
	
	
    $('.unlock').each(function(){
        sblocca(this)
		
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log('applico_sblocca');
		}
		
    })
    $('table.elenco.admin').find('select').attr('disabled', 'disabled');
    $('table.elenco.admin').find('input').attr('disabled', 'disabled');
	 $('table.elenco.admin').find('textarea').attr('disabled', 'disabled');
    
    
    $('a.ext').each(function() {
        $(this).on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            window.open(this.href, '_blank');
        });
    });
    
    
})





	

function aggiorna_checkbox(nomecampo){
    $("[name="+ nomecampo +"]").change(function(){
        campo = this
        var options = {}
        name = $(this).val();
        stato = $(this).attr('checked') ? 1 : 0
        options[name] = stato
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log(name + ":" + stato+ ":" + campo)
		}
        $.post("/merito/index.php/admin/update", options, function(data){
                if(data == "OK"){
                    $(campo).parent('.field').css('opacity', '0.2')
                    $(campo).parent('.field').animate({opacity: 1}, 300, function(){})
                } else {
                    //alert('errore del server, record non aggiornato')
                }
        })
    })
	
	


}

function aggiorna_select(nomecampo){
    $("select." + nomecampo).change(function(){
        campo = this
        var options = {}
        name = $(this).attr('name')
		id = $(this).attr('id')
        stato = $(this).val()
        options[name] = stato
		
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log (name + ":" + stato )
		}
        $.post("/merito/index.php/admin/update", options, function(data){
                if(data == "OK"){
                    $(campo).parent('.field').css('opacity', '0.2')
                    $(campo).parent('.field').animate({opacity: 1}, 300, function(){})
                } else {
                    alert('errore del server, record non aggiornato')
                }
        })
	
    if  ( nomecampo == 'protesti' &&(options[name]!='2')) {
	id_substring = id.substring(9)
	
		if (options[name] =='3') {
		aggiorna_datepicker_change("protesti_scadenza"+id_substring,1,"protesti_scadenza")
		}
	}
	
	/*
	if ( nomecampo == 'durc' &&(options[name]!='3')) {
	id_substring = id.substring(5)
	
		if (options[name] =='2') {
			aggiorna_datepicker_change("durc_scadenza"+id_substring,1,"durc_scadenza")
			}
	
      }
	  
	  */
	
	if  ( nomecampo == 'antimafia' && ((options[name]!='3') || (options[name]!='4')))  {
	id_substring = id.substring(10)
	$.post("/merito/index.php/admin/check", options, function(option){
	if(option != "BLOCCATO")
			$('table.elenco tr td.smallbox .field').find('input#pubblicato_'+id_substring).removeAttr('disabled')
					})
					
		if (options[name] =='2') {
			aggiorna_datepicker_change("antimafia_scadenza"+id_substring,1,"antimafia_scadenza")
		}
	}
	
	
	
	
	
	
	})
	
	
}


function aggiorna_datepicker_change(nome_idcampo,flag,nomecampo){
		if (flag=="1")
		{
		d = new Date();
		if (nomecampo == "durc_scadenza"){
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear(),d.getMonth()+4, d.getDate()));
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');		
		}
		else if((nomecampo == "protesti_scadenza") || (nomecampo == "antimafia_scadenza")) {
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear(),d.getMonth()+6, d.getDate()))
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');
	
		}
		
		}
		
}



function aggiorna_datepicker (nome_idcampo) {
$("input#"+nome_idcampo).change(
		function(){
		campo = this
		data = $(this).val();
		name = $(this).attr('name')
        var options = {}
        options[name] = data
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log(name + ":" + data)
		}
		
		$.post("/merito/index.php/admin/update", options, function(data){
                if(data == "OK"){
                    $(campo).parent('.field').css('opacity', '0.2')
                    $(campo).parent('.field').animate({opacity: 1}, 300, function(){})
                } else {
                    //alert('errore del server, record non aggiornato')
                }
        })
   })
   
}

function sblocca(button){
    /*$(elemento).click(function(){
        $(this).unbind('click')*/
	$(button).click(function(){
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log('sblocca')
		}
	button=this
	var options = {}
	id = $(this).attr('id')
	name = $(this).attr('name')
	stato = $(this).val()
    options[id] = stato
	
	

		if (window.console && 'function' === typeof window.console.log) {
		window.console.log('sblocca')
		}
        $(this).parents('tr').find('select').removeAttr('disabled')
		
		$(this).parents('tr').find('input').removeAttr('disabled')
		
		$(this).parents('tr').find('textarea').removeAttr('disabled')
		
	
		
		$(this).html('blocca')
        $(this).attr('class', 'button lock')
        blocca(this)

		scadenza(name)
		
	})
}

function blocca(elemento){
    $(elemento).click(function(){
         $(this).unbind('click')
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log('blocca')
		}
		
		
		
	    var options = {}
        name = $(this).parents('tr').find('textarea').attr('name')
        data = $(this).parents('tr').find('textarea').val();
        options[name] = data
		
		
		
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log(name + ":" + data)
		}
		
		$.post("/merito/index.php/admin/update_note", options);
		
		
		
         
		 
		 
		 $(this).parents('tr').find('select').attr('disabled', 'disabled');
         $(this).parents('tr').find('input').attr('disabled', 'disabled');
		 $(this).parents('tr').find('textarea').attr('disabled', 'disabled');
         $(this).html('sblocca')
         $(this).attr('class', 'button unlock')
         sblocca(this)
		 
    })
}



function scadenza(nome) {
	$( "#durc_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	$( "#protesti_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	$( "#antimafia_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	aggiorna_datepicker('durc_scadenza'+nome);
    aggiorna_datepicker('protesti_scadenza'+nome);
	aggiorna_datepicker('antimafia_scadenza'+nome);
	
	
	}
	
	

	
/*	button_spec=this;

	$(button_spec).click(function(){
	$('#finestra_spec').dialog("open");
				var options_button = {}
				name_button = $(button_spec).parents('tr').find('textarea').attr('id')
				data_button = $(button_spec).parents('tr').find('textarea').val();
				options_button[name_button] = data_button	
				
	
	$.post("/merito/index.php/admin/apri_finestra",options_button);
	
	return false;
	})
	
	$('#finestra_spec').dialog({
        modal: true,
        autoOpen: false,
        buttons: {
            "Chiudi": function() {
                $( this ).dialog( "close" );
                },
            'Procedi': function(){
				var options = {}
				name = $(button_spec).parents('tr').find('textarea').attr('id')
				data = $(button_spec).parents('tr').find('textarea').val();
				options[name] = data
				window.console.log(name + ":" + data)
                $.post("/merito/index.php/admin/pec_spubblicazione",options);
                $( this ).dialog( "close" );
                }
            }
        });
	
	
	
	
	*/


$(function() {
// initialize tooltip
$(".field_antimafia .field label").tooltip({

   // tweak the position
   offset: [700, 0],

   // use the "slide" effect
   effect: 'slide',
   
   position: 'top center'

// add dynamic plugin with optional configuration for bottom edge
});
});