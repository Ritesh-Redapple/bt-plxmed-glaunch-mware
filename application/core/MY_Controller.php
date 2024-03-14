<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller  extends CI_Controller {
    public function __construct(){
        parent::__construct();
        // $this->load->library('user_agent');
     }

    public function commonLayoutView($viewPath,$viewData){
       
        $viewData['maincontent'] = $this->load->view($viewPath,$viewData,true);
       
        $this->load->view('layout/body',$viewData);
    
       
    }
    
}