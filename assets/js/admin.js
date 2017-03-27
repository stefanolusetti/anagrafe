$(document).ready(function() {

		aggiorna_select('stato')
		aggiorna_select('uploaded')
		aggiorna_select('is_digital')
		aggiorna_select('stmt_wl')
		aggiorna_select('protocollato')
		aggiorna_input('iscritti_at')
		aggiorna_input('iscritti_scadenza')
		aggiorna_input('iscritti_at')
		aggiorna_input('iscritti_prov_at')
		aggiorna_input('iscritti_prov_scadenza')
		aggiorna_input('dia_scadenza')
		aggiorna_input('protocollo_struttura')
		aggiorna_input('fascicolo_struttura')



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
		window.console.log ("aggiorna_select")
		}
	if  ( nomecampo == 'stato' &&(stato=='2')) {
	id_substring = id.substring(5)
		aggiorna_datepicker_change("iscritti_prov_at"+id_substring,2,"iscritti_prov_at")
		aggiorna_datepicker_change("iscritti_prov_scadenza"+id_substring,2,"iscritti_prov_scadenza")
		window.console.log(nomecampo + ":" + stato + ":" + id_substring)
		window.console.log ("aggiorna_select")
		}
	})


}

function aggiorna_input(nomecampo){
  $("input." + nomecampo).on('input change', function ( ) {
    campo = this;
    var options = {};
    name = $(this).attr('name');
    id = $(this).attr('id');
    stato = $(this).val();
    options[name] = stato;
    if ( window.console && 'function' === typeof window.console.log ) {
      window.console.log (name + ":" + stato + ":" + nomecampo );
      window.console.log ("aggiorna_input");
    }

    if ( ( nomecampo == 'iscritti_at') && ( stato != "" ) ) {
      id_substring = id.substring(11);
      var date_iscritti = $("input#"+"iscritti_at"+id_substring).datepicker("getDate");
      $("input#"+"iscritti_scadenza" + id_substring)
        .datepicker("setDate", new Date(date_iscritti.getFullYear()+1,date_iscritti.getMonth(), date_iscritti.getDate()));
      $("input#"+"iscritti_scadenza"+id_substring).change();
    }

    if ( ( nomecampo == 'iscritti_prov_at' )  && ( stato != "" ) ) {
      id_substring = id.substring(16)
      var date_iscritti_prov = $("input#"+"iscritti_prov_at"+id_substring).datepicker("getDate");
      $("input#"+"iscritti_prov_scadenza"+id_substring).datepicker("setDate", new Date(date_iscritti_prov.getFullYear()+1,date_iscritti_prov.getMonth(), date_iscritti_prov.getDate()));
      $("input#"+"iscritti_prov_scadenza"+id_substring).change();
    }

    timeoutId = setTimeout(function() {
      $.post("/admin/update", options);
    }, 1000);
  });
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
		window.console.log ("aggiorna_datepicker_change")
		}
		if((nomecampo == "iscritti_scadenza")) {
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(), d.getDate()))
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');
		window.console.log(nome_idcampo + ":" + flag)
		window.console.log ("aggiorna_datepicker_change")
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
		window.console.log ("aggiorna_datepicker_change")
		}
		if((nomecampo == "iscritti_prov_scadenza")) {
		$("input#"+nome_idcampo).datepicker("setDate", new Date(d.getFullYear()+1,d.getMonth(), d.getDate()))
		$("input#"+nome_idcampo).change();
		$('table.elenco tr td.smallbox .field').find("input#"+nome_idcampo).removeAttr('disabled');
		window.console.log(nome_idcampo + ":" + flag)
		window.console.log ("aggiorna_datepicker_change")
		}

}

		}


/*

function aggiorna_datepicker (nome_idcampo) {

$("input#"+nome_idcampo).change(
		function(){
		campo = this
		data = $(this).val();
		name = $(this).attr('name')
        var options = {}
        options[name] = data
		if (window.console && 'function' === typeof window.console.log) {
		window.console.log(name + ":" + data + ":" + options[name])
		window.console.log ("aggiorna_datepicker")
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

  */

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

		//$.post("/admin/update_protocollo", options);






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
	  $( "#dia_scadenza"+ nome).datepicker({dateFormat: 'dd/mm/yy',changeMonth: true,
      changeYear: true});


	  //aggiorna_input('protocollo_struttura')
	  //aggiorna_input('fascicolo_struttura')

	//aggiorna_datepicker('iscritti_at'+nome);
    //aggiorna_datepicker('iscritti_prov_at'+nome);
	//aggiorna_datepicker('iscritti_scadenza'+nome);
	//aggiorna_datepicker('iscritti_prov_scadenza'+nome);

	}
