<div id="headbar">
    <ul>
       <!-- <li>
         <?php 
         $attr = array();
         $attr['title'] = "Domanda di iscrizione";
         if(current_controller('domanda'))
            $attr['class'] = 'selected';
         echo anchor('/domanda', 'Domanda di iscrizione', $attr); ?>
        </li>-->
        <li>
         <?php
         $attr = array();
         $attr['title'] = "Domanda di iscrizione";
         if(current_controller('domanda') && current_action('index')) {
           $attr['class'] = 'selected';
         }
         echo anchor('/domanda', 'Domanda di iscrizione', $attr); ?>
        </li>
        <li>
         <?php
         $attr = array();
         $attr['title'] = "Istruzioni per la compilazione";
         $attr['target'] = "_blank";
         if(current_controller('domanda') && current_action('help')) {
           $attr['class'] = 'selected';
         }
         echo anchor('/domanda/help', 'Istruzioni', $attr); ?>
        </li>
       <li>
         <?php 
         $attr = array();
         $attr['title'] = "Elenco";
		 $current_url =& get_instance();
		 
         if($current_url->router->fetch_method()=='iscritti')
            $attr['class'] = 'selected';
         echo anchor('/elenco/iscritti', 'Elenco', $attr); ?>
        </li>
		<!--
		 <li>
         <?php 
         $attr = array();
         $attr['title'] = "Iscritti provvisoriamente";
		 if($current_url->router->fetch_method()=='iscritti_provv')
            $attr['class'] = 'selected';
         echo anchor('/elenco/iscritti_provv', 'Iscritti provvisoriamente', $attr); ?>
        </li>-->
        <?php if(logged_in()): ?>
		<li>
         <?php 
         $attr = array();
         $attr['title'] = "In richiesta";
		 if($current_url->router->fetch_method()=='richiesta')
            $attr['class'] = 'selected';
         echo anchor('/elenco/richiesta', 'In richiesta', $attr); ?>
        </li>
        <!-- <li>
        <?php 
         $attr = array();
         $attr['title'] = "Caricamento esecutore";
         //if(current_controller('admin'))
	     if($current_url->router->fetch_method()=='caricamento_esecutore')
            $attr['class'] = 'selected';
         echo anchor('/admin/caricamento_esecutore', 'Caricamento esecutore', $attr); ?>
        </li>-->
		<li>
        <?php 
         $attr = array();
         $attr['title'] = "Domande cartacee";
         if(current_controller('admin'))
            $attr['class'] = 'selected';
         echo anchor('/admin/caricamento_esecutore', 'Domande cartacee', $attr); ?>
        </li>
        <?php endif;?>
        <?php if(is_admin()): ?>
         <li>
        <?php 
         $attr = array();
         $attr['title'] = "Utenti";
         if(current_controller('users'))
            $attr['class'] = 'selected';
         echo anchor('/users/users', 'Utenti', $attr); ?>
        </li>
        <?php endif;?>
    </ul>
        <div id="logout">
        <?php if(logged_in()): 
            $attr = array();
            $attr['title'] = "logout";
            echo anchor('/admin/logout', 'logout', $attr);
        endif;?>
        </div>
</div>

