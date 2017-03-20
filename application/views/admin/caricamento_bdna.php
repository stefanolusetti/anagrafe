<ul class="submenu">
    <li> <a href="<?php echo site_url("/admin"); ?>">Istruttoria domande</a> </li>
<?php if(is_admin()): ?>
	<li>
        <a href="<?php echo site_url("/admin/caricamento_esecutore"); ?>">Caricamento esecutore</a>
    </li>
    <li class="selected">
          Caricamento esecutore CSV
      </li>
<?php endif; ?>

</ul>


<?php



?>
<h3>Procedere al caricamento del file CSV generato da BDNA</h3>
<div id="searchbar">
    <?php echo form_open_multipart("admin/caricamento_bdna") ?>
        <div class="fieldarea">
            <div class="field">
                <label for='submit'>Selezionare il file CSV generato da BDNA</label>
                <?php echo form_upload('userfile'); ?>
            </div>
            <div class="field">
                <label for='submit'>&nbsp</label>
                <?php echo form_submit('submit', 'Carica file'); ?>
            </div>
        </div>


 </form>
 <?php if(isset($msg)): ?>
    <div class="errors">
        <p>
            <?php echo $msg; ?>
        </p>
    </div>
<?php endif; ?>
<?php if(isset($msg_ok)): ?>
    <div class="no_errors">
        <p>
            <?php echo $msg_ok; ?>
        </p>
    </div>
<?php endif; ?>
</div>
