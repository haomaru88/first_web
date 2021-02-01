<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define ("OK", 0);
define ("NG", -1);

define ("DEBUG", "ON");
// define ("DEBUG", "OFF");

function my_dump ($item, $data) {
	if(DEBUG === "ON") {
		echo date('[y-m-d H:i:s]') . ' DEBUG # ' . $item . " = ";
		var_dump($data).PHP_EOL;
	}
}

function my_info($data) {
	echo date('[Y-m-d H:i:s]') . ' INFO = ' . $data . PHP_EOL;
}

function is_gematek_site_name_2019 ($name) {

   $gematek_site_name = array('AI51', 'AI52', 'AI53', 'AI57', 'AI59', 'ZI45');

   if (in_array($name, $gematek_site_name)) {
      return OK;
   }
   return NG;
}

function is_gematek_site_name_2020 ($name) {

   $gematek_site_name = array('AI53', 'AI57', 'AI58', 'AI59', 'AI60', 'AI61', 'AI65', 'AI66');

   if (in_array($name, $gematek_site_name)) {
      return OK;
   }
   return NG;
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
   }

   // 시스템 이름을 인자에 따라 구분하여 DB Table의 이름들을 설정한다.
   public function set_db_table($name) {
      if ($name == kind_system_name[0]) {
         $this->table_data = 'tdata_2019';
         $this->table_index = 'tindex_2019';
      }
      elseif ($name == kind_system_name[1]) {
         $this->table_data = 'tdata_2020';
         $this->table_index = 'tindex_2020';
      }
      else {
         my_info('argument Error!!');
         exit;
      }
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

   public function get_latest_data_2019() {
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

   public function get_latest_data_2020() {
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

   public function get_one_year_data_2019 ($site) {

      $query = $this->db->get_where($this->table_index, array('site_name'=>$site));
      $result = $query->result();
      $para['layer'] = $result[0]->layer;

      $para += $this->get_latest_data_2019();
      $prev_one_year = date_create("2019-12-02");
      $end_day = date_format($prev_one_year, 'Y-m-d');   // 지정된 포멧으로 날짜를 변환한다.
      // $prev_one_year = date_create();  // 현재 날짜를 얻는다.
      date_sub($prev_one_year, date_interval_create_from_date_string('1 week'));  // 현재 날짜에서 지정된 기간을 뺀다. 1년치 데이터를 얻는다.
      // date_sub($prev_one_year, date_interval_create_from_date_string('1 year'));  // 현재 날짜에서 지정된 기간을 뺀다. 1년치 데이터를 얻는다.

      $target_day = date_format($prev_one_year, 'Y-m-d');   // 지정된 포멧으로 날짜를 변환한다.

      $this->db->order_by("date", "asc");
      $this->db->order_by("time", "asc");
      $where_clause = array('site_name'=>$site, 'date >'=>$target_day, 'date <='=>$end_day);

      // 19-09-13 ~ 19-07-15 까지 지오시스템에서 지마텍 부이로 부터 받은 자료를 GMAIL로 송신하면서 serial_no를 '0'으로 처리하여서
      // 이에 대해 serial_no 값이 0 보다 큰 데이터를 선택한다.
      if (is_gematek_site_name_2019($site) == OK) {
         $where_clause['serial_no >'] = 0;
      }

      $query = $this->db->get_where($this->table_data, $where_clause);
      $result = $query->result();

      $para['one_year_data'] = array();
      foreach ($result as $key => $data) {
         $info = $this->make_one_chart_data($data, $para['layer']);
         array_push ($para['one_year_data'], $info);
      }

      $para['site'] = $site;
      // $query->free_result();

      return $para;
   }

   public function get_one_year_data_2020 ($site) {

      $query = $this->db->get_where($this->table_index, array('site_name'=>$site));
      $result = $query->result();
      $para['layer'] = $result[0]->layer;

      $para += $this->get_latest_data_2020();
      $prev_one_year = date_create("2020-10-29");
      $end_day = date_format($prev_one_year, 'Y-m-d');   // 지정된 포멧으로 날짜를 변환한다.
      // $prev_one_year = date_create();  // 현재 날짜를 얻는다.
      date_sub($prev_one_year, date_interval_create_from_date_string('7 days'));  // 현재 날짜에서 지정된 기간을 뺀다. 1년치 데이터를 얻는다.
      // date_sub($prev_one_year, date_interval_create_from_date_string('1 year'));  // 현재 날짜에서 지정된 기간을 뺀다. 1년치 데이터를 얻는다.

      $target_day = date_format($prev_one_year, 'Y-m-d');   // 지정된 포멧으로 날짜를 변환한다.

      $this->db->order_by("date", "asc");
      $this->db->order_by("time", "asc");
      $where_clause = array('site_name'=>$site, 'date >'=>$target_day, 'date <='=>$end_day);

      // 19-09-13 ~ 19-07-15 까지 지오시스템에서 지마텍 부이로 부터 받은 자료를 GMAIL로 송신하면서 serial_no를 '0'으로 처리하여서
      // 이에 대해 serial_no 값이 0 보다 큰 데이터를 선택한다.
      if (is_gematek_site_name_2020($site) == OK) {
         $where_clause['serial_no >'] = 0;
      }

      $query = $this->db->get_where($this->table_data, $where_clause);
      $result = $query->result();

      $para['one_year_data'] = array();
      foreach ($result as $key => $data) {
         $info = $this->make_one_chart_data($data, $para['layer']);
         array_push ($para['one_year_data'], $info);
      }

      $para['site'] = $site;
      // $query->free_result();

      return $para;
   }
}

























