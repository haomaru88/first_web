<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web_monitor extends CI_Controller
{
	public function index(){
		$layer1 = array('depth1' => 1.1, 'temperature1' => 25.1, 'salinity1' => 33.1, 'oxygen1' => 9.1);
		$layer2 = array('depth2' => 2.1, 'temperature2' => 26.1, 'salinity2' => 34.1, 'oxygen2' => 9.2);
		$layer3 = array('depth3' => 3.1, 'temperature3' => 27.1, 'salinity3' => 35.1, 'oxygen3' => 9.3);
		$info1['site_name'] = '자란1 (AI51)';
		$info1['date'] = '2019/05/12';
		$info1['time'] = '12:04:33';
		$info1['battery'] = 9.7;
		$info1['data'] = array($layer1, $layer2, $layer3);

		$layer1 = array('depth1' => 4.1, 'temperature1' => 45.1, 'salinity1' => 43.1, 'oxygen1' => 4.1);
		$layer2 = array('depth2' => 4.2, 'temperature2' => 46.1, 'salinity2' => 44.1, 'oxygen2' => 4.2);
		$layer3 = array('depth3' => 4.3, 'temperature3' => 47.1, 'salinity3' => 45.1, 'oxygen3' => 4.3);
		$info2['site_name'] = '자란2 (AI56)';
		$info2['date'] = '2020/01/01';
		$info2['time'] = '16:24:03';
		$info2['battery'] = 10.7;
		$info2['data'] = array($layer1, $layer2, $layer3);

		$layer1 = array('depth1' => 5.1, 'temperature1' => 55.1, 'salinity1' => 53.1, 'oxygen1' => 5.1);
		$layer2 = array('depth2' => 5.2, 'temperature2' => 56.1, 'salinity2' => 54.1, 'oxygen2' => 5.2);
		$layer3 = array('depth3' => 5.3, 'temperature3' => 57.1, 'salinity3' => 55.1, 'oxygen3' => 5.3);
		$info3['site_name'] = '진동1 (AI53)';
		$info3['date'] = '2022/11/21';
		$info3['time'] = '06:04:13';
		$info3['battery'] = 11.7;
		$info3['data'] = array($layer1, $layer2, $layer3);

		$layer1 = array('depth1' => 6.1, 'temperature1' => 65.1, 'salinity1' => 63.1, 'oxygen1' => 6.1);
		$layer2 = array('depth2' => 6.2, 'temperature2' => 66.1, 'salinity2' => 64.1, 'oxygen2' => 6.2);
		$layer3 = array('depth3' => 6.3, 'temperature3' => 67.1, 'salinity3' => 65.1, 'oxygen3' => 6.3);
		$info4['site_name'] = '진동2 (AI54)';
		$info4['date'] = '2010/08/31';
		$info4['time'] = '23:44:38';
		$info4['battery'] = 12.7;
		$info4['data'] = array($layer1, $layer2, $layer3);

		$layer1 = array('depth1' => 7.1, 'temperature1' => 75.1, 'salinity1' => 73.1, 'oxygen1' => 7.1);
		$layer2 = array('depth2' => 7.2, 'temperature2' => 76.1, 'salinity2' => 74.1, 'oxygen2' => 7.2);
		$layer3 = array('depth3' => 7.3, 'temperature3' => 77.1, 'salinity3' => 75.1, 'oxygen3' => 7.3);
		$info5['site_name'] = '가막 (AI57)';
		$info5['date'] = '1977/10/55';
		$info5['time'] = '10:42:13';
		$info5['battery'] = 12.7;
		$info5['data'] = array($layer1, $layer2, $layer3);

		$layer1 = array('depth1' => 8.1, 'temperature1' => 85.1, 'salinity1' => 83.1, 'oxygen1' => 8.1);
		$layer2 = array('depth2' => 8.2, 'temperature2' => 86.1, 'salinity2' => 84.1, 'oxygen2' => 8.2);
		$layer3 = array('depth3' => 8.3, 'temperature3' => 87.1, 'salinity3' => 85.1, 'oxygen3' => 8.3);
		$layer4 = array('depth4' => 8.4, 'temperature4' => 88.1, 'salinity4' => 86.1, 'oxygen4' => 8.4);
		$info6['site_name'] = '하동 (ZI41)';
		$info6['date'] = '2000/01/01';
		$info6['time'] = '21:32:13';
		$info6['battery'] = 13.7;
		$info6['data'] = array($layer1, $layer2, $layer3, $layer4);

		$imsi['buoy_data'] = array($info5, $info1, $info2, $info3, $info4, $info6);
		
//		$this->load->model('home_model');
		
		$this->load->view('home_header');
		$this->load->view('home_body', $imsi);
		$this->load->view('home_inner_footer');
		$this->load->view('home_footer');
   }
}


