<ul class="submenu">
    <li class="selected"> Istruttoria </li>
<?php if(is_admin()): ?>	
	<li>
        <a href="<?php echo site_url("/admin/nascondi_imprese"); ?>">Imprese congelate</a>
    </li>
<?php endif; ?>
	<li>
        <a href="<?php echo site_url("/admin/alert_scadenze"); ?>">Alert di istruttoria</a>
    </li>
<?php if(is_admin()): ?>	
	<li>
        <a href="<?php echo site_url("/admin/riepilogo"); ?>">Dati di riepilogo</a>
    </li>


    <li>
        <a href="<?php echo site_url("/admin/protocollo"); ?>">Configurazione protocollo</a>
    </li>
<?php endif; ?>
  </ul>

<div id="searchbar">
    <?php
    $legenda = legenda();
    $attr = array('method' => 'get');
    echo form_open('admin/index', $attr) ?>
	
        <div class="fieldarea">
            <?php f_text('id', 'ID', array('input' => array('size' => '1'))); ?>
            <?php f_text('titolare_nome', 'Titolare', array('input' => array('size' => '10'))); ?>
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '10'))); ?>
            <?php f_text('sl_piva', 'P. IVA', array('input' => array('size' => '10'))); ?>
            <?php f_text('sl_cf', 'Codice Fiscale', array('input' => array('size' => '10'))); ?> 
            <?php f_text('data_firma', 'Firma', array('input' => array('size' => '4'))); ?>
		</div>
		<div class="fieldarea">
            <?php //f_text('created_at', 'Generato', array('input' => array('size' => '4'))); ?>
            <?php //f_text('uploaded_at', 'Caricato', array('input' => array('size' => '4'))); ?>
			<div class="field">
			<?php 
			echo form_label('Pubblicato', 'pubblicato'); 
			$options =array(''=>'-','1'=>'Si','0'=>'No');
			$get_pubblicato = $this -> input -> get('pubblicato');
			echo form_dropdown ('pubblicato',$options,($get_pubblicato===false) ? '' : $get_pubblicato, 'id="pubblicato"');
			?>
			</div>
			<div class="field">
			<?php 
			echo form_label('Caricato', 'uploaded'); 
			$options = array(''=>'-','0'=>'No','1'=>'Si','2'=>'Non conforme');
			$get_uploaded = $this -> input -> get('uploaded');
			echo form_dropdown ('uploaded',$options,($get_uploaded===false) ? '' : $get_uploaded, 'id="uploaded"');
			?>
			</div>
			<div class="field">
			<?php 
			echo form_label('DURC', 'durc'); 
			$options = array(''=>'-','0'=>'Non fatta','1'=>'In attesa','2'=>'Regolare','3'=>'Non regolare','4'=>'Scaduto');
			$get_durc = $this -> input -> get('durc');
			echo form_dropdown ('durc',$options,($get_durc===false) ? '' : $get_durc, 'id="durc"');
			?>
			</div>
			<div class="field">
			<?php 
			echo form_label('Protesti', 'protesti'); 
			$options = array(''=>'-','0'=>'Non fatta','1'=>'In attesa','2'=>'Protestato','3'=>'Non protestato','4'=>'Scaduto');
			$get_protesti = $this -> input -> get('protesti');
			echo form_dropdown ('protesti',$options,($get_protesti===false) ? '' : $get_protesti, 'id="protesti"');
			?>
			</div>
			<div class="field">
			<?php 
			echo form_label('Antimafia', 'antimafia'); 
			$options = array(''=>'-','0'=>'Non fatta','1'=>'In attesa','2'=>'Nulla osta','3'=>'Ostativa','4'=>'Interdittiva','5'=>'Scaduta','6'=>'White List');
			$get_antimafia = $this -> input -> get('antimafia');
			echo form_dropdown ('antimafia',$options,($get_antimafia===false) ? '' : $get_antimafia, 'id="antimafia"');
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
        <th><?php query_link('id', "ID"); ?> / <?php query_link('ragione_sociale', "Ragione Sociale"); ?></th>
        <th></th>
        <th colspan="3"></th>
	</tr>
	
<?php foreach ($statements as $item): 

