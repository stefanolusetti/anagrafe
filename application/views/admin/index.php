<ul class="submenu">
    <li class="selected"> Istruttoria domande </li>
<?php if(is_admin()): ?>
	<li>
        <a href="<?php echo site_url("/admin/caricamento_esecutore"); ?>">Caricamento esecutore</a>
    </li>
<?php endif; ?>

</ul>

<div id="searchbar">
    <?php
    $legenda = legenda();
    $attr = array('method' => 'get');
    echo form_open('admin/index', $attr) ?>

        <div class="fieldarea">
            <?php f_text('codice_istanza', 'ID', array('input' => array('size' => '15'))); ?>
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '35'))); ?>
		</div>
		<div class="fieldarea">
            <?php f_text('partita_iva', 'P. IVA', array('input' => array('size' => '15'))); ?>
            <?php f_text('codice_fiscale', 'Codice Fiscale', array('input' => array('size' => '20'))); ?>
		</div>
        <div class="fieldarea">
			<div class="field">
			<?php
			echo form_label('Stato', 'stato');
			$options =array(''=>'-','1'=>'Iscritti','2'=>'Iscritti provvisoriamente','0'=>'In richiesta');
			$get_stato = $this -> input -> get('stato');
			echo form_dropdown ('stato',$options,($get_stato===false) ? '' : $get_stato, 'id="stato"');
			?>
			</div>

			<div class="field">
			<?php
			echo form_label('Carta di identità', 'uploaded');
			$options = array(''=>'-','0'=>'No','1'=>'Si');
			$get_uploaded = $this -> input -> get('uploaded');
			echo form_dropdown ('uploaded',$options,($get_uploaded===false) ? '' : $get_uploaded, 'id="uploaded"');
			?>
			</div>
			<div class="field">
			<?php
			echo form_label('Tipo di pratica', 'is_digital');
			$options = array(''=>'-','0'=>'Cartacea','1'=>'Digitale');
			$get_is_digital = $this -> input -> get('is_digital');
			echo form_dropdown ('is_digital',$options,($get_is_digital===false) ? '' : $get_is_digital, 'id="is_digital"');
			?>
			</div>
			<!--
			<div class="field">
			<?php
			echo form_label('Stato integrazioni', 'stato');
			$options = array(''=>'-','0' => 'Non richieste', '1' => 'In attesa','2'=>'In valutazione','3'=>'Integrazione accettata','4'=>'Integrazione rifiutata');
			$get_integrazioni = $this -> input -> get('per_page');
			echo form_dropdown ('',$options,($get_integrazioni===false) ? '' : $get_integrazioni, 'id="integrazioni"');
			?>
			</div>
			-->
			<div class="field">
			<?php
			echo form_label('White list', 'stmt_wl');
			$options = array(''=>'-','0'=>'No','1'=>'Si');
			$get_white_list = $this -> input -> get('stmt_wl');
			echo form_dropdown ('stmt_wl',$options,($get_white_list===false) ? '' : $get_white_list, 'id="stmt_wl"');
			?>
			</div>
			<div class="field">
			<?php
			echo form_label('Mostra', 'mostra');
			$options = array('25'=>'25','50'=>'50','100'=>'100','500'=>'500');
			$get_mostra = $this -> input -> get('mostra');
			if ($get_mostra=='')
			$get_mostra=$this -> input -> get('per_page');
			echo form_dropdown ('mostra',$options,($get_mostra===false) ? '' : $get_mostra,'id="mostra"');
			?>
			</div>
			<div class="field">
                <label>&nbsp;</label>
                <?php echo form_submit('submit', 'Applica filtro'); ?>
            </div>
       </div>
    </form>
</div>
<? echo form_open('admin/update') ?>
<table class="elenco admin">
	<tr>
	    <th class="id"></th>
        <th><?php //query_link('codice_istanza', "Ordina imprese"); ?></th>
        <th></th>
        <th colspan="3"></th>
	</tr>
