
<ul class="submenu">
    <li>
        <a href="<?php echo site_url("/admin"); ?>">Istruttoria</a>
    </li>
<?php if(is_admin()): ?>
	<li>
        <a href="<?php echo site_url("/admin/nascondi_imprese"); ?>">Imprese congelate</a>
    </li>
<?php endif; ?>	
	
	<li class="selected">Alert di istruttoria</li>
	   	<ul class="sub_submenu">
				<li class="selected">Alert scadenze</li>
				<li>
				<a href="<?php echo site_url("/admin/alert_piva_pubblicate"); ?>">PIVA doppie<br>pubblicate</a>
				</li>
				<li>
				<a href="<?php echo site_url("/admin/alert_piva_banca_dati"); ?>">PIVA doppie<br>in banca dati</a>
				</li>
		</ul>

</ul>


<h2>Dati scadenze DURC</h2>

<table class="elenco">
    <tr>
        <th class="21pc">Id Pratica</th>
		<th class="20pc">Ragione Sociale</th>
        <th class="21pc">Partita IVA</th>
		<th class="21pc">Codice Fiscale</th>
        <th class="25pc">DURC scaduto dal</th>
		
    </tr>
<?php foreach ($items_durc as $item): ?>
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
        <div class="scadenza_cf">
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
                <?php echo format_date($item['durc_scadenza']); ?>
            </div>
        </td>
    </tr>
<?php endforeach ?>
</table>

<h2>Dati scadenze Protesti</h2>

<table class="elenco">
    <tr>
		<th class="21pc">Id Pratica</th>
        <th class="20pc">Ragione Sociale</th>
        <th class="21pc">Partita IVA</th>
		<th class="21pc">Codice Fiscale</th>
        <th class="25pc">Controllo protesti scaduto dal</th>
		
    </tr>
<?php foreach ($items_protesti as $item): ?>
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
        <div class="scadenza_cf">
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
                <?php echo format_date($item['protesti_scadenza']); ?>
            </div>
        </td>
    </tr>
<?php endforeach ?>
</table>


<h2>Dati scadenze Antimafia</h2>

<table class="elenco">
    <tr>
		<th class="21pc">Id Pratica</th>
        <th class="20pc">Ragione Sociale</th>
        <th class="21pc">Partita IVA</th>
		<th class="21pc">Codice Fiscale</th>
        <th class="25pc">Antimafia scaduto dal</th>
		
    </tr>
<?php foreach ($items_antimafia as $item): ?>
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
        <div class="scadenza_cf">
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
                <?php echo format_date($item['antimafia_scadenza']); ?>
            </div>
        </td>
    </tr>
<?php endforeach ?>
</table>


<div id="paginazione">
    <?php echo $this->pagination->create_links(); ?>
</div>
