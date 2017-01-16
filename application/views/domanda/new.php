<?php echo form_open('domanda/nuova') ?>

	<h2>Anagrafica del richiedente</h2>
	<?php 
	f_text("titolare_nome", "Nome e Cognome*", array('input' => array('class'=>'required maxlen')));
	f_text("titolare_comune_nascita", "Comune di nascita*", array('input' => array('class'=>'required maxlen')));
    f_select('titolare_prov_nascita', 'Provincia di nascita*', opzioni_provincie(), array('input' => array('class'=>'required maxlen')));
	f_text("titolare_nazione_nascita", "Nazione di nascita (se diversa da Italia)"); 
	f_text("titolare_data_nascita", "Data di nascita (nel formato gg/mm/aaaa)*", array('input' => array('class'=>'required data'))); 
	f_text("titolare_comune_residenza", "Comune di residenza*", array('input' => array('class'=>'required maxlen')));
	f_select('titolare_prov_residenza', 'Provincia di residenza*', opzioni_provincie(), array('input' => array('class'=>'required maxlen')));
	f_text("titolare_via_residenza", "Via/Piazza*", array('input' => array('class'=>'required maxlen')));
	?>
	
	<h2>Anagrafica dell'azienda</h2>
	<?php
	f_select("tipo_rappresentanza", "Tipo di rappresentanza", array('Legale rappresentante' => 'Legale rappresentante', 
																	'Procuratore' => 'Procuratore' ));
	f_text("ragione_sociale", "Ragione sociale*", array('input' => array('class'=>'required maxlen')));
	f_text("anno_inizio", "Data costituzione società (nel formato gg/mm/aaaa)*", array('input' => array('class'=>'data')));
	f_select("forma_giuridica", "Forma giuridica dell'impresa", array(
																	'Datore di Lavoro' => 'Datore di lavoro',
																	'Lavoratore autonomo' => 'Lavoratore autonomo',
																	'Gestione Separata - Committente/Associante' => 'Gestione Separata - Committente/Associante',
																	'Gestione separata - Titolare di reddito di lavoro autonomo di arte e professione' => 'Gestione separata - Titolare di reddito di lavoro autonomo di arte e professione'
																	
																	));
																	
																	
	f_select("tipo_impresa", "Tipo società", array(
																	'Società per azioni' => 'Società per azioni',
																	'Società in accomandita per azioni' => 'Società in accomandita per azioni',
																	'Società a responsabilità limitata' => 'Società a responsabilità limitata',
																	'Società cooperativa' => 'Società cooperativa',
																	'Società semplice' => 'Società semplice',
																	'Società in nome collettivo' => 'Società in nome collettivo',
																	'Società in accomandita semplice' => 'Società in accomandita semplice',
																	'Società consortile' => 'Società consortile',
																	'Impresa individuale' => 'Impresa individuale',
																	'Altro' => 'Altro',
																	
																	), array('input' => array('class'=>'required')));
																	
	f_text("tipo_impresa_altri", "se selezionato Altro specificare", array('input' => array('class'=>'controlla_tipo_impresa')));																
	?>
	<h4>Sede legale</h4>
	<?php
	f_text("sl_via", "Via*", array('input' => array('class'=>'required maxlen')));
	f_text("sl_civico", "Civico*", array('input' => array('class'=>'required maxlen')));
	f_text("sl_cap", "CAP*", array('input' => array('class'=>'required maxlen')));
	f_text("sl_comune", "Comune*", array('input' => array('class'=>'required maxlen')));
    f_select('sl_prov', 'Provincia*', opzioni_provincie(), array('input' => array('class'=>'required maxlen')));
	f_text("sl_tel", "Telefono*", array('input' => array('class'=>'required maxlen')));
	f_text("sl_mobile", "Tel. mobile");
	f_text("sl_fax", "Fax");
	f_text("sl_piva", "Partita IVA*", array('input' => array('class'=>'required piva')));
	f_text("sl_cf", "Codice Fiscale*", array('input' => array('class'=>'required cf')));
	f_text("sl_email", "Casella e-mail*", array('input' => array('class'=>'required email')));
	f_text("sl_pec", "Casella PEC (inserire email valida per l'invio del modulo da firmare digitalmente)*", array('input' => array('class'=>'required email')));
	?>
	<h4>
		Sede operativa<br/>
		<a id="operativa">
    	[copia i dati della sede legale]
    	</a>
	</h4>
	<?php
	f_text("so_via", "Via*", array('input' => array('class'=>'required maxlen')));
	f_text("so_civico", "Civico*", array('input' => array('class'=>'required maxlen')));
	f_text("so_cap", "CAP*", array('input' => array('class'=>'required maxlen')));
	f_text("so_comune", "Comune*", array('input' => array('class'=>'required maxlen')));
    f_select('so_provincia', 'Provincia*', opzioni_provincie(), array('input' => array('class'=>'required maxlen')));
	?>
	<h4>CCNL Applicato</h4>
	<?php
	f_select('tipo_contratto', 'Contratto prevalente', array('Edilizia' => 'Edilizia', 'Altri settori' => 'Altri settori'));
	f_text("tipo_contratto_altri", "se selezionato Altri, indicare il settore", array('input' => array('class'=>'controlla_contratto')));
	?>
	<h4>INAIL</h4>
	<?php
	f_text("pos_inail_n", "Posizione n.*", array('input' => array('class'=>'required maxlen')));
	f_text("pos_inail_sede", "Ufficio*", array('input' => array('class'=>'required maxlen')));
	?>
	<h4>INPS</h4>
	<?php
	f_text("pos_inps_n", "Posizione n.*", array('input' => array('class'=>'required inps')));
	f_text("pos_inps_sede", "Ufficio*", array('input' => array('class'=>'required maxlen')));
	?>
	<h4>Cassa edile</h4>
	<?php
	
	f_text("pos_cassa_denominazione", "Denominazione*",	array('input' => array('class'=>'required maxlen')));
	f_text("pos_cassa_n", "Posizione n.*", array('input' => array('class'=>'required maxlen')));
	f_text("pos_cassa_sede", "Ufficio*", array('input' => array('class'=>'required maxlen')));
	
	?>
	<h4>Dipendenti</h4>
	<?php
	f_text("addetti_n_dipendenti", "Numero di dipendenti*", array('input' => array('class'=>'dipendenti')));
	f_text("addetti_n_socilav", "Numero di soci lavoratori", array('input' => array('class'=>'dipendenti')));
	f_text("addetti_n_artigiani", "Numero di soci artigiani", array('input' => array('class'=>'dipendenti')));
	?>
	<h4>Iscrizione al Registro Imprese presso la C.C.I.A.A.</h4>
	<?php
	f_text("iscrizione_cciaa_sede", "Ufficio*", array('input' => array('class'=>'required maxlen')));
	f_text("iscrizione_cciaa_n", "Numero di iscrizione*", array('input' => array('class'=>'required maxlen')));
	f_text("rea_n", "Numero di R.E.A.*", array('input' => array('class'=>'required maxlen')));
	?>
	
	<?php if (!$antimafias) { ?>
    <div class="field_antimafia">
	<h2> Anagrafiche dei componenti<br><br>
	Inserire i dati richiesti per tutti i soggetti previsti dal DLgs. n. 159/2011 art.85 e ss.mm.ii.</h2>
	
	
	<div class="field">
		<label  title =" <?php 
	echo '<p>Per tutti i tipi di imprese, società, associazioni, anche prive di personalità
	giuridica, la documentazione antimafia deve sempre riferirsi al <b>direttore tecnico</b>
	ove previsto ed inoltre ai <b>membri del collegio sindacale</b> o, nei casi di cui all’art
	2477 c.c, al <b>sindaco o ai soggetti che svolgono compiti di vigilanza</b> di cui all’art
	6 comma 1 lettera b) del d.lgs 231 del 8 giugno 2001.
	In aggiunta poi, sono soggetti a verifica le cariche indicate a fianco di ciascun tipo
	di impresa.</p>';
	echo'<table>
	<tbody>
	<tr>
	<td>
	1-Imprese individuali
	</td>
	<td>
	Titolare
	</td>
	</tr>
	<tr>
	<td>
	2-Per le Società di capitali, anche consortili, le Società cooperative,Consorzi cooperativi, Consorzi di cui al Libro V, Titolo X, Capo II, Sezione II del
	c.c, Associazioni e società di qualunque tipo, anche prive di personalità giuridica
	</td>
	<td>
	Legale rappresentante,ed eventuali altri Componenti organo di amministrazione, Ciascuno dei consorziati che nei consorzi e nelle società consortili detenga una
	partecipazione superiore al 10% oppure detenga una partecipazione inferiore al 10% e che abbia stipulato un patto parasociale riferibile a una partecipazione
	pari o superiore al 10%,ed ai Soci o consorziati per conto dei quali le società consortili o i consorzi operino in modo esclusivo nei confronti della pubblica
	amministrazione
	</td>
	</tr>
	<tr>
	<td>
	3-Società di capitali
	</td>
	<td>
	Socio di maggioranza in caso di società con un numero di soci pari o inferiore a quattro, ovvero al Socio in caso di società con socio
	unico
	</td>
	</tr>
	<tr>
	<td>
	4-Consorzi di cui l’Art 2602 del c.c e per i Gruppi europei di interesse economico(GEIE)
	</td>
	<td>
	Chi ne ha la rappresentanza ed agli Imprenditori o Società consorziate
	</td>
	</tr>
	<tr>
	<td>
	5-Società semplice e in nome collettivo
	</td>
	<td>
	Tutti i soci
	</td>
	</tr>
	<tr>
	<td>
	6-Società in accomandita semplice
	</td>
	<td>
	Soci accomandatari
	</td>
	</tr>
	<tr>
	<td>
	7-Società estere con sede secondaria in territorio statale (Art 2508 c.c)
	</td>
	<td>
	Coloro che rappresentano stabilmente nel territorio dello Stato
	</td>
	</tr>
	<tr>
	<td>
	8-Società costituite all’estero,prive di una sede secondaria con rappresentanza stabile nel territorio statale
	</td>
	<td>
	Coloro che esercitano poteri di amministrazione,di rappresentanza o di direzione dell’impresa
	</td>
	</tr>
	<tr>
	<td>
	9-Raggruppamenti temporanei di imprese
	</td>
	<td>
	Imprese costituenti il raggruppamento anche se aventi sede all’estero,di rappresentanza o di direzione dell’impresa
	</td>
	</tr>
	<tr>
	<td>
	10-Società personali
	</td>
	<td>
	Soci persone fisiche delle società personali o di capitali che ne siano socie
	</td>
	</tr>
		<tr>
	<td>
	11-Società capitali concessionarie nel settore dei giochi pubblici di cui alle lettere b) e c) del comma 2 dell’ Art 85 del D.Lgs 159/2011
	</td>
	<td>
	Oltre ai Soggetti indicati nei precedenti punti 2 e 3, ai Soci persone fisiche che detengono,anche indirettamente,una partecipazione al capitale o al patrimonio
	superiore al 2%,nonché ai Direttori generali e Soggetti responsabili delle sedi secondarie o delle stabili organizzazioni in
	Italia di soggetti non residenti. Nell’ipotesi in cui i soci persone fisiche detengano la partecipazione superiore alla predetta
	soglia mediante altre società di capitali,la documentazione deve riferirsi anche al legale rappresentante e agli eventuali componenti dell’organo di amministrazione
	della società socia,alle persone fisiche che,direttamente o indirettamente,controllano tale società,nonché ai direttori generali e ai
	soggetti responsabili delle sedi secondarie o delle stabili organizzazioni in Italia di soggetti non residenti. La documentazione
	di cui al periodo precedente deve riferirsi anche al coniuge non separato
	</td>
	</tr>
	</tbody>
	</table>'
	;?>" >Per avere maggiori informazioni</label>
	</div>
	
	
	<h4>
	
	<div class="container">

	<a href="#" class="add">Aggiungi anagrafica</a>|<a href="#" class="remove">Elimina anagrafica</a> | <a href="#" class="reset">Cancella tutto</a><br>
    
	
	<div class="inputs">
   
    </div>
	
	</div>
	
     </h4>
	 
	</div>
	 <?php } 
	  
	 
	 else
     { ?>
	 
	 <h2>Anagrafiche dei componenti</h2>
	
	<div class="field">
		<label>Titolare / soci delle s.n.c. / soci accomandatari delle s.a.s. /amministratori muniti di poteri di rappresentanza/ socio unico (per le società di capitali con unico socio)/socio di maggioranza (per le società di capitali con meno di quattro soci) è/sono:
		</label>
	</div>
	<?php
	 $i=0;
	 foreach ($antimafias as $antimafia) {
	 
	 ?>
	
	 <h2>Anagrafica del componente <?php echo $antimafia['antimafia_nome'];?></h2>
	 <div class="field">
	 <?php
	 echo form_label('Nome e Cognome*', 'antimafia_nome');
	 echo form_input ('antimafia_nome',$antimafia['antimafia_nome']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Comune di Nascita*', 'antimafia_comune_nascita');
	 echo form_input ('antimafia_comune_nascita',$antimafia['antimafia_comune_nascita']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Data di nascita (nel formato GG/MM/AAAA)*', 'antimafia_data_nascita');
	 echo form_input ('antimafia_data_nascita',format_date($antimafia['antimafia_data_nascita']));
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Codice Fiscale*', 'antimafia_cf');
	 echo form_input ('antimafia_cf',$antimafia['antimafia_cf']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Carica sociale*', 'antimafia_carica_sociale');
	 echo form_input ('antimafia_carica_sociale',$antimafia['antimafia_carica_sociale']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Residente a', 'antimafia_comune_residenza');
	 echo form_input ('antimafia_comune_residenza',$antimafia['antimafia_comune_residenza']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Provincia di residenza', 'antimafia_provincia_residenza');
	 echo form_input ('antimafia_provincia_residenza',$antimafia['antimafia_provincia_residenza']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Via di residenza', 'antimafia_via_residenza');
	 echo form_input ('antimafia_via_residenza',$antimafia['antimafia_via_residenza']);
	 ?>
	 </div>
	 <div class="field">
	 <?php
	 echo form_label('Civico', 'antimafia_civico_residenza');
	 echo form_input ('antimafia_civico_residenza',$antimafia['antimafia_civico_residenza']);
	 ?>
	 </div>
	 <?php
	  $i++;
	 }
	
	 }
	 
	 
	 ?>
	
	
	
	
	
	<h4>Attestazione SOA</h4>
	<div class="field">
		<label>Specificare l'eventuale possesso di attestazione SOA, ed in caso affermativo quale/i</label>
		<div id="errorbox_soas"></div>
	</div>
	<?php 
	f_select('soa_sino', 'Possesso attestazione SOA', array('Off' => 'No', 'Yes' => 'sì'));
	?>
	<?php 
	foreach ($soas as $soa)
	{
		f_select("soa[{$soa['id']}]", "{$soa['codice']} - {$soa['denominazione']}", soa_options(), array('input' => array('class' => 'soas')));
	}
	?>
	<h4>Codice identificativo ATECO</h4>
	<div class="field">
		<div class="label">Specificare uno o più codici:*</div>
		<div id="errorbox_ateco">
		    <?php mostra_errore('ateco_sino'); ?>
		</div>
	</div>
	<h4>F-Costruzioni</h4>
	<?php 
	foreach ($atecos as $ateco)
	{
		if ($ateco['codice']=="90.03.02"){ ?>
		<h4>R-Attività artistiche, sportive, di intrattenimento e divertimento</h4>
		<?php
		}
	 f_checkbox("ateco[{$ateco['id']}]", "{$ateco['codice']} - {$ateco['denominazione']}", array('input' => array('class' => 'ateco')));
		
	}
	?>
	<h2>Dichiara*</h2>
	<?php
	f_checkbox('okdurc_sino', 'di essere in regola con l’assolvimento degli obblighi di versamento dei contributi stabiliti dalle vigenti disposizioni in materia di DURC (ai sensi del D.M. 24 ottobre 2007, “Documento Unico di regolarità contributiva”)', 
				array('input' => array('class'=>'required')));
	f_checkbox('noprotesti_sino', 'di non aver subito protesti cambiari e/o di assegni nell’ultimo quinquennio (ai sensi degli artt. 68-73 legge cambiaria);',
				array('input' => array('class'=>'required')));
	f_checkbox('antimafia_sino', 'che nei propri confronti non sussistono le cause di decadenza, di sospensione o di divieto di cui all’art. 67 del D.Lgs. 6 settembre 2011, n. 159);',
				array('input' => array('class'=>'required')));
	f_checkbox('ateco_sino', 'di essere in possesso dei Codici Identificativi Ateco relativi alla lettera F e/o R, di cui alla “Tabella dei titoli a sei cifre della classificazione delle attività economiche Ateco 2007”, pubblicati nel sito dell’ISTAT',
				array('input' => array('class'=>'required')));
	?>
	<h2>Si impegna*</h2>
	<?php
	f_checkbox('sopralluoghi_sino', 'a garantire, durante l’esecuzione dei lavori, l’accesso e lo svolgimento dei sopralluoghi da parte degli organismi paritetici di settore presenti sul territorio ove si svolgono i lavori stessi, ai sensi dell’art. 51, comma 6 del D.lgs. 81/2008 e s.m.i. e dell’art. 13, comma 2 della L.R. 11/2010;',
				array('input' => array('class'=>'required')));
	f_checkbox('sico_sino', 'a trasmettere la notifica preliminare agli enti competenti tramite il sistema informatico SICO ai sensi dell’allegato 1) parte integrante alla deliberazione di Giunta regionale n.  637/2011.',
				array('input' => array('class'=>'required')));
	f_checkbox('iscrizionecassa_sino', 'al rispetto, fermo restando gli accordi posti in essere nella regione Emilia-Romagna, degli accordi territoriali ed in particolare all’obbligo dell’iscrizione alla Cassa Edile territorialmente competente rispetto all’ubicazione del cantieri',
				array('input' => array('class'=>'required')));
	f_checkbox('ccnledilizia_sino', 'ad applicare, per le lavorazioni previste nell’ambito della sfera di applicazione del CCNL dell’edilizia, integralmente la contrattazione collettiva dell’edilizia',
				array('input' => array('class'=>'required')));
	f_checkbox('ccnlaltro_sino', 'ad applicare, per le lavorazioni non comprese nell\'ambito della sfera dell’edilizia, il CCNL corrispondente, siglato dalle organizzazioni sindacali confederali maggiormente rappresentative sul piano nazionale',
				array('input' => array('class'=>'required')));
	?>
	<h4>Informativa privacy</h4>
	<div class="field fixed">
		<? $this->load->view( "pages/privacy" ); ?>
	</div>
	<?php
	f_text("luogo_firma", "Luogo*", array('input' => array('class'=>'required maxlen')));
	f_text("data_firma", "Data*", array('input' => array('class'=>'required data')));
	?>
	
	
	<div class="submit">
		<input type="submit" name="submit" value="Genera richiesta" />
	</div>
	
</form>