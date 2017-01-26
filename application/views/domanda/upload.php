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
<ul>
  <li>
    Si consiglia di inviare un file in format pdf. Sono comunque accettati i seguenti formati: tiff, jpg, jpeg.
  </li>
  <li>
    Non saranno accettati altri documenti oltre alla carta d’identità.
  </li>
  <li>
    Verranno accettati solamente documenti inferiori ai 2.5 MB di dimensione.
  </li>
  <li>
  È possibile caricare <strong>un unico file</strong> che dovrà contenere la scannerizzazione <strong>fronte e retro</strong> della propria carta d’identità in corso di validità.
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
