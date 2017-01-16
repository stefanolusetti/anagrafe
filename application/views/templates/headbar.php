<div id="headbar">
    <ul>
        <li>
         <?php 
         $attr = array();
         $attr['title'] = "Domanda di iscrizione";
         if(current_controller('domanda'))
            $attr['class'] = 'selected';
         echo anchor('/domanda', 'Domanda di iscrizione', $attr); ?>
        </li>
        <li>
         <?php 
         $attr = array();
         $attr['title'] = "Elenco iscritti";
         if(current_controller('elenco'))
            $attr['class'] = 'selected';
         echo anchor('/elenco', 'Elenco iscritti', $attr); ?>
        </li>
        <?php if(logged_in()): ?>
         <li>
        <?php 
         $attr = array();
         $attr['title'] = "Gestione domande";
         if(current_controller('admin'))
            $attr['class'] = 'selected';
         echo anchor('/admin', 'Gestione domande', $attr); ?>
        </li>
        <?php endif;?>
        <?php if(is_admin()): ?>
         <li>
        <?php 
         $attr = array();
         $attr['title'] = "Controllo accessi";
         if(current_controller('users'))
            $attr['class'] = 'selected';
         echo anchor('/users/logs', 'Controllo accessi', $attr); ?>
        </li>
        <?php endif;?>
    </ul>
        <div id="logout">
        <?php if(logged_in()): 
            $attr = array();
            $attr['title'] = "Gestione domande";
            echo anchor('/admin/logout', 'logout', $attr);
        endif;?>
        </div>
</div>
