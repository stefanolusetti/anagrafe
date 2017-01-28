<?php

/**
 * 
 * Questo controller gestisce utenti e visualizza i relativi log.
 *
 * @author Certhidea
 * 
 * @since 0.1
 * 
 */
 
class Users extends CI_Controller {

    public function __construct() 
    {
        parent::__construct();
        
        $this -> load -> library('parser');
        $this -> load -> library('session');
        $this -> load -> library('pagination');
        $this -> load -> library('simpleuserauth');
		$this->load->library('encryption');
        
        $this -> load -> model('admin_model');
        $this -> load -> model('comuni_model');
        $this -> load -> model('dichiarazione_model');
        
        $this -> load -> helper('url');
        $this -> load -> helper('pdf');
        $this -> load -> helper('form');
        $this -> load -> helper('application');
            
    } 
    
    /**
     * 
     * index
     * 
     * Il metodo index è alias del del metodo lists
     */
    
    public function index() 
    {
        $this -> users();
    }
    
    /**
     * lists
     * 
     * Mostra l'elenco delle domande di iscrizione con i relativi controlli di istruttoria
     * 
     * @param int   $offset   Se fornito utilizzao per la paginazione
     * 
     */
    
    public function users() 
    { 
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $this -> simpleuserauth -> require_admin();
        
        $query = $this->db->get('users');
        $data['users'] = $query->result_array();
            
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('users/index', $data);
        $this -> load -> view('templates/footer');
    }
    
    public function delete($id = FALSE){
        
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $this -> simpleuserauth -> require_admin();
        
        if($id)
        {
            $this->db->delete('users', array('id' => $id));
        }
        $this -> users();
        return;
    }
    
/*    
    public function add(){
        $this -> simpleuserauth -> require_admin();
        
        $params = $this->input->post(NULL, TRUE);
        $items = $params['name'];
        if($items)
        {
            foreach ($items as $key => $value) {
                $update = array(
                                'name' => $params['name'][$key],
                                'pwd' => $params['pwd'][$key],
                                'admin' => array_key_exists($key, $params['admin']) ? 1 : 0
                                );
                 $this -> db
                            ->where('id', $key)
                            ->update('users', $update);
            }   
        }
        $this -> users();
    }
*/
  
    public function nuovo($id=FALSE){
	  if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
			
        /*$this -> simpleuserauth -> require_admin();
        $data = array('name' => 'nuovoutente', 'pwd' => crypt('testrer'));
        $this->db->insert('users', $data);
        $this -> users();
		
		
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('users/nuovo');
        $this -> load -> view('templates/footer');*/
	  
        $this -> simpleuserauth -> require_admin();
        
        if($this->input->post())
        {
            $this->load->library('form_validation');
            if($this -> form_validation -> run('utente') == TRUE)
            {
                $data = array(  'name' => $this->input->post('nome'), 
                    //'pwd' => crypt($this->input->post('password')),
					'pwd' => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
                    'admin' => $this->input->post('admin') ? 1 : 0,
                    'attivo' => $this->input->post('attivo') ? 1 : 0
                    );
                
           
                    $this->db->insert('users', $data);
                
                $this -> users();
                return;
            }
        }
        //echo $salt;
        $this -> simpleuserauth -> require_admin();
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('users/nuovo');
        $this -> load -> view('templates/footer');
    
		
    }
   
    public function edit($id = FALSE){
        
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $this -> simpleuserauth -> require_admin();
        
        if($this->input->post())
        {
            $this->load->library('form_validation');
            if($this -> form_validation -> run('utente') == TRUE)
		
		//$salt = openssl_random_pseudo_bytes(22);
		//$salt = '$2a$%13$' . strtr(base64_encode($salt), array('_' => '.', '~' => '/'));
            {
                $data = array(  'name' => $this->input->post('nome'), 
                    //'pwd' => crypt($this->input->post('password')),
					'pwd' => password_hash($this->input->post('password'),PASSWORD_DEFAULT),
                    'admin' => $this->input->post('admin') ? 1 : 0,
                    'attivo' => $this->input->post('attivo') ? 1 : 0
                    );
                
                $query = $this->db->get_where('users', array('name' => $data['name']));
                $user = $query->result_array();
                if($user)
                {
                    $this->db
                            ->where('name', $data['name'])
                            ->update('users', $data);
                } else {
                    $this->db->insert('users', $data);
                }
                $this -> users();
                return;
            }
        }
        
        if($id)
        {
            $query = $this->db->get_where('users', array('id' => $id), 1, 0);
            $users = $query->result_array();
            if($users[0])
            {
                $_POST['nome'] = $users[0]['name'];
                //$_POST['password'] = $users[0]['pwd'];
                $_POST['admin'] = $users[0]['admin'] ? 'Yes' : 'No';
                $_POST['attivo'] = $users[0]['attivo'] ? 'Yes' : 'No';
            }
        }
        
        $this -> simpleuserauth -> require_admin();
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('users/edit');
        $this -> load -> view('templates/footer');
    }
    
    public function logs($offset = 0) 
    {
        if(ENVIRONMENT == 'development')   
            $this->output->enable_profiler(TRUE);
        
        $this -> simpleuserauth -> require_admin();
        
        $limit = 50;
        $config['per_page'] = $limit;
        $params = $this->input->get(NULL, TRUE);
        
        if(empty($params))
        {
            $data = $this -> Admin_model -> findlog(FALSE, $offset, $limit);
            $config['uri_segment'] = 3;
            $config['base_url'] = site_url('/users/logs/');
            $config['total_rows'] = $data['rowcount'];
            $this->pagination->initialize($config);
        } else 
        {
            unset($params['submit']);
            unset($params['per_page']);
            $params['created_at'] = partial_parse_date($params['created_at']); 
			$data = $this -> Admin_model -> findlog($params, $this->input->get("per_page"), $limit);
            $config['base_url'] = site_url("/users/logs/?user_id={$params['user_id']}&created_at={$params['created_at']}");
            $config['total_rows'] = $data['rowcount'];
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);
        }
            
        $this -> load -> view('elenco/header');
        $this -> load -> view('templates/headbar');
        $this -> load -> view('users/logs', $data);
        $this -> load -> view('templates/footer');
    }

}