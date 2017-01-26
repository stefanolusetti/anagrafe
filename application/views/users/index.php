<ul class="submenu">
    <li>
        <a href="<?php echo site_url("users/logs"); ?>">Logs</a>
    </li>
    <li class="selected">Utenti</li>
</ul>
<table class="elenco">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Admin?</th>
        <th>Attivo</th>
        <th></th>
    </tr>
<?php foreach ($users as $user): $id = $user['ID']; ?>
    <tr>
        <td><?php echo $id; ?></td>
        <td><?php echo $user['name']; ?></td>
        <td><?php echo $user['admin'] ? "*" : ""; ?></td>
        <td><?php echo $user['attivo'] ? "*" : ""; ?></td>
        <td>
            <?php echo anchor("users/edit/{$user['ID']}", "Edit"); ?> |
            <?php echo anchor("users/delete/{$user['ID']}", "Delete"); ?>
        </td>
    </tr>
<?php endforeach; ?>
</table>

<div class="box">
    <?php echo anchor("users/nuovo", "Nuovo utente", array('class' => 'button', 'title'=>'Crea nuovo utente')); ?>
</div>
