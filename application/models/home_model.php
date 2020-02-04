<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define ("DEBUG", "ON");
// define ("DEBUG", "OFF");

function my_dump ($item, $data) {
	if(DEBUG === "ON") {
		echo date('[y-m-d H:i:s]') . ' DEBUG # ' . $item . " = ";
		var_dump($data).PHP_EOL;
	}
}

class Home_model extends CI_Model
{
   /**
     * 테이블명
     */
   protected $table_data;
   protected $table_index;


   // protected $mysqli;
   // protected $sql1;
   protected $site_data = array();

   public function __construct() {
      parent::__construct();

      $this->load->database();
      $this->table_data = 'table_data';
      $this->table_index = 'table_index';
      // $this->sql1 = "SELECT * FROM {$this->table_index}";
      // $this->mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');
   }

   public function get_latest_data() {
      // table_index 테이블에서 데이터를 모두 읽어온다. (사이트 이름을 키로 하여 정렬한다.)
      $this->db->order_by("site_name", "asc");
      $query = $this->db->get($this->table_index);
      $sql_result_1 = $query->result();
      $para['buoy_data'] = array();

      foreach ($sql_result_1 as $key => $value) {
         if ($value->site_name == 'uid') { // 사이트 이름이 'uid'이면 스킵한다.
            continue;
         }

         // table_index에서 읽어온 데이터에서 last_data_id를 이용하여 table_data에서 해당 사이트의 마지막 Email 자료를 읽어온다.
         $query = $this->db->get_where($this->table_data, array('id'=>$value->last_data_id));
         $result = $query->result();
         $data = $result[0];

         $info['site_name'] = $data->site_name;
         $info['serial_no'] = $data->serial_no;
         $info['date'] = $data->date;
         $info['time'] = $data->time;
         $info['latitude'] = $data->latitude;
         $info['longitude'] = $data->longitude;
         $info['wind_direction'] = $data->wind_direction;
         $info['wind_speed'] = $data->wind_speed;
         $info['air_temperature'] = $data->air_temperature;
         $info['battery'] = $data->battery;
         $info['subject'] = $data->subject;
         $layer1 = array ('depth1'=>$data->depth1, 'temperature1'=>$data->temperature1, 'salinity1'=>$data->salinity1, 'oxygen1'=>$data->oxygen1);
         $layer2 = array ('depth2'=>$data->depth2, 'temperature2'=>$data->temperature2, 'salinity2'=>$data->salinity2, 'oxygen2'=>$data->oxygen2);
         $layer3 = array ('depth3'=>$data->depth3, 'temperature3'=>$data->temperature3, 'salinity3'=>$data->salinity3, 'oxygen3'=>$data->oxygen3);
         $info['data'] = array ($layer1, $layer2, $layer3);
         if ($value->layer == 4) {
            $layer4 = array ('depth4'=>$data->depth4, 'temperature4'=>$data->temperature4, 'salinity4'=>$data->salinity4, 'oxygen4'=>$data->oxygen4);
            array_push ($info['data'], $layer4);
         }
         
         array_push ($para['buoy_data'], $info);
      }
      return $para;
   }
}
