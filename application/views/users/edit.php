<?php echo form_open('users/edit') ?>

    <h2>Modifica utente</h2>
    <?php 
    f_text("nome", "Username", array('input' => array('class'=>'required maxlen')));
    f_password("password", "Password, minimo otto caratteri", array('input' => array('class'=>'required maxlen')));
    f_checkbox("admin", "Admin");
    f_checkbox("attivo", "Attivo");  
    ?>

<div class="submit">
    <input type="submit" name="submit" value="Modifica" />
    <?php echo anchor("users/users", "Annulla"); ?>
</div>