<ul class="submenu">
    <li class="selected"> Istruttoria domande </li>
<?php if (is_admin()): ?>
    <li>
          <a href="<?php echo site_url('/admin/caricamento_bdna'); ?>">CSV BDNA</a>
      </li>
<?php endif; ?>

</ul>
<?php
if (isset($actionMessage)) {
    printf('<div class="action-message %s">%s</div>', $actionMessage['type'], $actionMessage['msg']);
}
?>
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
            $options = array('' => '-', '1' => 'Iscritti', '2' => 'Iscritti provvisoriamente', '0' => 'In richiesta');
            $get_stato = $this->input->get('stato');
            echo form_dropdown('stato', $options, ($get_stato === false) ? '' : $get_stato, 'id="stato"');
            ?>
			</div>

			<div class="field">
			<?php
            echo form_label('Carta di identità', 'uploaded');
            $options = array('' => '-', '0' => 'No', '1' => 'Si');
            $get_uploaded = $this->input->get('uploaded');
            echo form_dropdown('uploaded', $options, ($get_uploaded === false) ? '' : $get_uploaded, 'id="uploaded"');
            ?>
			</div>
			<div class="field">
			<?php
            echo form_label('Tipo di pratica', 'is_digital');
            $options = array('' => '-', '0' => 'Cartacea', '1' => 'Digitale');
            $get_is_digital = $this->input->get('is_digital');
            echo form_dropdown('is_digital', $options, ($get_is_digital === false) ? '' : $get_is_digital, 'id="is_digital"');
            ?>
			</div>
			<!--
			<div class="field">
			<?php
            echo form_label('Stato integrazioni', 'stato');
            $options = array('' => '-', '0' => 'Non richieste', '1' => 'In attesa', '2' => 'In valutazione', '3' => 'Integrazione accettata', '4' => 'Integrazione rifiutata');
            $get_integrazioni = $this->input->get('per_page');
            echo form_dropdown('', $options, ($get_integrazioni === false) ? '' : $get_integrazioni, 'id="integrazioni"');
            ?>
			</div>
			-->
			<div class="field">
			<?php
            echo form_label('White list', 'stmt_wl');
            $options = array('' => '-', '0' => 'No', '1' => 'Si');
            $get_white_list = $this->input->get('stmt_wl');
            echo form_dropdown('stmt_wl', $options, ($get_white_list === false) ? '' : $get_white_list, 'id="stmt_wl"');
            ?>
			</div>
			<div class="field">
			<?php
            echo form_label('Mostra', 'mostra');
            $options = array('25' => '25', '50' => '50', '100' => '100', '500' => '500');
            $get_mostra = $this->input->get('mostra');
            if ($get_mostra == '') {
                $get_mostra = $this->input->get('per_page');
            }
            echo form_dropdown('mostra', $options, ($get_mostra === false) ? '' : $get_mostra, 'id="mostra"');
            ?>
			</div>
			<div class="field">
                <label>&nbsp;</label>
                <?php echo form_submit('submit', 'Applica filtro'); ?>
            </div>
       </div>
    </form>
</div>
<?php echo form_open('admin/update') ?>
<table class="elenco admin">
	<tr>
        <th><?php //query_link('codice_istanza', "Ordina imprese"); ?></th>
        <th></th>
        <th colspan="3"></th>
	</tr>
<?php foreach ($statements as $item):

