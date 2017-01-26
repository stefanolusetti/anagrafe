<div id="open_data">
<?php echo "Se vuoi accedere all'elenco delle imprese iscritte in formato open clicca qui " ?>
<a href="<?php echo site_url("/elenco/open_data");?>" target="_blank">Open data</a><br>
</div>

<div id="open_data_details">
<?php echo "(se vuoi conoscere quali altri dati la Regione Emilia Romagna pubblica in formato aperto collegati al " ?>
<a href="http://dati.emilia-romagna.it/" target="_blank">Portale Regionale degli Open Data )</a>
</div>



<div id="searchbar">
    <?php 
    $attributes = array('method' => 'get');
    echo form_open('elenco/index', $attributes) ?>
        <div class="fieldarea">
            <div class="field" style="margin-right:10px">
                <div class="olabel">Contratto prevalente</div>
                <?php
                    //$options = array('' => '-', 'Edilizia' => 'Edilizia', 'Altri settori' => 'Altri settori');
                    //echo form_dropdown('tipo_contratto', $options, $this -> input -> get('tipo_contratto'));
                    
                    echo form_radio('tipo_contratto', 'Edilizia', 
                                    $this -> input -> get('tipo_contratto') == "Edilizia" ? TRUE : FALSE, 
                                    'id="tipo_contratto"');
                    //echo "Edilizia";
                    echo '<label for="tipo_contratto" class="inline">Edilizia</label>';
                    echo form_radio('tipo_contratto', 'Altri settori', 
                                    $this -> input -> get('tipo_contratto') == "Altri settori" ? TRUE : FALSE, 
                                    'id="tipo_contratto_altri"');
                    //echo "Altri settori";
                    echo '<label for="tipo_contratto_altri" class="inline">Altri settori</label>';
                ?>
            </div>
            
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '10'))); ?>
            <?php f_text('sl_piva', 'P. IVA', array('input' => array('size' => '10'))); ?>
			<div class="field"style="margin-right:10px">
                <label for="provincia">Provincia</label>
                <?php
                    $options = array('' => '-', 'BO' => 'Bologna', 'FE' => 'Ferrara', 'MO' => 'Modena', 'RE' => 'Reggio Emilia', 'RO' => 'Rovigo', 'MN' => 'Mantova', 'FC' => 'Forl&iacute;-Cesena', 'PR' => 'Parma', 'PC' => 'Piacenza', 'RA' => 'Ravenna', 'RN' => 'Rimini', 'altre' => 'Altre province');
                    echo form_dropdown('provincia', $options, $this -> input -> get('provincia'), 'id="provincia"');?>
            </div>
			
			<div class="field"style="margin-right:10px">
                <label for="ateco">Ateco</label>
               <?php
			    echo form_dropdown('ateco', opzioni_ateco(), $this -> input -> get('ateco'), 'id="ateco"');
			   
			   ?>
                    
            </div>
			
			
			<div class="field">
                <label for="soa">SOA</label>
                <?php
			    echo form_dropdown('soa', opzioni_soa(), $this -> input -> get('soa'), 'id="soa"');
			   
			   ?>
            </div>
			
			
            <div class="field">
                <label>&nbsp;</label>
                <?php echo form_submit('submit', 'applica filtro'); ?>
             </div>
        </div>
    </form>
</div>

<div class="fieldarea">
<div class="export" style="width:300px;margin-top:20px;">
        <a class="button  ext" href="<?php echo site_url(array('elenco', 'export_excel')); ?>">Esporta imprese pubblicate in Excel</a>
</div>
</div>


			 
			 
<div id="5bis" style="padding-left: 10px; padding-bottom: 10px;font-size: 12px;">
<?php //echo "* = Azienda con obbligo di iscrizione alla White list della Prefettura ove &egrave; ubicato il cantiere che opera per lavori di ricostruzione ai sensi della L. 122/2012 e s.m.i." ?>
</div>

<div id="5bis" style="padding-left: 10px; padding-bottom: 10px;font-size: 12px;">
<?php //echo "** = Azienda iscritta alla White List prefetture ai sensi della L. 122/2012 e s.m.i." ?>
</div>

<table class="elenco">
    <tr>
        <th class="20pc">Azienda</th>
        <th class="21pc">Riferimenti</th>
        <th class="25pc">Attestazioni SOA</th>
        <th class="25pc">Classifica Ateco</th>
    </tr>
<?php foreach ($statements as $item): ?>
    <tr>
        <td>
			<div class="azienda">
            <?php echo $item['ragione_sociale']; 
			//if ($item['5bis']=='1') echo " (*)"; 
			//if ($item['antimafia']=='6') echo " (**)";
			?>
			</div>
		
            <div class="azienda">
                <strong>P.IVA</strong> <?php echo $item['sl_piva']; ?>
            </div>
			<div class="azienda">
                <strong>Numero dipendenti</strong> <?php echo $item['addetti_n_dipendenti']; ?>
            </div>
            <div class="azienda">
                <strong>Anno inizio attivit&agrave;</strong> <?php echo $item['anno_inizio']; ?>
            </div> 
			<div class="azienda">
				<?php if(($item['published_at'] != "0000-00-00 00:00:00") and ($item['pubblicato'] == "1")) : ?>
				<strong>Data di pubblicazione</strong> 
				<?php 
				echo format_date($item['published_at']); 
				endif; 
				?>
			</div>
        </td>
        <td>
            <div class="riferimento">
                <?php echo $item['sl_comune']; ?> (<?php echo $item['sl_prov']; ?>) - <?php echo $item['sl_cap']; ?>
            </div>
            <div class="riferimento">
                via/piazza <?= $item['sl_via'] ?>  <?php echo $item['sl_civico']; ?>
            </div>
            <div class="riferimento">
                <strong>Telefono</strong> <?php echo $item['sl_tel']; ?>
            </div>
            <div class="riferimento">
                <strong>Email</strong> <?php echo $item['sl_email']; ?>
            </div>
        </td>
        <td>
                <?php 
				
				$soa_search=$this -> input -> get('soa');
				
				if ($soa_search) {
				list_soas($item['dichiarazione_id']); 
				}
				
				else {
					
				list_soas($item['id']); 	
				}
				
				
				
		
				
				?>
        </td>
        <td>
                <?php 
				
				$ateco_search= $this -> input -> get('ateco');
				if ($ateco_search) {
				list_atecos($item['dichiarazione_id']); 
				}
				
				else {
				
				list_atecos($item['id']); 
				
				}
				?>
        </td>
    </tr>
<?php endforeach ?>
</table>
<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>