if ($item['hidden']==0) {
?>
		

	<tr>
	    <td class="id">
	        <a class="button ext" href="<?php echo site_url(array('admin', 'view', $item['id'])); ?>">PDF</a>
			<?php if ($item['uploaded']=='1') { ?>
			<a class="button orange ext" href="<?php echo base_url(array('uploads', $item['id']. "_" . get_year($item['data_firma']) . ".p7m")); ?>">File caricato</a>
	        <?php } elseif ($item['uploaded']=='2') {  
			?>
			<a class="button red ext" href="<?php echo base_url(array('uploads', $item['id']. "_" . get_year($item['data_firma']) . ".p7m")); ?>">File non conforme</a>
			<?php } ?>
			<a class="button red ext" href="<?php echo site_url(array('admin', 'load', $item['id'])); ?>">Load</a>
	        <!--<a class="button unlock">sblocca</a>-->
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
			
		</td>
        <td>(<?php echo $item['id']; ?>) <?php echo $item['ragione_sociale']; ?><br/>
            <?php query_link('titolare_nome', "Titolare"); ?>: <?php echo $item['titolare_nome']; ?><br/>
            <?php query_link('sl_piva', "p.iva"); ?>: <?php echo $item['sl_piva']; ?><br/>
			<label for='note_<?php echo $item['id']; ?>">'><b>Note</b></label><br/>
			 <?php 
			 $data = array(
              'name'        => "note[{$item['id']}]",
              'id'          => "note[{$item['id']}]",
			  'value'       => $item['note'],
              
              'cols'   => '50',
              'rows'        => '4',
           
            );

			echo form_textarea($data);



			 ?>
			
			</td>
        <td class="left"><?php query_link('data_firma', "Data firma"); ?>
            <?php echo format_date($item['data_firma']); ?><br/>
            <?php 
                if($item['created_at'] != "0000-00-00 00:00:00"):
                query_link('created_at', "generato il "); 
                echo format_date($item['created_at']);
                endif; 
            ?><br/>
            <?php 
                if($item['uploaded_at'] != "0000-00-00 00:00:00"):
                query_link('uploaded_at', "caricato il "); 
                echo format_date($item['uploaded_at']);
                endif; 
            ?> <br/>
			<?php 
                if(($item['published_at'] != "0000-00-00 00:00:00") and ($item['pubblicato'] == "1")) :
                query_link('published_at', "pubblicato il "); 
                echo format_date($item['published_at']);
                endif; 
            ?>	
			
        </td>
	    <td class="smallbox">
	        <div class="field">
	            <label for="uploaded_<?php echo $item['id']; ?>"><?php query_link('uploaded', "Caricato"); ?></label> 
                <?php  
                   //$options = array('0' => 'no', '1' => 'si', '2' => 'non conforme');
                   $options = $legenda['uploaded'];
                   echo form_dropdown("uploaded[{$item['id']}]", $options, $item['uploaded'], "class=\"uploaded\" id=\"uploaded_{$item['id']}\""); 
                   ?>
           </div>
           <div class="field">
                <label for="durc_<?php echo $item['id']; ?>"><?php query_link('durc', "DURC"); ?></label>
                <?php  
                   $options = $legenda['durc'];
					$id = $item['id'];
                   echo form_dropdown("durc[{$item['id']}]", $options, $item['durc'], "class=\"durc\" id=\"durc_{$item['id']}\""); 
                   ?>
	        </div>
	<div class="field">
		 <label for="durc_scadenza<?php echo $item['id']; ?>"><?php query_link ('durc_scadenza',"Data di scadenza (DURC)");?></label>
		 <?php if ((strtotime($item['durc_scadenza'])) < (strtotime(date("Y-m-d H:i:s"))) && (($item['durc_scadenza']) ) != '0000-00-00 00:00:00') { ?> 
		 <input type="text" style="background-color:red" id="durc_scadenza<?php echo $item['id']; ?>" name = "durc_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['durc_scadenza']); ?>>
		 <?php 
		
		 } else { ?>
		 <input type="text" id="durc_scadenza<?php echo $item['id']; ?>" name = "durc_scadenza[<?php echo $item['id']; ?>]" value= <?php echo format_date($item['durc_scadenza']);?>>
		 <?php }
		 ?>
		</div>
	</td>
        <td class="smallbox">
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
        </td>

		
    </tr>
	

	
	
<?php 
}
endforeach ?>
	
	
	
</table>
</form>
			
<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>
