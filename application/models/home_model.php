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


   protected $mysqli;
   protected $sql1;
   protected $site_data = array();

   public function __construct() {
      parent::__construct();

      $this->table_data = 'table_data';
      $this->table_index = 'table_index';
      $this->sql1 = "SELECT * FROM {$this->table_index}";
      $this->mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');
   }

   public function get_latest_data() {
      $sql_result = $this->mysqli->query($this->sql1);
      $ii = 0;
      for ($ii; $ii<$sql_result->num_rows; $ii++) {
         $sql_data = $sql_result->fetch_assoc();
         if ($sql_data['site_name'] == 'uid') { // 사이트 이름이 'uid'이면 스킵한다.
            continue;
         }

         $sql2 = "SELECT * FROM {$this->table_data} WHERE id={$sql_data['last_data_id']}";
         $sql_result = $this->mysqli->query($sql2);
         $site_data = $sql_result->fetch_assoc();

      }
   }
}
