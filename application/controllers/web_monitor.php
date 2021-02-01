<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// $IsLocalServer = TRUE;

const kind_system_name = array (
	'Oxygen2019', 'Oxygen2020'
);

class Web_monitor extends CI_Controller
{
	// 차트 각 층별 라인의 색깔
	public $chart_title_bg_color = [
		"#de0f00",
		"#c28800",
		"#000ed1",
		"#a600d4"
	];

	protected $system_name;

	public function __construct() {
      parent::__construct();
	}
	
	public function index() {

		$this->load->view('select');
		// $imsi = $this->home_model->get_latest_data();
		// $imsi += ['content_filename' => 'home_body_first_page.php'];
		// $imsi['sidebar_index'] = 0;
		// $imsi['server_ip'] = $this->get_server_ip();
		// $this->load->view('home_header');
		// $this->load->view('home_body', $imsi);
	}

	public function oxygen2019() {
		$this->home_model->set_db_table(kind_system_name[0]);

		$imsi = $this->home_model->get_latest_data_2019();
		$imsi += ['content_filename' => 'home_body_first_page_2019.php'];
		$imsi['sidebar_index'] = 0;
		$imsi['server_ip'] = $this->get_server_ip();
		$this->load->view('home_header');
		$this->load->view('home_body_2019', $imsi);
	}

	public function oxygen2020() {
		$this->home_model->set_db_table(kind_system_name[1]);

		$imsi = $this->home_model->get_latest_data_2020();
		$imsi += ['content_filename' => 'home_body_first_page_2020.php'];
		$imsi['sidebar_index'] = 0;
		$imsi['server_ip'] = $this->get_server_ip();
		$this->load->view('home_header');
		$this->load->view('home_body_2020', $imsi);
	}

	public function get_server_ip() {
		$serverIP = $_SERVER['SERVER_ADDR'];
		$ec2IP = 'http://15.165.214.117';
		if ($serverIP != "127.0.0.1" && $serverIP != "::1") {
			$serverIP = $ec2IP;
		}
		else {
			if ($serverIP == "::1") {
				$serverIP = "localhost";
			}
			$serverIP = 'http://'.$serverIP;
		}
		return $serverIP;
	}
	
	public function chart_2019() {
		$this->home_model->set_db_table(kind_system_name[0]);
		$site = $this->uri->rsegment(3, 100);
		$sb_index = $this->uri->rsegment(4, 99) + 1;
		if ($site==100 || $sb_index==100) {
			exit;
		}

		// $this->load->model('home_model');
		$imsi = $this->home_model->get_one_year_data_2019($site);
		$imsi += ['content_filename' => 'chart1.php'];
		$imsi['sidebar_index'] = $sb_index;
		$imsi['server_ip'] = $this->get_server_ip();

		$this->load->view('home_header');
		$this->load->view('home_body_2019', $imsi);
	}

	public function chart_2020() {
		$this->home_model->set_db_table(kind_system_name[1]);
		$site = $this->uri->rsegment(3, 100);
		$sb_index = $this->uri->rsegment(4, 99) + 1;
		if ($site==100 || $sb_index==100) {
			exit;
		}

		// $this->load->model('home_model');
		$imsi = $this->home_model->get_one_year_data_2020($site);
		$imsi += ['content_filename' => 'chart1.php'];
		$imsi['sidebar_index'] = $sb_index;
		$imsi['server_ip'] = $this->get_server_ip();

		$this->load->view('home_header');
		$this->load->view('home_body_2020', $imsi);
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
			echo "<input type='button' value='fetch' onclick='fetch(\"/test.txt\").then(console.log('TEST'));'>
		</body>
	</html>";
	}

	function phpinfo() {
		phpinfo();
	}
}
