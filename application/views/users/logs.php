<?php $legenda = legenda(); ?>
<ul class="submenu">
    <li class="selected">
        Logs
    </li>
    <li><a href="<?php echo site_url("users/users"); ?>">Utenti</a></li>
</ul>

<div id="searchbar">
    <?php 
    $attributes = array('method' => 'get');
    echo form_open('users/logs', $attributes) ?>
        <div class="fieldarea">
            <div class="field">
                <label for='created_at'>Data</label>
                <?php echo form_input(array('id' => 'created_at', 'name' => 'created_at', 'value' => $this -> input -> get('created_at'))); ?>
            </div>
            <div class="field">
                <label for='campo'>Target</label>
                <?php echo form_input(array('id' => 'target', 'name' => 'target', 'value' => $this -> input -> get('target'))); ?>
            </div>
            <div class="field">
                <label for='campo'>Campo</label>
                <?php echo form_input(array('id' => 'campo', 'name' => 'campo', 'value' => $this -> input -> get('campo'))); ?>
            </div>
            <div class="field">
                <label for='user_id'>Utente</label>
                <?php 
                $query = $this->db->get('users');
                $rows = $query->result_array();
                $options = array();
                $options[''] = '-';
                foreach($rows as $row){
                    $options[$row['ID']] = $row['name'];
                }
                echo form_dropdown('user_id', $options, $this -> input -> get('user_id'), "id='user_id'"); ?>
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
        <th>Data modifica</th>
        <th>Target</th>
        <th>Campo</th>
        <th>Nuovo valore</th>
        <th>Utente</th>
    </tr>
<?php foreach ($logs as $log): ?>
    <tr>
        <td><?php echo format_date($log['created_at']); ?></td>
        <td><?php echo $log['target']; ?></td>
        <td><?php echo $log['campo']; ?></td>
        <td><?php 
		if (($log['campo']!= 'durc_scadenza') && ($log['campo']!= 'protesti_scadenza') && ($log['campo']!= 'antimafia_scadenza'))
		echo $legenda[$log['campo']][$log['valore']]; 
		else
		echo ($log['valore']);
		
		?></td>
        <td><?php 
		if ($options[$log['user_id']]=='')
		echo "Utente cancellato";
		else 
		echo $options[$log['user_id']]; ?></td>
    </tr>
<?php endforeach; ?>
</table>
<div id="paginazione">
    <?php echo $this -> pagination -> create_links(); ?>
</div>