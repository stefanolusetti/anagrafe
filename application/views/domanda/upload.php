<div class="padded">
  <h2>Gentile <?php echo $titolare_nome . ' ' . $titolare_cognome; ?>,</h2>
<p>
  proceda nel caricare la scannerizzazione della sua carta di identità.
</p>
<p>
  Al termine dell'operazione la preghiamo di prendere nota del codice assegnato al documento:
dovr&agrave; esserre indicato nelle eventuali successive comunicazioni.
</p>
<p>
  <b>Attenzione:</b>
</p>
<ul style="font-size: 1em;">
  <li>
    Si consiglia di inviare un file in format <strong style="font-size: 1.2em;">pdf</strong>. Sono comunque accettati i seguenti formati: <strong style="font-size: 1.2em;">tiff, jpg, jpeg</strong>.
  </li>
  <li>
    Non saranno accettati altri documenti oltre alla carta d’identità.
  </li>
  <li>
    Verranno accettati solamente documenti inferiori ai <strong style="font-size: 1.2em;">2.5 MB</strong> di dimensione.
  </li>
  <li>
  È possibile caricare <strong style="font-size: 1.2em;">un unico file</strong> che dovrà contenere la scannerizzazione <strong style="font-size: 1.2em;">fronte e retro</strong> della propria carta d’identità in corso di validità.
  </li>
</ul>
</div>
<div id="searchbar">
    <?php echo form_open_multipart("domanda/upload/{$hash}") ?>
        <div class="fieldarea">
            <div class="field">
                <label for='submit'>Selezionare file:</label>
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
