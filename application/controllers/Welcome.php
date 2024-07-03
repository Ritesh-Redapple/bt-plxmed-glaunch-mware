<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		/** custom logger work start */
		$cotrollername = $this->router->fetch_class();
		$methodname = $this->router->fetch_method();
		$querystring = $this->input->server('QUERY_STRING');
		$getIp = $_SERVER['REMOTE_ADDR'];

		$filename = 'model_'.$methodname.'.txt';
		$filepath = 'assets/customlog/';
		$foldername = date('Y-m-d');
		$filecontent = json_encode(array('date'=>date('Y-m-d H:i:s'),'IP'=>$getIp,'controller'=>$cotrollername,'method'=>$methodname,'querystring'=> $querystring));
		if (!is_dir($filepath.$foldername)) 
		{
			mkdir($filepath . $foldername, 0777, TRUE);
		}
		$filecontent .= (file_exists($filepath.$foldername.'/'.$filename))?PHP_EOL.file_get_contents($filepath. $foldername.'/'.$filename):PHP_EOL.'';
		file_put_contents($filepath. $foldername.'/'.$filename,$filecontent);
		/** custom logger work end */
		$this->load->view('welcome_message');
	}
}
