<h3>Gentile <?php echo $name . ' ' . $lastname; ?>, 
<br/> proceda nel caricare la propria dichiarazione PDF firmata digitalmente (in formato p7m).
Al termine dell'operazione la preghiamo di prendere nota del numero di protocollo assegnato al documento:
dovr&agrave; esserre indicato nelle eventuali successive comunicazioni.</h3>
<div id="searchbar">
    <?php echo form_open_multipart("domanda/upload/{$hash}") ?>
        <div class="fieldarea">
            <div class="field">
                <label for='submit'>Selezionare il pdf firmato</label>
                <?php echo form_upload('userfile'); ?>
            </div>
            <div class="field">
                <label for='submit'>&nbsp</label>
                <?php echo form_submit('submit', 'carica file'); ?>
            </div>
        </div>
        <?php if($error): ?>
            <div class="errors">
                <p>
                    <?php echo $error; ?>
                    <!--<?php echo $hash; ?>-->
                </p>
            </div>
        <?php endif; ?>
    </form>
</div>