?>


	<tr>


		<td class="id">
		<?php if ($item['is_digital'] == '1') {
    ?>
		 <a class="button ext" href="<?php echo site_url(array('admin', 'view', $item['ID']));
    ?>">Scarica PDF</a>
			<?php if ($item['uploaded'] == '1') {
    ?>
			<a class="button ext" href="<?php echo base_url(array('uploads', 'CI_'.$item['codice_istanza'].'.pdf'));
    ?>">Scarica Carta di identità</a>


			 <!--
			 <?php if ($item['stato'] == '2') {
    ?>
			<a class="button green" href="<?php echo site_url(array('admin', 'view', $item['ID']));
    ?>">Visualizza integrazioni</a>
			<?php
} else {
    ?>
			<a class="button green" href="<?php echo site_url(array('admin', 'view', $item['ID']));
    ?>">Richiedi integrazioni</a>


			<?php

}
    ?>-->
	       <?php

}

    if ( $item['avvio_proc_dia'] ) {
      printf(
        '<a class="button myblue alreadysent" data-action="info-request" data-item="%s" id="%s-dia">Richiesta Informazioni DIA / GICERIC / Prefettura</a>',
        $item['ID'],
        $item['ID']
      );
    }
    else {
      printf(
        '<a class="button myblue" data-action="info-request" data-item="%s" id="%s-dia">Richiesta Informazioni DIA / GICERIC / Prefettura</a>',
        $item['ID'],
        $item['ID']
      );
    }

    if ( $item['avvio_proc_oe'] ) {
      printf(
        '<a class="button myblue alreadysent" data-action="proc-request" data-item="%s" id="%s-oe">Comunicazione avvio istruttoria</a>',
        $item['ID'],
        $item['ID']
      );
    }
    else {
      printf(
        '<a class="button myblue" data-action="proc-request" data-item="%s" id="%s-oe">Comunicazione avvio istruttoria</a>',
        $item['ID'],
        $item['ID']
      );

    }
}

    ?>

		<label for="unlock_<?php echo $item['ID']; ?>"><a class="button unlock" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "button[{$item['ID']}]"; ?>">Attiva modifiche</a></label>

		<!--
		<label for="ap<?php echo $item['ID']; ?>"><a class="button red ext" title="Avvio procedimento" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "ap[{$item['ID']}]"; ?>">NOTIFICA AVVIO DEL PROCEDIMENTO</a></label>
		<label for="sol_pref_30_<?php echo $item['ID']; ?>"><a class="button red ext" title="Primo sollecito prefettura" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "sol_pref_30_[{$item['ID']}]"; ?>">PRIMO SOLLECITO PREFETTURA</a></label>
		<label for="sol_pref_75_<?php echo $item['ID']; ?>"><a class="button red ext" title="Secondo sollecito prefettura" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "sol_pref_75_[{$item['ID']}]"; ?>">SECONDO SOLLECITO PREFETTURA</a></label>
		-->


		</td>

		<?php if ($item['is_digital'] == '0') {
    ?>
		 <td>
<div class="sl_wrapper">
		     <?php echo '<b>'.'RAGIONE SOCIALE : '.'</b>';
    ?> <?php echo $item['ragione_sociale'];
    ?><br/>
            <?php echo '<b>'.'PARTITA IVA : '.'</b>';
    ?> <?php echo $item['partita_iva'];
    ?><br/>
             <?php echo '<b>'.'CODICE FISCALE : '.'</b>';
    ?> <?php echo $item['codice_fiscale'];
    ?><br/>
			<?php echo '<b>'.'SEDE LEGALE : '.'</b>';
    ?> <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'];
    ?></br><br/>
		    <?php echo '<b>'.'STATO PRATICA : '.'</b>';
    ?> <?php
            if ($item['stato'] == '0') {
                echo 'In richiesta';
            } elseif ($item['stato'] == '1') {
                echo 'Iscritto';
            } else {
                echo 'Iscritto provvisoriamente';
            }
    ?>
</div>
			</td>


		<?php

} else {
            ?>

			<td>
        <div class="sl_wrapper">
			<?php echo '<b>'.$item['codice_istanza'].'</b>';
            ?><br/><br/>
			<?php echo '<b>'.'RICHIEDENTE : '.'</b>';
            ?> <?php echo $item['titolare_nome'].' '.$item['titolare_cognome'];
            ?><br/>
		    <?php echo '<b>'.'RAGIONE SOCIALE : '.'</b>';
            ?> <?php echo $item['ragione_sociale'];
            ?><br/>
            <?php echo '<b>'.'PARTITA IVA : '.'</b>';
            ?> <?php echo $item['partita_iva'];
            ?><br/>
            <?php echo '<b>'.'CODICE FISCALE : '.'</b>';
            ?> <?php echo $item['codice_fiscale'];
            ?><br/>
			<?php echo '<b>'.'SEDE LEGALE : '.'</b>';
            ?> <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'];
            ?><br/>
			<?php echo '<b>'.'PEC : '.'</b>';
            ?><?php echo $item['impresa_pec'];
            ?></br>
			<?php echo '<b>'.'RECAPITO TELEFONICO : '.'</b>';
            ?><?php echo $item['sl_telefono'];
            ?></br><br/>
			<?php echo '<b>'.'STATO PRATICA : '.'</b>';
            ?> <?php
            if ($item['stato'] == '0') {
                echo 'In richiesta';
            } elseif ($item['stato'] == '1') {
                echo 'Iscritto';
            } else {
                echo 'Iscritto provvisoriamente';
            }
            ?>
</div>
			</td>
		<?php

        }     ?>
        <td class="left middlebox">
		 <div class="statofieldwrapper">
            <label for="stato<?php echo $item['ID']; ?>"><b>Cambia stato pratica</b></label>
                <?php
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['stato'];
                   echo form_dropdown("stato[{$item['ID']}]", $options, $item['stato'], "class=\"stato\" id=\"stato{$item['ID']}\"");
                   ?>
		</div>

            <?php
                if ($item['uploaded_at'] != null):
                //query_link('uploaded_at', "Procedura terminata ");
                echo '<b>'.'PROCEDURA TERMINATA IL : '.'</b>';
                echo format_date($item['uploaded_at']);
                endif;
            ?> <br/><br/>

			 <?php
                if ($item['iscritti_prov_at'] != null):
                //query_link('iscritti_prov_at', "Iscritto provvisoriamente il ");
                echo '<b>'.'ISCRITTO PROVVISORIAMENTE IL : '.'</b>';
                echo format_date($item['iscritti_prov_at']);
                endif;
            ?> <br/><br/>
			 <?php
                if ($item['iscritti_at'] != null):
                //query_link('iscritti_at', "Iscritto il ");
                echo '<b>'.'ISCRITTO IL : '.'</b>';
                echo format_date($item['iscritti_at']);
                endif;
            ?> <br/>


        </td>


	    <td class="smallbox">



		<?php if (($item['iscritti_prov_at']) != null) {
    ?>
		<div class="field">
		 <label for="iscritti_prov_at<?php echo $item['ID'];
    ?>"><?php query_link('iscritti_prov_at', 'Data di iscrizione provvisoria');
    ?></label>
		 <input type="text" class="iscritti_prov_at" id="iscritti_prov_at<?php echo $item['ID'];
    ?>" name = "iscritti_prov_at[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['iscritti_prov_at']);
    ?>>
		 </div>
		<?php

} else {
            ?>
		<div class="field">
		 <label for="iscritti_prov_at<?php echo $item['ID'];
            ?>"><?php query_link('iscritti_prov_at', 'Data di iscrizione provvisoria');
            ?></label>
		 <input type="text" class="iscritti_prov_at" id="iscritti_prov_at<?php echo $item['ID'];
            ?>" name = "iscritti_prov_at[<?php echo $item['ID'];
            ?>]" value= <?php echo '';
            ?>>
		</div>
		<?php
        }
        if (($item['iscritti_prov_scadenza']) != null) {
            ?>
		<div class="field">
		 <label for="iscritti_prov_scadenza<?php echo $item['ID'];
            ?>"><?php query_link('iscritti_prov_scadenza', 'Scadenza iscrizione provvisoria');
            ?></label>
		 <input type="text" class="iscritti_prov_scadenza" id="iscritti_prov_scadenza<?php echo $item['ID'];
            ?>" name = "iscritti_prov_scadenza[<?php echo $item['ID'];
            ?>]" value= <?php echo format_date($item['iscritti_prov_scadenza']);
            ?>>
		</div>
		<?php

        } else {
            ?>
		<div class="field">
		 <label for="iscritti_prov_scadenza<?php echo $item['ID'];
            ?>"><?php query_link('iscritti_prov_scadenza', 'Scadenza iscrizione provvisoria');
            ?></label>
		 <input type="text" class="iscritti_prov_scadenza" id="iscritti_prov_scadenza<?php echo $item['ID'];
            ?>" name = "iscritti_prov_scadenza[<?php echo $item['ID'];
            ?>]" value= <?php echo '';
            ?>>
		</div>
		<?php
        }
        ?>


		<?php if (($item['iscritti_at']) != null) {
    ?>
		<div class="field">
		 <label for="iscritti_at<?php echo $item['ID'];
    ?>"><?php query_link('iscritti_at', 'Data di iscrizione');
    ?></label>
		 <input type="text" class="iscritti_at" id="iscritti_at<?php echo $item['ID'];
    ?>" name = "iscritti_at[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['iscritti_at']);
    ?>>
		 </div>
		<?php

} else {
            ?>
		<div class="field">
		 <label for="iscritti_at<?php echo $item['ID'];
            ?>"><?php query_link('iscritti_at', 'Data di iscrizione');
            ?></label>
		 <input type="text" class="iscritti_at" id="iscritti_at<?php echo $item['ID'];
            ?>" name = "iscritti_at[<?php echo $item['ID'];
            ?>]" value= <?php echo '';
            ?>>
		</div>
		<?php
        }
        if (($item['iscritti_scadenza']) != null) {
            if ((strtotime($item['iscritti_scadenza'])) < (strtotime(date('Y-m-d H:i:s')))) {
                ?>
		<div class="field">
		 <label for="iscritti_scadenza<?php echo $item['ID'];
                ?>"><?php query_link('iscritti_scadenza', 'Scadenza iscrizione');
                ?></label>
		 <input type="text" style="background-color:red" class="iscritti_scadenza" id="iscritti_scadenza<?php echo $item['ID'];
                ?>" name = "iscritti_scadenza[<?php echo $item['ID'];
                ?>]" value= <?php echo format_date($item['iscritti_scadenza']);
                ?>>
		</div>
		<?php
            } else {
                ?>
		<div class="field">
		 <label for="iscritti_scadenza<?php echo $item['ID'];
                ?>"><?php query_link('iscritti_scadenza', 'Scadenza iscrizione');
                ?></label>
		 <input type="text" class="iscritti_scadenza" id="iscritti_scadenza<?php echo $item['ID'];
                ?>" name = "iscritti_scadenza[<?php echo $item['ID'];
                ?>]" value= <?php echo format_date($item['iscritti_scadenza']);
                ?>>
		</div>
		<?php

            }
        } else {
            ?>
		<div class="field">
		 <label for="iscritti_scadenza<?php echo $item['ID'];
            ?>"><?php query_link('iscritti_scadenza', 'Scadenza iscrizione');
            ?></label>
		 <input type="text" class="iscritti_scadenza" id="iscritti_scadenza<?php echo $item['ID'];
            ?>" name = "iscritti_scadenza[<?php echo $item['ID'];
            ?>]" value= <?php echo '';
            ?>>
		</div>
		<?php
        }
    if (($item['dia_scadenza']) != null) {
        if ((strtotime($item['dia_scadenza'])) < (strtotime(date('Y-m-d H:i:s')))) {
            ?>
        <div class="field">
        <label for="dia_scadenza<?php echo $item['ID'];
            ?>"><?php query_link('dia_scadenza', 'Scadenza richiesta informazioni');
            ?></label>
        <input type="text" style="background-color:red" class="dia_scadenza" id="dia_scadenza<?php echo $item['ID'];
            ?>" name = "dia_scadenza[<?php echo $item['ID'];
            ?>]" value= <?php echo format_date($item['dia_scadenza']);
            ?>>
        </div>
      <?php
        } else {
            ?>
      <div class="field">
      <label for="dia_scadenza<?php echo $item['ID'];
            ?>"><?php query_link('dia_scadenza', 'Scadenza richiesta informazioni');
            ?></label>
      <input type="text" class="dia_scadenza" id="dia_scadenza<?php echo $item['ID'];
            ?>" name = "dia_scadenza[<?php echo $item['ID'];
            ?>]" value= <?php echo format_date($item['dia_scadenza']);
            ?>>
      </div>
    <?php

        }
    } else {
        ?>
    <div class="field">
    <label for="dia_scadenza<?php echo $item['ID'];
        ?>"><?php query_link('dia_scadenza', 'Scadenza richiesta informazioni');
        ?></label>
    <input type="text" class="dia_scadenza" id="dia_scadenza<?php echo $item['ID'];
        ?>" name = "dia_scadenza[<?php echo $item['ID'];
        ?>]" value= <?php echo '';
        ?>>
    </div>
    <?php
    } ?>



		   <!--
		     <div class="field">
	            <label for="avvio_proc_sent<?php echo $item['ID']; ?>"><?php query_link('avvio_proc_sent', 'Avvio del procedimento'); ?></label>
                <?php
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['avvio_proc_sent'];
                   echo form_dropdown("avvio_proc_sent[{$item['ID']}]", $options, $item['avvio_proc_sent'], "class=\"avvio_proc_sent\" id=\"avvio_proc_sent{$item['ID']}\"");
                   ?>
           </div>

		    <div class="field">
	            <label for="integrazioni<?php echo $item['ID']; ?>"><?php query_link('uploaded', 'Stato integrazioni'); ?></label>
                <?php
                   $options = array('0' => 'Non richieste', '1' => 'In attesa', '2' => 'In valutazione', '3' => 'Integrazione accettata', '4' => 'Integrazione rifiutata');

                   echo form_dropdown("stato[{$item['ID']}]", $options, $item['stato'], "class=\"stato\" id=\"stato{$item['ID']}\"");
                   ?>
           </div>
		   -->

		</td>




		<!--
       <td class="smallbox">
	    <div class="field">
                <label for="parere_dia<?php echo $item['ID']; ?>"><?php query_link('parere_dia', 'Parere DIA'); ?></label>
                <?php
                   $options = $legenda['parere_dia'];
                   echo form_dropdown("parere_dia[{$item['ID']}]", $options, $item['parere_dia'], "class=\"parere_dia\" id=\"parere_dia{$item['ID']}\"");
                   ?>
            </div>

	     <div class="field">
		  <label for="dia_scadenza<?php echo $item['ID']; ?>"><?php query_link('dia_scadenza', '10 gg DIA');?></label>
		  <?php if ((strtotime($item['dia_scadenza'])) < (strtotime(date('Y-m-d H:i:s'))) && (($item['dia_scadenza'])) != '0000-00-00 00:00:00' && (($item['parere_dia'])) == '0') {
    ?>
		  <input type="text" style="background-color:red" id="dia_scadenza<?php echo $item['ID'];
    ?>" name = "dia_scadenza[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['dia_scadenza']);
    ?>>
		  <?php

} else {
    ?>
		  <input type="text" id="dia_scadenza<?php echo $item['ID'];
    ?>" name = "dia_scadenza[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['dia_scadenza']);
    ?>>
		  <?php
}
          ?>
		</div>
	  </td>
	   <td class="smallbox">
	    <div class="field">
                <label for="parere_pref<?php echo $item['ID']; ?>"><?php query_link('parere_prefettura', 'Parere Prefettura'); ?></label>
                <?php
                   $options = $legenda['parere_prefettura'];
                   echo form_dropdown("parere_prefettura[{$item['ID']}]", $options, $item['parere_prefettura'], "class=\"parere_prefettura\" id=\"parere_prefettura{$item['ID']}\"");
                   ?>
            </div>

	     <div class="field">
		  <label for="pref_scadenza_30<?php echo $item['ID']; ?>"><?php query_link('pref_scadenza_30', '30 gg Prefettura');?></label>
		  <?php if ((strtotime($item['pref_scadenza_30'])) < (strtotime(date('Y-m-d H:i:s'))) && (($item['pref_scadenza_30'])) != '0000-00-00 00:00:00' && (($item['parere_prefettura'])) == '0') {
    ?>
		  <input type="text" style="background-color:red" id="pref_scadenza_30<?php echo $item['ID'];
    ?>" name = "pref_scadenza_30[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['pref_scadenza_30']);
    ?>>
		  <?php

} else {
    ?>
		  <input type="text" id="pref_scadenza_30<?php echo $item['ID'];
    ?>" name = "pref_scadenza_30[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['pref_scadenza_30']);
    ?>>
		  <?php
}
          ?>

		</div>
		<div class="field">
                <label for="pref_soll_30_sent<?php echo $item['ID']; ?>"><?php query_link('pref_soll_30_sent', 'Sollecito 30 GG'); ?></label>
                <?php
                   $options = $legenda['pref_soll_30_sent'];
                   echo form_dropdown("pref_soll_30_sent[{$item['ID']}]", $options, $item['pref_soll_30_sent'], "class=\"pref_soll_30_sent\" id=\"pref_soll_30_sent{$item['ID']}\"");
                   ?>
            </div>
		 <div class="field">
		  <label for="pref_scadenza_75<?php echo $item['ID']; ?>"><?php query_link('pref_scadenza_75', '75 gg Prefettura');?></label>
		  <?php if ((strtotime($item['pref_scadenza_75'])) < (strtotime(date('Y-m-d H:i:s'))) && (($item['pref_scadenza_75'])) != '0000-00-00 00:00:00' && (($item['parere_prefettura'])) == '0') {
    ?>
		  <input type="text" style="background-color:red" id="pref_scadenza_75<?php echo $item['ID'];
    ?>" name = "pref_scadenza_75[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['pref_scadenza_75']);
    ?>>
		  <?php

} else {
    ?>
		  <input type="text" id="pref_scadenza_75<?php echo $item['ID'];
    ?>" name = "pref_scadenza_75[<?php echo $item['ID'];
    ?>]" value= <?php echo format_date($item['pref_scadenza_75']);
    ?>>
		  <?php
}
          ?>
		</div>
			<div class="field">
                <label for="pref_soll_75_sent<?php echo $item['ID']; ?>"><?php query_link('pref_soll_75_sent', 'Sollecito 75 GG'); ?></label>
                <?php
                   $options = $legenda['pref_soll_75_sent'];
                   echo form_dropdown("pref_soll_75_sent[{$item['ID']}]", $options, $item['pref_soll_75_sent'], "class=\"pref_soll_75_sent\" id=\"pref_soll_75_sent{$item['ID']}\"");
                   ?>
            </div>-->






	<td class="smallbox">
	  <div class="field">
	            <label for="uploaded<?php echo $item['ID']; ?>">
				<?php //query_link('uploaded', "Carta di identità"); ?>
				<?php echo 'Carta di identità' ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("uploaded[{$item['ID']}]", $options, $item['uploaded'], "class=\"uploaded\" id=\"uploaded{$item['ID']}\"");
                   ?>
           </div>
		     <div class="field">
	            <label for="digital<?php echo $item['ID']; ?>">
				<?php //query_link('is_digital', "Tipo di invio"); ?>
				<?php echo 'Tipo di pratica' ?>
				</label>
                <?php
                   $options = array('0' => 'Cartacea', '1' => 'Digitale');

                   echo form_dropdown("is_digital[{$item['ID']}]", $options, $item['is_digital'], "class=\"is_digital\" id=\"is_digital{$item['ID']}\"");
                   ?>
           </div>
	  <div class="field">
	            <label for="wl<?php echo $item['ID']; ?>">
				<?php //query_link('stmt_wl', "White list"); ?>
				<?php echo 'White list' ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("stmt_wl[{$item['ID']}]", $options, $item['stmt_wl'], "class=\"stmt_wl\" id=\"stmt_wl{$item['ID']}\"");
                   ?>
           </div>
		   <div class="field">
	            <label for="protocollato<?php echo $item['ID']; ?>">
				<?php //query_link('protocollato', "Protocollato"); ?>
				<?php echo 'Protocollato' ?>
				</label>
                <?php
                   $options = array('0' => 'no', '1' => 'si');

                   echo form_dropdown("protocollato[{$item['ID']}]", $options, $item['protocollato'], "class=\"protocollato\" id=\"protocollato{$item['ID']}\"");
                   ?>
           </div>
		<div class="field">
		         <label for="protocollo_struttura<?php echo $item['ID']; ?>">
				 <?php //query_link('protocollo_struttura', "Numero protocollo domanda"); ?>
				 <?php echo 'Numero protocollo domanda' ?>
				 </label>
                 <input type="text" class="protocollo_struttura" id="protocollo_struttura<?php echo $item['ID']; ?>" name = "protocollo_struttura[<?php echo $item['ID']; ?>]" value= <?php echo $item['protocollo_struttura'];?>>
				 <?php //echo form_input("protocollo_struttura[{$item['ID']}]",$item['protocollo_struttura'],"protocollo_struttura[{$item['ID']}]"); ?>

        </div>
		<div class="field">
		         <label for="fascicolo_struttura<?php echo $item['ID']; ?>">
				 <?php //query_link('fascicolo_struttura', "Numero fascicolo domanda"); ?>
				  <?php echo 'Numero fascicolo domanda' ?>
				 </label>
                 <input type="text" class="fascicolo_struttura"  id="fascicolo_struttura<?php echo $item['ID']; ?>" name = "fascicolo_struttura[<?php echo $item['ID']; ?>]" value= <?php echo $item['fascicolo_struttura'];?>>
				 <?php //echo form_input("fascicolo_struttura[{$item['ID']}]",$item['fascicolo_struttura'],"fascicolo_struttura[{$item['ID']}]"); ?>

        </div>
	  </td>






    </tr>




