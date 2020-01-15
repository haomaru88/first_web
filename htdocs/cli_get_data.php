<?php
const OK = 0;
const NG = -1;

define ("DEBUG", "ON");
// define ("DEBUG", "OFF");

function my_dump ($item, $data) {
	if(DEBUG === "ON") {
		echo date('[Y-m-d H:i:s] DEBUG = ') . $item . " ";
		var_dump($data);
		echo PHP_EOL;
	}
}

function my_info($data) {
	echo date('[Y-m-d H:i:s] INFO = ') . $data . PHP_EOL;
}

$table_index = 'table_index';
$table_data = 'table_data';
$myrow_id = 'id';
$myrow_last_uid = 'last_uid';
$myrow_site_name = 'site_name';
$myrow_table_name = 'table_name';
$myrow_layer = 'layer';
$myrow_last_data_id = 'last_data_id';
$myrow_update_datetime = 'update_datetime';
$myrow_remark = 'remark';

$myrow_depth = 'depth';
$myrow_terperature = 'temperature';
$myrow_salinity = 'salinity';
$myrow_oxygen = 'oxygen';
$myrow_battetry = 'battery';
$myrow_latitude = 'latitude';
$myrow_longitude = 'longitude';
$myrow_date = 'date';
$myrow_time = 'time';
$myrow_serial_no = 'serial_no';
$myrow_uid = 'uid';
$myrow_rssi = 'rssi';
$myrow_body = 'raw_data';
$myrow_wind_direction = 'wind_direction';
$myrow_wind_speed = 'wind_speed';
$myrow_air_temperature = 'air_temperature';

const index_4data_array = array (
	'site_name', 'serial_no', 'date', 'time', 'latitude', 'longitude',
	'wind_direction', 'wind_speed', 'air_temperature',
	'depth1', 'temperature1', 'salinity1', 'oxygen1',
	'depth2', 'temperature2', 'salinity2', 'oxygen2',
	'depth3', 'temperature3', 'salinity3', 'oxygen3',
	'depth4', 'temperature4', 'salinity4', 'oxygen4',
	'battery', 'remark', 'rssi', 'subject', 'body', 'uid', 'receive_date'
);

const index_3data_array = array (
	'site_name', 'serial_no', 'date', 'time', 'latitude', 'longitude',
	'wind_direction', 'wind_speed', 'air_temperature',
	'depth1', 'temperature1', 'salinity1', 'oxygen1',
	'depth2', 'temperature2', 'salinity2', 'oxygen2',
	'depth3', 'temperature3', 'salinity3', 'oxygen3',
	'battery', 'remark', 'rssi', 'subject', 'body', 'uid', 'receive_date'
);

$index_table_array = array (
	'last_uid', 'site_name', 'table_name', 'layer', 'last_data_id', 'update_datetime', 'remark'
);

const SITE_NAME_LIST = array ('ZI45', 'AI51', 'AI52', 'AI53', 'AI54', 'AI56',
										'AI57', 'AI58', 'AI59', 'AI60', 'AI61');




$start_time = time();

date_default_timezone_set("Asia/Seoul");

$hostname = "{imap.gmail.com:993/ssl}INBOX";
$username = "nfrdi.me02@gmail.com";
$password = "djwkdghksrud2510";
// $username = "gematektest@gmail.com";
// $password = "system1837";
$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());
my_dump('$mbox', $mbox);

// $my_criteria = 'ON "1 Aug 2019" SUBJECT "AI56"';
$my_criteria = 'BEFORE "2019-12-20" SINCE "2019-4-18"';
$s_result = imap_search($mbox, $my_criteria, SE_UID);

$subj_body;
foreach ($s_result as $item) {
	$body = imap_body ($mbox, $item, FT_UID);
	$body = trim($body);
	$header = imap_headerinfo ($mbox, imap_msgno($mbox, $item));
	$subject = trim ($header->subject);

	// Gmail에서 PDT 시간대의 날짜와 시간을 받아서 한국시간대로 변환한다.
	$r_date = strtotime($header->date);
	$r_date = date('Y-m-d H:i:s', $r_date);

	// 지오시스템 자료를 수정한다. (이유는 모르지만 $body 에 형식과 맞지 않는 문자가 포함되어 있음)
	$body1 = trim($body);
	$del_string = array ("\r", "\n", "=", ":");
	$body2 = str_replace($del_string, "", $body1);
	my_dump('$body2', $body2);

	$temp = array('uid'=>$item, 'subject'=>$subject, 'body'=>$body2, 'r_date'=>$r_date);
	$subj_body[] = $temp;


}
imap_close($mbox);


$db_host = 'localhost';
$db_dbname = 'gematek_buoy';
$pdo = new PDO ("mysql:host=$db_host;dbname=$db_dbname;", 'juno', 'haomaru98');

foreach ($subj_body as $value) {
	if (insert_data_table($value, $pdo) === NG) {
		continue;
	}

}





$end_time = time();

$st = new DateTime ("@$start_time");
$et = new DateTime ("@$end_time");
$interval  = date_diff($st, $et);
echo PHP_EOL . "Run Time : " . $interval->format('%h:%i:%s') . PHP_EOL;
?>
