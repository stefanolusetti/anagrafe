<div class="padded">
  <h2>Gentile <?php echo $name . ' ' . $lastname; ?>,</h2>
  <p>
    L’istanza di iscrizione è terminate con successo.
  </p>
  <p>
    La preghiamo di prendere nota del codice assegnato al documento che dovrà essere indicato nelle eventuali successive comunicazioni:
  </p>
  <div style="text-align: center;">
    <span class="doc_code"><?php printf("%s-%s", $did, substr($doc_date, 0, 4)); ?></span>
  </div>
  <p>
    Riceverà all'indirizzo email pec da lei indicato il modulo da lei compilato.
  </p>
  <p>
    Cordiali Saluti,<br /><em>Struttura di Missione Prevenzione e Contrasto Antimafia Sisma</em>
  </p>
</div>
