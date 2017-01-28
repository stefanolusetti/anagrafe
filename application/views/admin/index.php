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
            <?php f_text('ID', 'ID', array('input' => array('size' => '1'))); ?>
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '10'))); ?>
            <?php f_text('partita_iva', 'P. IVA', array('input' => array('size' => '10'))); ?>
            <?php f_text('codice_fiscale', 'Codice Fiscale', array('input' => array('size' => '10'))); ?> 
		
            <?php //f_text('created_at', 'Generato', array('input' => array('size' => '4'))); ?>
            <?php //f_text('uploaded_at', 'Caricato', array('input' => array('size' => '4'))); ?>
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
			echo form_label('Mostra', 'mostra'); 
			$options = array('25'=>'25','50'=>'50','100'=>'100','5000'=>'Tutte');
			$get_mostra = $this -> input -> get('mostra');
			if ($get_mostra=='')
			$get_mostra=$this -> input -> get('per_page');
			echo form_dropdown ('mostra',$options,($get_mostra===false) ? '' : $get_mostra,'id="mostra"');
			?>
			</div>
			<div class="field">
                <label>&nbsp;</label>
                <?php echo form_submit('submit', 'filtra'); ?>
             </div>
        </div>
    </form>
</div>
<? echo form_open('admin/update') ?>
<table class="elenco admin">
	<tr>
	    <th class="id"></th>
        <th><?php query_link('ID', "ID"); ?> / <?php query_link('ragione_sociale', "Ragione Sociale"); ?></th>
        <th></th>
        <th colspan="3"></th>
	</tr>
	
<?php foreach ($statements as $item): 

