<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

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

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Home_model");
		$this->load->library('session');

	}

	function index(){
		$data = array();
		$this->commonLayoutView('home', $data, true);
	}

	public function pnglaunch()
	{
		$data = array();
		$params = $this->input->get();
		
		$provider_id = 17;
		$pid = $this->input->get('pid', TRUE);
        $gid  = $this->input->get('gid', TRUE);
        $channel = $this->input->get('channel', TRUE);
        $lang = $this->input->get('lang', TRUE);
        $practice = $this->input->get('practice', TRUE);
        $ticket  = $this->input->get('ticket', TRUE);
        $brand = $this->input->get('brand', TRUE);
        $origin = $this->input->get('origin', TRUE);
		$client_id = explode('-',$brand)[1];


		$chkuser_details = $this->Home_model->getUserDtlsByToken('PlayerToken',$ticket,$provider_id,$client_id);
		if(empty($chkuser_details))
		{
			$resultarr = json_encode([
				"status" => "error",
				"error"=> [
				  "scope"=> "user",
				  "no_refund"=>"1",
				  "message"=> "Token mismatched!"
				]
			]);
			return $resultarr;
		}

		$gamedetail = $this->Home_model->getGameDetailsbyCode($gid, $provider_id);
		if(empty($gamedetail))
		{
			$resultarr = json_encode([
				"status" => "error",
				"code"=> "1007",
				"message"=>"Game not found!"
			]);

			return $resultarr;
		}
		$provider_params = $this->Home_model->get_provider_params($client_id, $provider_id);
		$pparam = array();
		if (!empty($provider_params)) 
		{
			foreach ($provider_params as $provider_params) 
			{
				$pparam[$provider_params['field_key']] = $provider_params['field_value'];
			}
			
			//$data['stagecheck'] = $this->staging_check;
		}

		$query=""; $i=0;
		foreach ($params as $key => $value) {
			//echo $key.':'.$value;
			if($i==0) {
				$query .= urlencode($key)."=".$value;
			}else{
				$query .= "&".urlencode($key)."=".$value;
			}
			
			$i++;
		}

		$data['launchUrl'] = $pparam['provider_game_launch_url']; 
		$data['query'] = $query ; 
		
		
		$this->commonLayoutView('pnglaunch', $data, true);
	}
	/* public function index()
	{
		if ($this->session->userdata('session_data')) {

			$usercode = $this->session->session_data['usercode'];
			$token 	  = $this->session->session_data['token'];
			$secret_key = CLIENT_SECRET_KEY;
			$request_arr = array(
				'usercode' 		=> $usercode,
				'token' 		=> $token
			);
			$bodydata = json_encode($request_arr);

			$auth_key = md5($secret_key . $bodydata);

			//STAGING
			$url = CLIENT_API_URL.'/getaccountbalance';



			$header = array(
				'Content-Type: application/json',
				'Authorization:Bearer ' . $auth_key,
				'client_id:' . $this->client_id,
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_arr));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);


			$returndata = curl_exec($ch);
			curl_close($ch);
			$sendData = json_decode($returndata);
			
			$blncData = number_format($sendData->data->available_balance);
			$data['available_blnc'] = $blncData;
		}

		$data['lang_id'] = $this->session->userdata('lang_id');
		$data['categoriesArr'] = $this->getCategories($data['lang_id']);
		$data['providerArr'] = $this->getProviders($data['lang_id']);
		$provider_Arr = json_decode($data['providerArr'], true);
		//print_r($provider_Arr); die('sadf');
		$i = 0;
		$pr_data = array();
		foreach ($provider_Arr['data'] as $key => $pr) {

			if (in_array($pr['provider_id'], array("1", "2", "9", "25", "41","48"))) {

				$pr_data[$i]['pr_logo']    = $pr['provider_logo'];
				$pr_data[$i]['pr_name']    = $pr['provider_title'];
				$pr_data[$i]['pr_module_slug'] = "";
				$pr_data[$i]['pr_id']      = $pr['provider_id'];
				$i++;
			}

			if (count($pr['modules']) > 0) {
				foreach ($pr['modules'] as $rec) {

					if (!in_array($rec['module_id'], array("live", "slots", "others"))) {
						$pr_data[$i]['pr_logo']    = $rec['image'];
						$pr_data[$i]['pr_name']    = $pr['provider_title'];
						$pr_data[$i]['pr_module_slug'] = $rec['module_id'];
						$pr_data[$i]['pr_id']         = $pr['provider_id'];
						$i++;
					}
				}
			} elseif (!in_array($pr['provider_id'], array("1", "2", "9", "25","41","48")) && count($pr['modules']) == 0) {

				$pr_data[$i]['pr_logo']    = $pr['provider_logo'];
				$pr_data[$i]['pr_name']    = $pr['provider_title'];
				$pr_data[$i]['pr_module_slug'] = "";
				$pr_data[$i]['pr_id']         = $pr['provider_id'];
				$i++;
			}


			//$i++;
		}

		$data['pr_data'] 		=	$pr_data;
		$data['client_name'] 	=	$this->client_name;
		$data['client_id'] 		=	$this->client_id;

		// print_r($data) ;

		$this->load->view('home', $data);
	} */


	/*public function getGameUrl()
	{
		$data = $this->input->post();

		if (!empty($data)) {
			$mode 		= $data["mode"];
			$usercode 	= $data["usercode"];
			$game 		= $data["game"];
			$lang 		= "EN";
			$client_id 	= "$this->client_id";
			$return_url	= "https://bswb.plxmed.com/";
			$token 	 	= $data["token"];
			$secret_key = CLIENT_SECRET_KEY;

			$request_arr = array(
				'mode' 			=> $mode,
				'usercode' 		=> $usercode,
				'game' 			=> $game,
				'lang' 			=> $lang,
				'return_url' 	=> $return_url,
				'token' 		=> $token
			);
			$bodydata = json_encode($request_arr);

			$auth_key = md5($secret_key . $bodydata);
			
			$url = CLIENT_API_URL_NODE.'/getGameUrl';

			$header = array(
				'Content-Type: application/json',
				'Authorization:Bearer ' . $auth_key,
				'client_id:' . $this->client_id,
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_arr));
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			$getGameUrl = curl_exec($ch);
			curl_close($ch);
			$getGamesData = json_decode($getGameUrl);
			echo $getGameUrl;
			
		} else {
			echo 1;
		}
	} */

	
}