<?php

endforeach ?>



</table>
</form>

<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>

<div id="modal-fog"></div>


<div id="infoRequestModal" class="cth_modal">
  <div class="inner">
    <div class="loading infoRequestModal">
      <div class="inner">
        Invio in corso<hr />Attendere prego...
      </div>
    </div>
    <h3>S.I.S.M.A.2016. RICHIESTA INFORMAZIONI</h3>
    <form method="post" enctype="multipart/form-data" name="irm" id="irm" action="/admin/?<?php echo $_SERVER['QUERY_STRING']; ?>">
      <div id="irm-warning"></div>
      <input type="hidden" name="irm-esid" value="" class="esid" id="irm-esid"  />
      <div class="m-row">
        <!--
        <div class="m-field half">
          <div class="inner">
            <label for="irm-from">Mittente :</label>
            <input type="text" name="irm-from" id="irm-from"  />
          </div>
        </div>
        -->
        <div class="m-field">
          <div class="inner">
            <label for="irm-to">Destinatario :</label>
            <!-- <input type="text" class="dontUpdate" name="irm-to" id="irm-to"  /> -->
            <textarea class="dontUpdate" name="irm-to" id="irm-to"></textarea>
          </div>
        </div>
        <div class="m-field">
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
if (isset($irm_templates) && !empty($irm_templates)) {
    foreach ($irm_templates as $template_id => $template_name) {
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
            <textarea name="irm-text" id="irm-text" class="text"></textarea>
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





<div id="procSendModal" class="cth_modal">
  <div class="inner">
    <div class="loading procSendModal">
      <div class="inner">
        Invio in corso<hr />Attendere prego...
      </div>
    </div>
    <h3>S.I.S.M.A.2016. Avvio Istruttoria</h3>
    <form method="post" enctype="multipart/form-data" name="psm" id="psm" action="/admin/?<?php echo $_SERVER['QUERY_STRING']; ?>">
      <div id="psm-warning"></div>
      <input type="hidden" name="psm-esid" value="" class="esid" id="psm-esid"  />
      <div class="m-row">
        <!--
        <div class="m-field half">
          <div class="inner">
            <label for="psm-from">Mittente :</label>
            <input type="text" name="psm-from" id="psm-from"  />
          </div>
        </div>
        -->
        <div class="m-field">
          <div class="inner">
            <label for="psm-to">Destinatario :</label>
            <!-- <input type="text" class="dontUpdate" name="psm-to" id="psm-to"  /> -->
            <input type="text"  class="dontUpdate" name="psm-to" id="psm-to" />
          </div>
        </div>
        <div class="m-field">
          <div class="inner">
            <label for="psm-attachments">Allegati :</label>
            <input type="file" class="dontUpdate" name="psm_attachments[]" multiple />
          </div>
        </div>
      </div>

      <div class="m-row">
        <div class="m-field">
          <div class="inner">
            <label for="psm-subject">Oggetto :</label>
            <input type="text" class="dontUpdate" name="psm-subject" id="psm-subject"  />
          </div>
        </div>
      </div>
      <div class="m-row">
        <div class="m-field">
          <div class="inner">
            <label for="psm-attachments">Template :</label>
            <select id="psm-template" name="psm-template">
<?php
if (isset($psm_templates) && !empty($psm_templates)) {
    foreach ($psm_templates as $template_id => $template_name) {
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
            <textarea name="psm-text" id="psm-text" class="text"></textarea>
          </div>
        </div>
      </div>
      <div class="m-row">
        <div class="m-field half center">
          <a class="button cancel" id="psm-no">Annulla</a>
        </div>
        <div class="m-field half center">
          <a class="button ok" id="psm-si">Invia</a>
        </div>
      </div>
    </form>
  </div>
</div>

<?php
if (isset($pecs) && !empty($pecs)) {
    printf(
    '<script> window._pec_list = %s;</script>',
    json_encode($pecs)
  );
}
?>
