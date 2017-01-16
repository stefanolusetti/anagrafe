
<ul class="submenu">
    <li>
        <a href="<?php echo site_url("/admin"); ?>">Istruttoria</a>
    </li>
<?php if(is_admin()): ?>
	<li>
        <a href="<?php echo site_url("/admin/nascondi_imprese"); ?>">Imprese congelate</a>
    </li>
<?php endif;?>	
	<li class="selected">Alert di istruttoria</li>
	   	<ul class="sub_submenu">
				<li><a href="<?php echo site_url("/admin/alert_scadenze"); ?>">Alert scadenze</a></li>
				<li>
				<a href="<?php echo site_url("/admin/alert_piva_pubblicate"); ?>">PIVA doppie<br>pubblicate</a>
				</li>
				<li>
				<li class="selected">PIVA doppie<br>in banca dati</li>
				</li>
		</ul>
</ul>

<div id="searchbar">
<?php
$legenda = legenda();
$attr = array('method' => 'get');
    echo form_open('admin/alert_piva_banca_dati', $attr) ?>
        <div class="fieldarea">
            <?php f_text('id', 'ID', array('input' => array('size' => '1'))); ?>
            <?php f_text('titolare_nome', 'Titolare', array('input' => array('size' => '10'))); ?>
            <?php f_text('ragione_sociale', 'Ragione sociale', array('input' => array('size' => '10'))); ?>
            <?php f_text('sl_piva', 'P. IVA', array('input' => array('size' => '10'))); ?>
            <?php f_text('sl_cf', 'Codice Fiscale', array('input' => array('size' => '10'))); ?> 
     
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

<h2>Imprese con stessa partita IVA presenti in banca dati</h2>
<table class="elenco">
    <tr>
        <th class="20pc">Id Pratica</th>
		<th class="20pc">Ragione Sociale</th>
		<th class="25pc">Titolare</th>
        <th class="21pc">Partita IVA</th>
		<th class="21pc">Codice Fiscale</th>
		<th class="20pc">Congelato</th>
       
		
    </tr>
<?php foreach ($items as $item): 

?>
    <tr>
		<td>
			<div class="azienda">
            <?php echo $item['id'];?>
			</div>
        </td>
        <td>
			<div class="azienda">
            <?php echo $item['ragione_sociale'];?>
			</div>
        </td>
		<td>
        <div class="azienda">
                <?php echo $item['titolare_nome']; ?>
            </div>
        </td>
        <td>
        <div class="azienda">
               <?php echo $item['sl_piva']; ?>
            </div>
        </td>
		 <td>
        <div class="scadenza_cf">
               <?php echo $item['sl_cf']; ?>
            </div>
        </td>
		 <td>
        <div class="azienda">
               <?php 
			   if ($item['hidden']==0)
			   echo "NO"; 
			   else 
			   echo "SI";?>
            </div>
        </td>
		
    </tr>
<?php endforeach ?>
</table>
