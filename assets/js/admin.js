$(document).ready(function() {
	
	aggiorna_select('stato')
	aggiorna_select('uploaded')
	aggiorna_select('is_digital')
	aggiorna_select('stmt_wl')
	aggiorna_select('protocollato')
	aggiorna_input('protocollo_struttura')
	aggiorna_input('fascicolo_struttura')

	
	/*
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
	
	*/
	
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





	/*

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

*/


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
        $.post("/admin/update", options, function(data){
                if(data == "OK"){
                    $(campo).parent('.field').css('opacity', '0.2')
                    $(campo).parent('.field').animate({opacity: 1}, 300, function(){})
                } else {
                    alert('errore del server, record non aggiornato')
                }
        })
	
    if  ( nomecampo == 'stato' &&(stato=='1')) {
	id_substring = id.substring(5)
		aggiorna_datepicker_change("iscritti_at"+id_substring,1,"iscritti_at")
		aggiorna_datepicker_change("iscritti_scadenza"+id_substring,1,"iscritti_scadenza")
		window.console.log(nomecampo + ":" + stato + ":" + id_substring)
		}
	if  ( nomecampo == 'stato' &&(stato=='2')) {
	id_substring = id.substring(5)
		aggiorna_datepicker_change("iscritti_prov_at"+id_substring,2,"iscritti_prov_at")
		aggiorna_datepicker_change("iscritti_prov_scadenza"+id_substring,2,"iscritti_prov_scadenza")
		window.console.log(nomecampo + ":" + stato + ":" + id_substring)
		}
	})
	
	
}

function aggiorna_input(nomecampo){	

	
 $("input").on('input change',function(){
        campo = this
        var options = {}
        name = $(this).attr('name')
		id = $(this).attr('id')
        stato = $(this).val()
        options[name] = stato
		
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log (name + ":" + stato )
		}
		
		
		timeoutId = setTimeout(function() {
        // Runs 1 second (1000 ms) after the last change    
        $.post("/admin/update_protocollo", options);
		}, 1000);
		
		
		
       
	
 
	})
	




}
	
	
	



function aggiorna_datepicker_change(nome_idcampo,flag,nomecampo){
		if (flag=="1")
		{
		d = new Date();
		if (nomecampo == "iscritti_at"){
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear(),d.getMonth(), d.getDate()));
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');	
		window.console.log(nome_idcampo + ":" + flag)		
		}
		if((nomecampo == "iscritti_scadenza")) {
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(), d.getDate()))
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');
		window.console.log(nome_idcampo + ":" + flag)
		}
		}
		
		if (flag=="2")
		{
		d = new Date();
		if (nomecampo == "iscritti_prov_at"){
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear(),d.getMonth(), d.getDate()));
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');	
		window.console.log(nome_idcampo + ":" + flag)		
		}
		if((nomecampo == "iscritti_prov_scadenza")) {
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(), d.getDate()))
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
		
		$.post("/admin/update", options, function(data){
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
		
	
		
		$(this).html('Salva modifiche')
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
        name = $(this).parents('tr').find('input').attr('name')
        data = $(this).parents('tr').find('input').val();
        options[name] = data
		
		
		
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log(name + ":" + data)
		}
		
		$.post("/admin/update_protocollo", options);
		
		
		
         
		 
		 
		 $(this).parents('tr').find('select').attr('disabled', 'disabled');
         $(this).parents('tr').find('input').attr('disabled', 'disabled');
		 $(this).parents('tr').find('textarea').attr('disabled', 'disabled');
         $(this).html('Attiva modifiche')
         $(this).attr('class', 'button unlock')
         sblocca(this)
		 
    })
}



function scadenza(nome) {
	$( "#iscritti_at"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	$( "#iscritti_prov_at"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	$( "#iscritti_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	$( "#iscritti_prov_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});
	aggiorna_datepicker('iscritti_at'+nome);
    aggiorna_datepicker('iscritti_prov_at'+nome);
	aggiorna_datepicker('iscritti_scadenza'+nome);
	aggiorna_datepicker('iscritti_prov_scadenza'+nome);
	
	}
	
	
/*
	



*/