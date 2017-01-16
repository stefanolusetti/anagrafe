<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

//
//  Basic401Auth
//  Version 0.1
//  Coded by Nathan Koch on 12-23-2008
//  Just add 
//
//      $this->load->library('basic401auth');
//      $this->Basic401Auth->require_login() 
//
//  anywhere you need basic authentication

class SimpleUserAuth
{

    function __construct()
    {
        $this->ci =& get_instance();
    }

    function authorize()
    {
        $username = $this -> ci -> input -> post('admin_user');
        $query = $this-> ci -> db-> get_where('users', array('name' => $username, 'attivo' => 1), 1, 0);
        $users = $query->result_array();
        
        //$user = $this->ci->config->item('admin_user');
        //$password = $this->ci->config->item('admin_pw');
        
        $post_pwd = $this -> ci -> input -> post('admin_pwd');
        
        if (($users) && ($users[0]['pwd'] == crypt($post_pwd, $users[0]['pwd']))) 
        {
            $this->ci->session->set_userdata(array('logged_in' => TRUE, 
                                                   'user_id' => $users[0]['ID']));
            if($users[0]['admin'] == 1)
                $this->ci->session->set_userdata(array('admin' => TRUE));
            
            $this->ci->session->set_userdata(array('tentativi' => 0));
            
            $data = array(
                      'user_id' => $users[0]['ID'],
                      'campo' => 'login',
                      'valore' => '1',
                      'created_at' => date("Y-m-d H:i:s"));
            $this->ci->db->insert('logs', $data);
            
            return TRUE;
            
        } else {
            
            if(($users) && ($users[0]['name']))
            {
                $i = $this->ci->session->userdata('tentativi') + 1;
                $this->ci->session->set_userdata(array('tentativi' => $i));
                
                $data = array(
                      'user_id' => $users[0]['ID'],
                      'campo' => 'login',
                      'valore' => '2',
                      'created_at' => date("Y-m-d H:i:s"));
                $this->ci->db->insert('logs', $data);
                
                
                if($i >= 5)
                {
                    $data = array('attivo' => 0);
                    $query = $this->ci->db
                                        ->where('name', $users[0]['name'])
                                        ->update('users', $data);
                    $this->ci->session->sess_destroy();
                    
                    $data = array(
                      'user_id' => $users[0]['ID'],
                      'campo' => 'login',
                      'valore' => '3',
                      'created_at' => date("Y-m-d H:i:s"));
                    $this->ci->db->insert('logs', $data);
                }
            }
            
            return FALSE;
            redirect(site_url("/admin/login"));
            exit();
        }
        
    }

    function require_login()
    {
        $logged_in = $this->ci->session->userdata('logged_in');
        if ( $logged_in != TRUE)
        {
            redirect(site_url("/admin/login"));
            exit();
        }
    }
    
    function require_admin(){
        $admin = $this->ci->session->userdata('admin');
        if ( $admin != TRUE)
        {
            $this->ci->session->sess_destroy();
            redirect(site_url("/admin/login"));
            exit();
        }
    }
    
    function force_logout()
    {
        
        $data = array(
                      'user_id' => $this->ci->session->userdata('user_id'),
                      'campo' => 'login',
                      'valore' => '4',
                      'created_at' => date("Y-m-d H:i:s"));
        $this->ci->db->insert('logs', $data);
        
        //$this->ci->session->set_userdata( array('logged_in' => FALSE) );
        $this->ci->session->sess_destroy();
        //redirect(site_url("/admin/login"));
        //exit();
    }
}
?>