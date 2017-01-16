
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
				<li class="selected">PIVA doppie<br>pubblicate</li>
				</li>
			    <li>
				<a href="<?php echo site_url("/admin/alert_piva_banca_dati"); ?>"> PIVA doppie<br>in banca dati</a>
				</li>
		</ul>
</ul>


<h2>Imprese con stessa partita IVA pubblicate</h2>

<table class="elenco">
    <tr>
        <th class="20pc">Id Pratica</th>
		<th class="20pc">Ragione Sociale</th>
		<th class="25pc">Titolare</th>
        <th class="21pc">Partita IVA</th>
		<th class="21pc">Codice Fiscale</th>
       
		
    </tr>
<?php foreach ($items as $item): ?>
    <tr>
		<td>
			<div class="azienda">
            <?php echo $item['id'];?>
			</div>
        </td>
        <td>
			<div class="scadenza_azienda">
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
		
    </tr>
<?php endforeach ?>
</table>
