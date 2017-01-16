<?php if(is_admin()): ?>
<ul class="submenu">
    <li>
        <a href="<?php echo site_url("/admin"); ?>">Istruttoria</a>
    </li>
	<li>
        <a href="<?php echo site_url("/admin/alert_scadenze"); ?>">Alert di istruttoria</a>
    </li>
	<li>
        <a href="<?php echo site_url("/admin/riepilogo"); ?>">Dati di riepilogo</a>
    </li>
    <li class="selected">Parametri Protocollo</li>
</ul>
<?php endif;
$query = $this->db->get('protocollazione_config');
$item =  $query->row_array();
echo form_open('admin/protocollo')
?>

<?php if(isset($msg)): ?>
    <div class="errors">
        <p>
            <?php echo $msg; ?>
        </p>
    </div>
<?php endif; ?>

<h2>Configurazione Postazione</h2>
<div class="field">
    <label>SettIn</label>
    <?php echo form_input("SettIn[{$item['SettIn']}]", $item['SettIn'] );?>
</div>

<div class="field">
    <label>ServIn</label>
    <?php echo form_input("ServIn[{$item['ServIn']}]", $item['ServIn'] );?>
</div>

<div class="field">
    <label>UOCIn</label>
    <?php echo form_input("UOCIn[{$item['UOCIn']}]", $item['UOCIn'] );?>
</div>

<div class="field">
    <label>UOSIn</label>
    <?php echo form_input("UOSIn[{$item['UOSIn']}]", $item['UOSIn'] );?>
</div>

<div class="field">
    <label>PostIn</label>
    <?php echo form_input("PostIn[{$item['PostIn']}]", $item['PostIn'] );?>
</div>

<div class="field">
    <label>Indice</label>
    <?php echo form_input("IdInd[{$item['IdInd']}]", $item['IdInd'] );?>
</div>

<h2>Configurazione Fascicolo</h2>

<div class="field">
    <label>Anno Fascicolazione</label>
    <?php echo form_input("AnnoFasc[{$item['AnnoFasc']}]", $item['AnnoFasc'] );?>
</div>

<div class="field">
    <label>Numero fascicolo</label>
    <?php echo form_input("ProgrFasc[{$item['ProgrFasc']}]", $item['ProgrFasc'] );?>
</div>

<div class="field">
    <label>Numero sottofascicolo</label>
    <?php echo form_input("Numsottofasc[{$item['Numsottofasc']}]", $item['Numsottofasc'] );?>
</div>

<div class="submit">
 <?php echo form_submit('submit', 'Salva i dati'); ?>
</div>
</form>