<?php
/*
██       ██████   ██████  ██████
██      ██    ██ ██    ██ ██   ██
██      ██    ██ ██    ██ ██████
██      ██    ██ ██    ██ ██
███████  ██████   ██████  ██
*/
foreach ($statements as $item) {
?>
  <tr>
	   <td class="id">
<?php
if ( '1' == $item['is_digital'] ) {
  printf(
    '<a class="button ext" href="%s">Scarica PDF</a>',
    site_url( array( 'admin', 'view', $item['ID'] ) )
  );
  if ( '1' == $item['uploaded'] ) {
    printf(
      '<a class="button ext" href="%s">Scarica Carta di identità</a>',
      base_url( array( 'uploads', "CI_" . $item['codice_istanza'] . ".pdf" ) )
    );
  }

  printf(
    '<a class="button green" data-action="info-request" data-item="%s">Richiesta Integrazioni</a>',
    $item['ID']
  );

  /*
  if ( '2' == $item['stato'] ) {
    printf(
      '<a class="button green" href="%s">Visualizza integrazioni</a>',
      site_url( array( 'admin', 'view', $item['ID'] ) )
    );
  }
  else {
    printf(
      '<a class="button green" href="%s">Richiedi integrazioni</a>',
      site_url( array( 'admin', 'view', $item['ID'] ) )
    );
  }
  */
}
?>
		<label for="unlock_<?php echo $item['ID']; ?>"><a class="button unlock" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "button[{$item['ID']}]"; ?>">Attiva modifiche</a></label>

		</td>

		<?php if ($item['is_digital']=='0') { ?>
		 <td>
		     <?php echo "<b>"."RAGIONE SOCIALE : "."</b>";?> <?php echo $item['ragione_sociale']; ?><br/>
            <?php echo "<b>"."PARTITA IVA : "."</b>";?> <?php echo $item['partita_iva']; ?><br/>
             <?php echo "<b>"."CODICE FISCALE : "."</b>";?> <?php echo $item['codice_fiscale']; ?><br/>
			<?php echo "<b>"."SEDE LEGALE : "."</b>";?> <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'] ;?></br><br/>
		    <?php echo "<b>"."STATO PRATICA : "."</b>";?> <?php
			if ($item['stato']=='0')
			echo "In richiesta";
			else if ($item['stato']=='1')
			echo "Iscritto";
			else
			echo "Iscritto provvisoriamente"
			?>

			</td>


		<?php
		}
		else { ?>

			<td>
			<?php echo "<b>".$item['codice_istanza']."</b>"; ?><br/><br/>
			<?php echo "<b>"."RICHIEDENTE : "."</b>";?> <?php echo $item['titolare_nome'].' '.$item['titolare_cognome']; ?><br/>
		    <?php echo "<b>"."RAGIONE SOCIALE : "."</b>";?> <?php echo $item['ragione_sociale']; ?><br/>
            <?php echo "<b>"."PARTITA IVA : "."</b>";?> <?php echo $item['partita_iva']; ?><br/>
            <?php echo "<b>"."CODICE FISCALE : "."</b>";?> <?php echo $item['codice_fiscale']; ?><br/>
			<?php echo "<b>"."SEDE LEGALE : "."</b>";?> <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'] ;?><br/>
			<?php echo "<b>"."PEC : "."</b>";?><?php echo $item['impresa_pec'];?></br>
			<?php echo "<b>"."RECAPITO TELEFONICO : "."</b>";?><?php echo $item['sl_telefono'];?></br><br/>
			<?php echo "<b>"."STATO PRATICA : "."</b>";?> <?php
			if ($item['stato']=='0')
			echo "In richiesta";
			else if ($item['stato']=='1')
			echo "Iscritto";
			else
			echo "Iscritto provvisoriamente"
			?>

			</td>
		<?php
		}	 ?>
        <td class="left">
		 <div class="stato">
            <label for="stato<?php echo $item['ID']; ?>"><b>Cambia stato pratica</b></label>
                <?php
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['stato'];
                   echo form_dropdown("stato[{$item['ID']}]", $options, $item['stato'], "class=\"stato\" id=\"stato{$item['ID']}\"");
                   ?>
		</div>

            <?php
                if($item['uploaded_at'] != NULL):
                //query_link('uploaded_at', "Procedura terminata ");
                echo "<b>"."PROCEDURA TERMINATA IL : "."</b>";
				echo format_date($item['uploaded_at']);
                endif;
            ?> <br/><br/>

			 <?php
                if($item['iscritti_prov_at'] != NULL):
                //query_link('iscritti_prov_at', "Iscritto provvisoriamente il ");
				echo "<b>"."ISCRITTO PROVVISORIAMENTE IL : "."</b>";
                echo format_date($item['iscritti_prov_at']);
                endif;
            ?> <br/><br/>
			 <?php
                if($item['iscritti_at'] != NULL):
                //query_link('iscritti_at', "Iscritto il ");
				echo "<b>"."ISCRITTO IL : "."</b>";
                echo format_date($item['iscritti_at']);
                endif;
            ?> <br/>


        </td>


	    <td class="smallbox">



		<?php if ( ($item['iscritti_prov_at'])!=NULL ) { ?>
		<div class="field">
		 <label for="iscritti_prov_at<?php echo $item['ID']; ?>"><?php query_link ('iscritti_prov_at',"Data di iscrizione provvisoria");?></label>
		 <input type="text" id="iscritti_prov_at<?php echo $item['ID']; ?>" name = "iscritti_prov_at[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['iscritti_prov_at']);?>>
		 </div>
		<?php
		}
		else { ?>
		<div class="field">
		 <label for="iscritti_prov_at<?php echo $item['ID']; ?>"><?php query_link ('iscritti_prov_at',"Data di iscrizione provvisoria");?></label>
		 <input type="text" id="iscritti_prov_at<?php echo $item['ID']; ?>" name = "iscritti_prov_at[<?php echo $item['ID']; ?>]" value= <?php echo ""; ?>>
		</div>
		<?php }
		if ( ($item['iscritti_prov_scadenza'])!=NULL ) { ?>
		<div class="field">
		 <label for="iscritti_prov_scadenza<?php echo $item['ID']; ?>"><?php query_link ('iscritti_prov_scadenza',"Scadenza iscrizione provvisoria");?></label>
		 <input type="text" id="iscritti_prov_scadenza<?php echo $item['ID']; ?>" name = "iscritti_prov_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['iscritti_prov_scadenza']);?>>
		</div>
		<?php
		}
		else { ?>
		<div class="field">
		 <label for="iscritti_prov_scadenza<?php echo $item['ID']; ?>"><?php query_link ('iscritti_prov_scadenza',"Scadenza iscrizione provvisoria");?></label>
		 <input type="text" id="iscritti_prov_scadenza<?php echo $item['ID']; ?>" name = "iscritti_prov_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo "";?>>
		</div>
		<?php }
		?>


		<?php if ( ($item['iscritti_at'])!=NULL ) { ?>
		<div class="field">
		 <label for="iscritti_at<?php echo $item['ID']; ?>"><?php query_link ('iscritti_at',"Data di iscrizione");?></label>
		 <input type="text" id="iscritti_at<?php echo $item['ID']; ?>" name = "iscritti_at[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['iscritti_at']);?>>
		 </div>
		<?php
		}
		else { ?>
		<div class="field">
		 <label for="iscritti_at<?php echo $item['ID']; ?>"><?php query_link ('iscritti_at',"Data di iscrizione");?></label>
		 <input type="text" id="iscritti_at<?php echo $item['ID']; ?>" name = "iscritti_at[<?php echo $item['ID']; ?>]" value= <?php echo ""; ?>>
		</div>
		<?php }
		if ( ($item['iscritti_scadenza'])!=NULL ) { ?>
		<div class="field">
		 <label for="iscritti_scadenza<?php echo $item['ID']; ?>"><?php query_link ('iscritti_scadenza',"Scadenza iscrizione");?></label>
		 <input type="text" id="iscritti_scadenza<?php echo $item['ID']; ?>" name = "iscritti_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['iscritti_scadenza']);?>>
		</div>
		<?php
		}
		else { ?>
		<div class="field">
		 <label for="iscritti_scadenza<?php echo $item['ID']; ?>"><?php query_link ('iscritti_scadenza',"Scadenza iscrizione");?></label>
		 <input type="text" id="iscritti_scadenza<?php echo $item['ID']; ?>" name = "iscritti_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo "";?>>
		</div>
		<?php }
		?>



		   <!--
		     <div class="field">
	            <label for="avvio_proc_sent<?php echo $item['ID']; ?>"><?php query_link('avvio_proc_sent', "Avvio del procedimento"); ?></label>
                <?php
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['avvio_proc_sent'];
                   echo form_dropdown("avvio_proc_sent[{$item['ID']}]", $options, $item['avvio_proc_sent'], "class=\"avvio_proc_sent\" id=\"avvio_proc_sent{$item['ID']}\"");
                   ?>
           </div>

		    <div class="field">
	            <label for="integrazioni<?php echo $item['ID']; ?>"><?php query_link('uploaded', "Stato integrazioni"); ?></label>
                <?php
                   $options = array('0' => 'Non richieste', '1' => 'In attesa','2'=>'In valutazione','3'=>'Integrazione accettata','4'=>'Integrazione rifiutata');

                   echo form_dropdown("stato[{$item['ID']}]", $options, $item['stato'], "class=\"stato\" id=\"stato{$item['ID']}\"");
                   ?>
           </div>
		   -->

		</td>




		<!--
       <td class="smallbox">
	    <div class="field">
                <label for="parere_dia<?php echo $item['ID']; ?>"><?php query_link('parere_dia', "Parere DIA"); ?></label>
                <?php
                   $options = $legenda['parere_dia'];
                   echo form_dropdown("parere_dia[{$item['ID']}]", $options, $item['parere_dia'], "class=\"parere_dia\" id=\"parere_dia{$item['ID']}\"");
                   ?>
            </div>

	     <div class="field">
		  <label for="dia_scadenza<?php echo $item['ID']; ?>"><?php query_link ('dia_scadenza',"10 gg DIA");?></label>
		  <?php if ((strtotime($item['dia_scadenza'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['dia_scadenza']) ) != '0000-00-00 00:00:00' && (($item['parere_dia']) ) == '0' ) { ?>
		  <input type="text" style="background-color:red" id="dia_scadenza<?php echo $item['ID']; ?>" name = "dia_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['dia_scadenza']); ?>>
		  <?php

		  } else { ?>
		  <input type="text" id="dia_scadenza<?php echo $item['ID']; ?>" name = "dia_scadenza[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['dia_scadenza']);?>>
		  <?php }
		  ?>
		</div>
	  </td>
	   <td class="smallbox">
	    <div class="field">
                <label for="parere_pref<?php echo $item['ID']; ?>"><?php query_link('parere_prefettura', "Parere Prefettura"); ?></label>
                <?php
                   $options = $legenda['parere_prefettura'];
                   echo form_dropdown("parere_prefettura[{$item['ID']}]", $options, $item['parere_prefettura'], "class=\"parere_prefettura\" id=\"parere_prefettura{$item['ID']}\"");
                   ?>
            </div>

	     <div class="field">
		  <label for="pref_scadenza_30<?php echo $item['ID']; ?>"><?php query_link ('pref_scadenza_30',"30 gg Prefettura");?></label>
		  <?php if ((strtotime($item['pref_scadenza_30'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['pref_scadenza_30']) ) != '0000-00-00 00:00:00' && (($item['parere_prefettura']) ) == '0') { ?>
		  <input type="text" style="background-color:red" id="pref_scadenza_30<?php echo $item['ID']; ?>" name = "pref_scadenza_30[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['pref_scadenza_30']); ?>>
		  <?php

		  } else { ?>
		  <input type="text" id="pref_scadenza_30<?php echo $item['ID']; ?>" name = "pref_scadenza_30[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['pref_scadenza_30']);?>>
		  <?php }
		  ?>

		</div>
		<div class="field">
                <label for="pref_soll_30_sent<?php echo $item['ID']; ?>"><?php query_link('pref_soll_30_sent', "Sollecito 30 GG"); ?></label>
                <?php
                   $options = $legenda['pref_soll_30_sent'];
                   echo form_dropdown("pref_soll_30_sent[{$item['ID']}]", $options, $item['pref_soll_30_sent'], "class=\"pref_soll_30_sent\" id=\"pref_soll_30_sent{$item['ID']}\"");
                   ?>
            </div>
		 <div class="field">
		  <label for="pref_scadenza_75<?php echo $item['ID']; ?>"><?php query_link ('pref_scadenza_75',"75 gg Prefettura");?></label>
		  <?php if ((strtotime($item['pref_scadenza_75'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['pref_scadenza_75']) ) != '0000-00-00 00:00:00' && (($item['parere_prefettura']) ) == '0') { ?>
		  <input type="text" style="background-color:red" id="pref_scadenza_75<?php echo $item['ID']; ?>" name = "pref_scadenza_75[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['pref_scadenza_75']); ?>>
		  <?php

		  } else { ?>
		  <input type="text" id="pref_scadenza_75<?php echo $item['ID']; ?>" name = "pref_scadenza_75[<?php echo $item['ID']; ?>]" value= <?php echo format_date($item['pref_scadenza_75']);?>>
		  <?php }
		  ?>
		</div>
			<div class="field">
                <label for="pref_soll_75_sent<?php echo $item['ID']; ?>"><?php query_link('pref_soll_75_sent', "Sollecito 75 GG"); ?></label>
                <?php
                   $options = $legenda['pref_soll_75_sent'];
                   echo form_dropdown("pref_soll_75_sent[{$item['ID']}]", $options, $item['pref_soll_75_sent'], "class=\"pref_soll_75_sent\" id=\"pref_soll_75_sent{$item['ID']}\"");
                   ?>
            </div>-->






	<td class="smallbox">
	  <div class="field">
	            <label for="uploaded<?php echo $item['ID']; ?>">
				<?php //query_link('uploaded', "Carta di identità"); ?>
				<?php echo "Carta di identità" ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("uploaded[{$item['ID']}]", $options, $item['uploaded'], "class=\"uploaded\" id=\"uploaded{$item['ID']}\"");
                   ?>
           </div>
		     <div class="field">
	            <label for="digital<?php echo $item['ID']; ?>">
				<?php //query_link('is_digital', "Tipo di invio"); ?>
				<?php echo "Tipo di pratica" ?>
				</label>
                <?php
                   $options = array('0' => 'Cartacea', '1' => 'Digitale');

                   echo form_dropdown("is_digital[{$item['ID']}]", $options, $item['is_digital'], "class=\"is_digital\" id=\"is_digital{$item['ID']}\"");
                   ?>
           </div>
	  <div class="field">
	            <label for="wl<?php echo $item['ID']; ?>">
				<?php //query_link('stmt_wl', "White list"); ?>
				<?php echo "White list" ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("stmt_wl[{$item['ID']}]", $options, $item['stmt_wl'], "class=\"stmt_wl\" id=\"stmt_wl{$item['ID']}\"");
                   ?>
           </div>
		   <div class="field">
	            <label for="protocollato<?php echo $item['ID']; ?>">
				<?php //query_link('protocollato', "Protocollato"); ?>
				<?php echo "Protocollato" ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("protocollato[{$item['ID']}]", $options, $item['protocollato'], "class=\"protocollato\" id=\"protocollato{$item['ID']}\"");
                   ?>
           </div>
		<div class="field">
		         <label for="protocollo_struttura<?php echo $item['ID']; ?>">
				 <?php //query_link('protocollo_struttura', "Numero protocollo domanda"); ?>
				 <?php echo "Numero protocollo domanda" ?>
				 </label>
                 <input type="text" id="protocollo_struttura<?php echo $item['ID']; ?>" name = "protocollo_struttura[<?php echo $item['ID']; ?>]" value= <?php echo $item['protocollo_struttura'];?>>
				 <?php //echo form_input("protocollo_struttura[{$item['ID']}]",$item['protocollo_struttura'],"protocollo_struttura[{$item['ID']}]"); ?>

        </div>
		<div class="field">
		         <label for="fascicolo_struttura<?php echo $item['ID']; ?>">
				 <?php //query_link('fascicolo_struttura', "Numero fascicolo domanda"); ?>
				  <?php echo "Numero fascicolo domanda" ?>
				 </label>
                 <input type="text" id="fascicolo_struttura<?php echo $item['ID']; ?>" name = "fascicolo_struttura[<?php echo $item['ID']; ?>]" value= <?php echo $item['fascicolo_struttura'];?>>
				 <?php //echo form_input("fascicolo_struttura[{$item['ID']}]",$item['fascicolo_struttura'],"fascicolo_struttura[{$item['ID']}]"); ?>

        </div>
	  </td>
    </tr>
<?php
}
/*
██       ██████   ██████  ██████  ███████ ███    ██ ██████
██      ██    ██ ██    ██ ██   ██ ██      ████   ██ ██   ██
██      ██    ██ ██    ██ ██████  █████   ██ ██  ██ ██   ██
██      ██    ██ ██    ██ ██      ██      ██  ██ ██ ██   ██
███████  ██████   ██████  ██      ███████ ██   ████ ██████
*/
?>
</table>
</form>

<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>

<div id="modal-fog"></div>
<div id="infoRequestModal" class="cth_modal">
  <div class="inner">
    <h3>Richiesta Integrazioni</h3>
    <form method="post" enctype="multipart/form-data" name="irm" id="irm">
      <div class="m-row">
        <!--
        <div class="m-field half">
          <div class="inner">
            <label for="irm-from">Mittente :</label>
            <input type="text" name="irm-from" id="irm-from"  />
          </div>
        </div>
        -->
        <div class="m-field half">
          <div class="inner">
            <label for="irm-to">Destinatario :</label>
            <input type="text" class="dontUpdate" name="irm-to" id="irm-to"  />
          </div>
        </div>
        <div class="m-field half">
          <div class="inner">
            <label for="irm-attachments">Allegati :</label>
            <input type="file" class="dontUpdate" name="irm_attachments[]" multiple />
          </div>
        </div>
      </div>

      <div class="m-row">
        <div class="m-field">
          <div class="inner">
            <label for="irm-subject">Oggetto :</label>
            <input type="text" class="dontUpdate" name="irm-subject" id="irm-subject"  />
          </div>
        </div>
      </div>
      <div class="m-row">
        <div class="m-field">
          <div class="inner">
            <label for="irm-attachments">Template :</label>
            <select id="irm-template" name="irm-template">
<?php
if ( isset($irm_templates) && !empty($irm_templates) ) {
  foreach ( $irm_templates AS $template_id => $template_name ) {
    printf(
      '<option value="%s">%s</option>',
      $template_id,
      $template_name
    );
  }
}
?>
            </select>
          </div>
        </div>
      </div>
      <div class="m-row">
        <div class="m-field">
          <div class="inner">
            <textarea name="irm-text" id="irm-text"></textarea>
          </div>
        </div>
      </div>
      <div class="m-row">
        <div class="m-field half center">
          <a class="button cancel" id="irm-no">Annulla</a>
        </div>
        <div class="m-field half center">
          <a class="button ok" id="irm-si">Invia</a>
        </div>
      </div>
    </form>
  </div>
</div>
<?php
if ( isset($pecs) && !empty($pecs) ) {
  printf(
    "<script> window._pec_list = %s;</script>",
    json_encode($pecs)
  );
}
?>
