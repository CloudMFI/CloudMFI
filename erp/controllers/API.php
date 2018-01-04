<?php defined('BASEPATH') OR exit('No direct script access allowed');

class API extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
		$this->load->model('api_model');
		$this->load->model('reports_model');
		$this->load->model('installment_payment_model');
		$this->load->model('settings_model');
		$this->load->model('sales_model');
		$this->load->model('companies_model');
		$this->load->model('accounts_model');
		$this->load->model('down_payment_model');
		$this->load->model('site');
        $this->load->model('db_model');
		$this->load->model('quotes_model');
		
    }

    function index()
    {
		$this->load->model('api_model'); 
		$setting = $this->api_model->getSettingCurrncies();
		$quotes = $this->api_model->GetquotesLists();
		$format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml';
		foreach($quotes as $quote){
			$array[] = array(
								"quote_id" 				=> $quote->id,
								"reference_no" 			=> $quote->reference_no,
								"group_loan" 			=> $quote->glname,
								"customer_name_en" 		=> $quote->customer_name_en,
								"customer_name_other" 	=> $quote->customer_name_other,
								"product_name" 			=> $quote->product_name,
								"quote_status" 			=> $quote->quote_status,
								"date" 					=> $quote->date,
								"approved_date" 		=> $quote->approved_date,
								"co_name" 				=> $quote->coname,
								"branch_name" 			=> $quote->branch_name,
								"currency_name" 		=> $quote->currency_name,
								"total" 				=> $this->erp->convertCurrency($setting->code, $quote->currency_code, $quote->total), 
							);
		}
		 
		if($format == 'json') {
			header('Content-type: application/json');
			echo json_encode($array);
		}
		else {
			header('Content-type: text/xml');
				echo '';
			foreach($array as $index => $post) {
				if(is_array($post)) {
					foreach($post as $key => $value) {
						echo '<',$key,'>';
						if(is_array($value)) {
							foreach($value as $tag => $val) {
								echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
							}
						}
					  echo '</',$key,'>';
					}
				}
			}
		}
    }
	
	function test(){
		if(isset($_GET['user']) && intval($_GET['user'])) {
		  /* soak in the passed variable or set our own */
		  $number_of_posts = isset($_GET['num']) ? intval($_GET['num']) : 10; //10 is the default
		  $format = strtolower($_GET['format']) == 'json' ? 'json' : 'xml'; //xml is the default
		  $user_id = intval($_GET['user']); //no default
		  /* connect to the db */
		  $link = mysql_connect('localhost','root','') or die('Cannot connect to the DB');
		  mysql_select_db('TEST',$link) or die('Cannot select the DB');
		  /* grab the posts from the db */
		  //$query = "SELECT post_title, guid FROM wp_posts WHERE post_author = 
		  //  $user_id AND post_status = 'publish' ORDER BY ID DESC LIMIT $number_of_posts";
		  $query = "SELECT * FROM `mfi_v1.1_git`.`erp_quotes`;";
		  $result = mysql_query($query,$link) or die('Errant query:  '.$query);
		  /* create one master array of the records */
		  $posts = array();
		  if(mysql_num_rows($result)) {
			while($post = mysql_fetch_assoc($result)) {
			  $posts[] = array('post'=>$post);
			}
		  }
		  /* output in necessary format */
		  if($format == 'json') {
			header('Content-type: application/json');
			echo json_encode(array('posts'=>$posts));
		  }
		  else {
			header('Content-type: text/xml');
			echo '';
			foreach($posts as $index => $post){
			  if(is_array($post)) {
				foreach($post as $key => $value) {
				  echo '<',$key,'>';
				  if(is_array($value)) {
					foreach($value as $tag => $val) {
					  echo '<',$tag,'>',htmlentities($val),'</',$tag,'>';
					}
				  }
				  echo '</',$key,'>';
				}
			  }
			}
			echo '';
		  }
		  /* disconnect from the db */
		  @mysql_close($link);
		}
	}
	
	function quotes()
    {
		$array = array(
						"username" => "Mkan Ko",
						"password" => "12345",
					  );
		$post = $array;
        echo json_encode($post); 
    }


}
