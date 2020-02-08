<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web_monitor extends CI_Controller
{
	public function __construct() {
      parent::__construct();
	}

	public function index() {
		$this->load->model('home_model');
		$imsi = $this->home_model->get_latest_data();
		$imsi += ['content_filename' => 'home_body_first_page.php'];
		$this->load->view('home_header');
		$this->load->view('home_body', $imsi);
		// $this->load->view('home_inner_footer');
		// $this->load->view('home_footer');
	}
	
	public function chart1() {
		$this->load->model('home_model');
		$imsi = $this->home_model->get_latest_data();
		$imsi += ['content_filename' => 'chart1.php'];
		$this->load->view('home_header');
		$this->load->view('home_body', $imsi);
	}

	function get($id){
		echo '
		<!DOCTYPE html>
		<html>
			 <head>
				  <meta charset="utf-8"/>
			 </head>
			 <body>
				  토픽 '.$id.'
			 </body>
		</html>
		';
	}

	function ajax () {
		echo '
	<!doctype html>
	<html>
		<body>
			<article>
			</article>
	';
			echo "<input type='button' value='fetch' onclick='fetch(\"/test.txt\").then();'>
		</body>
	</html>";
	}
}
