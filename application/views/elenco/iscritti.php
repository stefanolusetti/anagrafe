<div id="searchbar">
    <?php 
    $attributes = array('method' => 'get');
	$current_url =& get_instance();
	if($current_url->router->fetch_method()=='iscritti'):
	echo form_open('elenco/iscritti', $attributes);
	elseif ($current_url->router->fetch_method()=='iscritti_provv'):
	echo form_open('elenco/iscritti_provv', $attributes);
	else:
	echo form_open('elenco/richiesta', $attributes);
	endif;

	?>
        <div class="fieldarea">
         
            
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '10'))); ?>
            <?php f_text('partita_iva', 'P. IVA', array('input' => array('size' => '11'))); ?>
			<?php f_text('codice_fiscale', 'Codice Fiscale', array('input' => array('size' => '15'))); ?>
			<div class="field"style="margin-right:10px">
                <label for="tipo_attivita">Tipo attività</label>
                <?php
                    $options = array('' => '-', 'interesse_lavori'=>'Lavori','interesse_forniture'=>'Forniture','interesse_servizi'=>'Servizi','interesse_interventi'=>'Interventi ex art.8');
                    echo form_dropdown('tipo_attivita', $options, $this -> input -> get('tipo_attivita'), 'id="tipo_attivita"');?>
            </div>
			
			
            <div class="field">
                <label>&nbsp;</label>
                <?php echo form_submit('submit', 'applica filtro'); ?>
             </div>
        </div>
    </form>
</div>


	

<table class="elenco">
    <tr>
        <th class="25pc">Ragione sociale</th>
        <th class="25pc">Partita IVA / Codice Fiscale</th>
		 <th class="25pc">Sede legale</th>
		 <th class="25pc">Provincia</th>
        <th class="25pc">Tipo attività</th>
	
       
    </tr>
<?php foreach ($statements as $item): ?>
    <tr>
        <td>
			<div class="azienda">
            <?php echo $item['ragione_sociale']; 
			
			?>
			</div>
		
			
        </td>
		
        <td>
		  <div class="azienda">
                <strong>P.IVA</strong> <?php echo $item['partita_iva']; ?>
            </div>
			<div class="azienda">
                <strong>Codice Fiscale</strong> <?php echo $item['codice_fiscale']; ?>
            </div>
           
        </td>
		 <td>
			<div class="azienda">
               <?php echo $item['sl_via'].' '.$item['sl_civico'].' - '.$item['sl_cap'].' '.$item['sl_comune'] ;?>
            </div>
        </td>
        <td>
			<div class="azienda">
               <?php echo $item['sl_prov']; ?>
            </div>
        </td>
		 <td>
			<div class="azienda">
               <?php 
			   if ($item['interesse_lavori']==1) {
				echo "-Lavori";
               	echo "<br>";
				}
				if ($item['interesse_servizi']==1) {
				echo "-Servizi";
				echo "<br>";
               	}
				if ($item['interesse_forniture']==1) {
				echo "-Forniture";
				echo "<br>";
               	}
				if ($item['interesse_interventi']==1) {
				echo "-Interventi di immediata riparazione";
				echo "<br>";
               	}
			    ?>
            </div>
            </div>
        </td>

    </tr>
<?php endforeach ?>
</table>
<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>