?>
		

	<tr>
	    <td class="id">
	       
	        <!--
			<label for="unlock_<?php echo $item['id']; ?>"><a class="button unlock" name="<?php echo "{$item['id']}"; ?>" id="<?php echo "button[{$item['id']}]"; ?>">sblocca</a></label>
	        <br>
			<?php if(($item['pubblicato'] == "1")) : ?>
			<label for="spec_<?php echo $item['id']; ?>"><a class="button spec" title="PEC spubblicazione" name="<?php echo "{$item['id']}"; ?>" id="<?php echo "button_spec[{$item['id']}]"; ?>">AVVISO DI SPUBBLICAZIONE</a></label>
			<div id="finestra_spec" title="AVVISO DI SPUBBLICAZIONE">
			
			</div>
			
			<?php else: ?>
			<label for="pec_<?php echo $item['id']; ?>"><a class="button pec" title="PEC pubblicazione" name="<?php echo "{$item['id']}"; ?>" id="<?php echo "button_pec[{$item['id']}]"; ?>">AVVISO DI PUBBLICAZIONE</a></label>
			
			<div id="finestra_pec" title="AVVISO DI PUBBLICAZIONE">
			
			</div>
			<label for="pec_np<?php echo $item['id']; ?>"><a class="button pecnp" title="PEC non pubblicazione" name="<?php echo "{$item['id']}"; ?>" id="<?php echo "button_pecnp[{$item['id']}]"; ?>">AVVISO DI NON PUBBLICAZIONE</a></label>
			
			<div id="finestra_pec_np" title="AVVISO DI NON PUBBLICAZIONE">
			
			</div>
			
			<?php endif; ?>
			
			<div id="finestra_messaggio" title="ESITO INVIO PEC">
			
			</div>
			
			<br>
			<?php if(is_admin()): ?>
			<?php if ($item['hidden']=='0') { ?>
			<a class="button blue ext" href="<?php echo site_url(array('admin', 'nascondi_imprese',$item['id'])); ?>">NASCONDI</a>
			<?php } elseif ($item['hidden']=='1') { ?>
			<a class="button blue ext" href="<?php echo site_url(array('admin')); ?>">RIPRISTINA</a>
			<?php } 
			endif;
			?>
			
		</td>-->
		<td class="id">
		<?php if ($item['is_digital']=='1') { ?>
		 <a class="button red ext" href="<?php echo site_url(array('admin', 'view', $item['ID'])); ?>">Scarica PDF</a>
		  <a class="button red ext" href="<?php echo site_url(array('admin', 'view', $item['ID'])); ?>">Scarica CSV DIA</a>
			<?php if ($item['uploaded']=='1') { ?>
			<a class="button red ext" href="<?php echo base_url(array('uploads', $item['ID']. "_" . get_year($item['ID']) . ".p7m")); ?>">Scarica Carta di identità</a>
	        <?php } 
			?>
			<a class="button red ext" href="<?php echo site_url(array('admin', 'load', $item['ID'])); ?>">Vedi form compilata</a>
		<?php } ?>
		
		<label for="unlock_<?php echo $item['ID']; ?>"><a class="button unlock" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "button[{$item['ID']}]"; ?>">Attiva modifiche</a></label>
		<?php if ($item['stato']=='1') { ?>
		<a class="button green ext">Iscritto</a>
		
		
		
		 <?php } elseif ($item['stato']=='2') {  ?>
		 
		 <a class="button orange ext">Iscritto provvisoriamente</a>
		
		<?php } else { ?>
		
		<a class="button red ext">In richiesta</a>
		
		<?php }  ?>
		<!--	
		<label for="ap<?php echo $item['ID']; ?>"><a class="button red ext" title="Avvio procedimento" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "ap[{$item['ID']}]"; ?>">NOTIFICA AVVIO DEL PROCEDIMENTO</a></label>
		<label for="sol_pref_30_<?php echo $item['ID']; ?>"><a class="button red ext" title="Primo sollecito prefettura" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "sol_pref_30_[{$item['ID']}]"; ?>">PRIMO SOLLECITO PREFETTURA</a></label>
		<label for="sol_pref_75_<?php echo $item['ID']; ?>"><a class="button red ext" title="Secondo sollecito prefettura" name="<?php echo "{$item['ID']}"; ?>" id="<?php echo "sol_pref_75_[{$item['ID']}]"; ?>">SECONDO SOLLECITO PREFETTURA</a></label>
		-->
		</td>
		
        <td>(<?php echo sprintf("%05d",$item['ID']); ?>) <?php echo $item['ragione_sociale']; ?><br/>
		    <?php query_link('ragione_sociale', "Ragione Sociale"); ?>: <?php echo $item['ragione_sociale']; ?><br/>
			<?php query_link('ragione_sociale', "Forma giuridica"); ?></br>
            <?php query_link('partita_iva', "Partita IVA"); ?>: <?php echo $item['partita_iva']; ?><br/>
            <?php query_link('codice_fiscale', "Codice Fiscale"); ?>: <?php echo $item['codice_fiscale']; ?><br/>
			<?php query_link('sl_comune', "Sede legale"); ?>: <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'] ;?><br/>
			<?php query_link('ragione_sociale', "Email"); ?></br>
			<?php query_link('ragione_sociale', "PEC"); ?></br>
			<?php query_link('ragione_sociale', "Numero di telefono"); ?></br>
			
			
			</td>
        <td class="left">
           
            <?php 
                if($item['uploaded_at'] != "0000-00-00 00:00:00"):
                query_link('uploaded_at', "caricato il "); 
                echo format_date($item['uploaded_at']);
                endif; 
            ?> <br/>
			
			
        </td>
	    <td class="smallbox">
	        <div class="field">
	            <label for="stato<?php echo $item['ID']; ?>"><?php query_link('stato', "Stato"); ?></label> 
                <?php  
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['stato'];
                   echo form_dropdown("stato[{$item['ID']}]", $options, $item['stato'], "class=\"stato\" id=\"stato{$item['ID']}\""); 
                   ?>
           </div>
		   <!--
		     <div class="field">
	            <label for="avvio_proc_sent<?php echo $item['ID']; ?>"><?php query_link('avvio_proc_sent', "Avvio del procedimento"); ?></label> 
                <?php  
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['avvio_proc_sent'];
                   echo form_dropdown("avvio_proc_sent[{$item['ID']}]", $options, $item['avvio_proc_sent'], "class=\"avvio_proc_sent\" id=\"avvio_proc_sent{$item['ID']}\""); 
                   ?>
           </div>
		   -->
		     <div class="field">
	            <label for="uploaded<?php echo $item['ID']; ?>"><?php query_link('uploaded', "Carta di identità"); ?></label> 
                <?php  
                   $options = array('0' => 'no', '1' => 'si');
                   
                   echo form_dropdown("uploaded[{$item['ID']}]", $options, $item['uploaded'], "class=\"uploaded\" id=\"uploaded{$item['ID']}\""); 
                   ?>
           </div>
		     <div class="field">
	            <label for="digital<?php echo $item['ID']; ?>"><?php query_link('is_digital', "Tipo di invio"); ?></label> 
                <?php  
                   $options = array('0' => 'Cartaceo', '1' => 'Digitale');
                
                   echo form_dropdown("is_digital[{$item['ID']}]", $options, $item['is_digital'], "class=\"is_digital\" id=\"is_digital{$item['ID']}\""); 
                   ?>
           </div>
		   
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
	            <label for="wl<?php echo $item['ID']; ?>"><?php query_link('stmt_wl', "White list"); ?></label> 
                <?php  
                   $options = array('0' => 'no', '1' => 'si');
                   
                   echo form_dropdown("stmt_wl[{$item['ID']}]", $options, $item['stmt_wl'], "class=\"stmt_wl\" id=\"stmt_wl{$item['ID']}\""); 
                   ?>
           </div>
		<div class="field">
		         <label for="protocollo_struttura<?php echo $item['ID']; ?>"><?php query_link('protocollo_struttura', "Numero protocollo domanda"); ?></label>
                 <?php echo form_input("protocollo_struttura[{$item['ID']}]",$item['protocollo_struttura'],"protocollo_struttura[{$item['ID']}]"); ?>
                   
            </div>
	  </td>
	  
	  
	  
       <!-- <td class="smallbox">
            <div class="field">
                <label for="bis_<?php echo $item['id']; ?>"><?php query_link('5bis', "5bis"); ?></label>
                <?php  
                   $options = $legenda['5bis'];
                   echo form_dropdown("5bis[{$item['id']}]", $options, $item['5bis'], "class=\"5bis\" id=\"bis_{$item['id']}\""); 
                   ?>
            </div>
            <div class="field">
                <label for="protesti_<?php echo $item['id']; ?>"><?php query_link('protesti', "Protesti"); ?></label>
                <?php  
                   $options = $legenda['protesti'];
                   echo form_dropdown("protesti[{$item['id']}]", $options, $item['protesti'], "class=\"protesti\" id=\"protesti_{$item['id']}\""); 
                   ?>
            </div>
		
		<div class="field">
		 <label for="protesti_scadenza<?php echo $item['id']; ?>"><?php query_link ('protesti_scadenza',"Data di scadenza(Protesti)");?></label>
		 
		 <?php if ((strtotime($item['protesti_scadenza'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['protesti_scadenza']) ) != '0000-00-00 00:00:00') { ?> 
		 <input type="text" style="background-color:red" id="protesti_scadenza<?php echo $item['id']; ?>" name = "protesti_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['protesti_scadenza']); ?>>
		 <?php } else { ?>
		 <input type="text" id="protesti_scadenza<?php echo $item['id']; ?>" name = "protesti_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['protesti_scadenza']);?>>
		 
		 <?php 
		 }
		?>
		</div>
        </td>
        <td class="smallbox">
            <div class="field" style="min-height: 32px">
                <label for="pubblicato_<?php echo $item['id']; ?>"><?php query_link('pubblicato', "Pubblicato?"); ?></label>
                <?php echo form_checkbox('pubblicato', "pubblicato[{$item['id']}]", $item['pubblicato'], "id=\"pubblicato_{$item['id']}\""); ?>
            </div>
             <div class="field">
                <label for="antimafia_<?php echo $item['id']; ?>"><?php query_link('antimafia', "Antimafia"); ?></label>
                <?php
                   $options = $legenda['antimafia'];
                   echo form_dropdown("antimafia[{$item['id']}]", $options, $item['antimafia'], "class=\"antimafia\" id=\"antimafia_{$item['id']}\""); 
                ?>
            </div>
		<div class="field">
		<label for="antimafia_scadenza<?php echo $item['id']; ?>"><?php query_link ('antimafia_scadenza',"Data di scadenza(Antimafia)");?></label>
		 <?php if ((strtotime($item['antimafia_scadenza'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['antimafia_scadenza']) ) != '0000-00-00 00:00:00') { ?> 
		 <input type="text" style="background-color:red" id="antimafia_scadenza<?php echo $item['id']; ?>" name = "antimafia_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['antimafia_scadenza']);?>>
		<?php } else { ?>
		 <input type="text" id="antimafia_scadenza<?php echo $item['id']; ?>" name = "antimafia_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['antimafia_scadenza']);?>>
		
		
		<?php 
		}
		?>
		</div>
        </td>-->

		
    </tr>
	

	
	
<?php 

endforeach ?>
	
	
	
</table>
</form>
			
<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>
