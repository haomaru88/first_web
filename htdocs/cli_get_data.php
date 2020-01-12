<?php
const OK = 0;
const NG = -1;

define ("DEBUG", "ON");
// define ("DEBUG", "OFF");

function my_dump ($item, $data) {
	if(DEBUG === "ON") {
		echo $item . " : ";
		var_dump($data).PHP_EOL;
	}
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
	'battery', 'remark', 'rssi', 'raw_data'
);

const index_3data_array = array (
	'site_name', 'serial_no', 'date', 'time', 'latitude', 'longitude',
	'wind_direction', 'wind_speed', 'air_temperature',
	'depth1', 'temperature1', 'salinity1', 'oxygen1',
	'depth2', 'temperature2', 'salinity2', 'oxygen2',
	'depth3', 'temperature3', 'salinity3', 'oxygen3',
	'battery', 'remark', 'rssi', 'raw_data'
);

const SITE_NAME_LIST = array ('ZI45', 'AI51', 'AI52', 'AI53', 'AI54', 'AI56',
										'AI57', 'AI58', 'AI59', 'AI60', 'AI61');

function insert_data_table ($mail, $pdo) {
	$search_list = array("/", "_");
	$imsi_body1 = str_replace($search_list, " ", $mail['body']);
	$imsi_body2 = explode(" ", $imsi_body1);
	$site_name = $imsi_body2[0];
	$length = strlen($site_name);

	// 메일 본문의 첫 문자열의 길이가 5보다 크면 에러
	if (5 < $length || empty($site_name)) {
		return NG;
	}

	if ($length === 5) {
		$site_name = substr($site_name, 1);		// 첫번째 문자를 삭제한다.
	}

	$site_name = strtoupper($site_name);	// 대문자로 변환한다.

	// 정해진 사이트 이름이 아니면 에러
	if (!in_array($site_name, SITE_NAME_LIST)) {
		my_dump("'$site_name' ($site_name)", 'not found!!!');
		return NG;
	}

	// 기본은 3개층이고 ZI45는 4개층이다.
	$layer = 3;
	if (SITE_NAME_LIST[0] === $site_name) {
		$layer = 4;
	}

	// $stmt_index1 = $pdo->prepare("SELECT * FROM $table_index WHERE site_name = :site_name");
	$stmt_data1 = $pdo->prepare("
						INSERT INTO {$table_index} ({$myrow_site_name}, {$myrow_uid}, {$myrow_serial_no}, {$myrow_date}, {$myrow_time})
						VALUE (:{$myrow_site_name}, :{$myrow_uid}, :{$myrow_serial_no}, :{$myrow_date}, :{$myrow_time})
					");

	$index_array = index_3data_array;
	if (layer === 4) {
		$index_array = index_4data_array;
	}

	foreach ($imsi_body as $key => $item) {
		$stmt_data1->bindValue(":{$index_array[$key]}", $item);
	}
	my_dump('$stmt_data1', $stmt_data1);
	exit;


	$stmt_data1->execute();
	$row = $stmt_data1->fetch();
	
	
	$stmt_data1 = $pdo->prepare ("INSERT");
	my_dump('$row', $row	);
	exit;
	
	return OK;
}

$hostname = "{imap.gmail.com:993/ssl}INBOX";
$username = "gematektest@gmail.com";
$password = "system1837";
$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());
my_dump('$mbox', $mbox);

$my_query1 = 'SELECT * FROM test_data';
// $my_query2 = "INSERT $table_data 
// 					($myrow_uid, $myrow_date, $myrow_time, $myrow_body) 
// 					VALUES ('%d', '%s', '%s', '%s')";
$my_criteria = 'ON "1 Aug 2019" SUBJECT "AI51"';
$s_result = imap_search($mbox, $my_criteria, SE_UID);

// $uid_first = $s_result[0];
// $uid_last = $s_result[count($s_result)-1];
// $f_result = imap_fetch_overview($mbox,"{$uid_first}:{$uid_last}",FT_UID);
// my_dump('$f_result', $f_result);

$subj_body;
foreach ($s_result as $item) {
	$body = imap_body ($mbox, $item, FT_UID);
	$body = trim($body);
	$header = imap_headinfo ($mbox, iimap_msgnno($item));
	$temp = array('uid'=>$item->uid, 'subject'=>$item->subject, 'body'=>$body);
	my_dump('$temp', $temp);
	$subj_body[] = $temp;
}

$db_host = 'localhost';
$db_dbname = 'gematek_buoy';
$pdo = new PDO ("mysql:host=$db_host;dbname=$db_dbname;", 'root', 'haomaru98');
// $mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');

foreach ($subj_body as $value) {
	if (insert_data_table($value, $pdo) === NG) {
		continue;
	}

}


exit;






$my_query3 = "UPDATE $table_index SET next_uid='$new_uid' WHERE $myrow_site = 'uid'";
$sql_result = $mysqli->query($my_query3);
if (!$sql_result) {
	echo 'ERROR!';
	echo "Errno: " . $mysqli->errno . "$new_line";
	echo "Error: " . $mysqli->error . "$new_line";
}

printf("OK!!!$new_line");

imap_close($mbox);
//	$sql_result->free();
$mysqli->close();

?>
