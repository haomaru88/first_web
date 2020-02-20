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

   private function make_one_data ($data, $layer_value)
   {
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
      if ($layer_value == 4) {
         $layer4 = array ('depth4'=>$data->depth4, 'temperature4'=>$data->temperature4, 'salinity4'=>$data->salinity4, 'oxygen4'=>$data->oxygen4);
         array_push ($info['data'], $layer4);
      }

      return $info;
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

         $info = $this->make_one_data ($data, $value->layer);

         array_push ($para['buoy_data'], $info);
      }
      return $para;
   }

   private function make_one_chart_data ($data, $layer_value)
   {
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
      // $info['subject'] = $data->subject;

      $date_time = $data->date . ' ' . $data->time;
      // $info['depth'] = array ('date'=>$date_time, 'depth1'=>$data->depth1);
      $info['depth'] = array ('date'=>$date_time, 'depth1'=>$data->depth1, 'depth2'=>$data->depth2, 'depth3'=>$data->depth3);
      $info['temperature'] = array ('date'=>$date_time, 'temperature1'=>$data->temperature1, 'temperature2'=>$data->temperature2, 'temperature3'=>$data->temperature3);
      $info['salinity'] = array ('date'=>$date_time, 'salinity1'=>$data->salinity1, 'salinity2'=>$data->salinity2, 'salinity3'=>$data->salinity3);
      $info['oxygen'] = array ('date'=>$date_time, 'oxygen1'=>$data->oxygen1, 'oxygen2'=>$data->oxygen2, 'oxygen3'=>$data->oxygen3);

      if ($layer_value == 4) {
         $info['depth'] += ['depth4'=>$data->depth4];
         $info['temperature'] += ['temperature4'=>$data->temperature4];
         $info['salinity'] += ['salinity4'=>$data->salinity4];
         $info['oxygen'] += ['oxygen4'=>$data->oxygen4];
      }

      return $info;
   }

   public function get_one_year_data ($site) {
      $query = $this->db->get_where($this->table_index, array('site_name'=>$site));
      $result = $query->result();
      $para['layer'] = $result[0]->layer;

      $para += $this->get_latest_data();

      $prev_one_year = date_create();  // 현재 날짜를 얻는다.
      date_sub($prev_one_year, date_interval_create_from_date_string('6 months'));  // 현재 날짜에서 지정된 기간을 뺀다. 1년치 데이터를 얻는다.
      $target_day = date_format($prev_one_year, 'Y-m-d');   // 지정된 포멧으로 날짜를 변환한다.

      $this->db->order_by("date", "asc");
      $this->db->order_by("time", "asc");
      $query = $this->db->get_where($this->table_data, array('site_name'=>$site, 'date >='=>$target_day));
      $result = $query->result();
      $para['one_year_data'] = array();
      foreach ($result as $key => $data) {
         $info = $this->make_one_chart_data($data, $para['layer']);
         array_push ($para['one_year_data'], $info);
      }

      // $query->free_result();

      return $para;
   }
}

























