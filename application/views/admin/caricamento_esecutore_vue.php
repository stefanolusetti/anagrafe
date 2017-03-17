<ul class="submenu">
  <li> <a href="<?php echo site_url("/admin"); ?>">Istruttoria domande</a> </li>
  <?php if(is_admin()): ?>
  <li class="selected">Caricamento esecutore</li>
  <?php endif; ?>
</ul>

<div id="cthImporter"></div>
