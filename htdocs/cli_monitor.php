<?php

define ("DEBUG", "ON");
// define ("DEBUG", "OFF");

function my_dump ($item, $data) {
	if(DEBUG === "ON") {
		echo date('[y-m-d H:i:s]') . ' DEBUG # ' . $item . " = ";
		var_dump($data).PHP_EOL;
	}
}

function my_info($data) {
	echo date('[y-m-d H:i:s]') . " INFO  # " . $data . PHP_EOL;
}

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

function insert_data_table ($mail, $pdo) {
	global $index_table_array;

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
	$myrow_body = 'body';
	$myrow_subject = 'subject';
	$myrow_wind_direction = 'wind_direction';
	$myrow_wind_speed = 'wind_speed';
	$myrow_air_temperature = 'air_temperature';
	
	
	$imsi_body0 = str_replace(" ", "0", $mail['body']);
	$imsi_body1 = str_replace("_", "/", $imsi_body0);
	$imsi_body2 = explode("/", $imsi_body1);
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

	$index_array = index_3data_array;
	if ($layer === 4) {
		$index_array = index_4data_array;
	}

	$sql1 = '';
	$sql2 = '';
	foreach (index_4data_array as $key => $value) {
		$sql1 = $sql1 . $value . ', ';
		$sql2 = $sql2 . ':' . $value . ', ';
	}
	$sql1 = substr($sql1, 0, -2);
	$sql2 = substr($sql2, 0, -2);
	// my_dump('$sql1', $sql1);
	// my_dump('$sql2', $sql2);

	// $table_data 에 수신된 메일을 저장한다.
	$stmt_data1 = $pdo->prepare("INSERT INTO {$table_data} ({$sql1}) VALUE ({$sql2})");
	// my_dump('$stmt_data1', $stmt_data1);

	foreach ($imsi_body2 as $key => $item) {
		$stmt_data1->bindValue(":{$index_array[$key]}", $item);
		// my_dump('$index_array[$key]', $index_array[$key]);
		// my_dump('$item', $item);
	}

	if ($layer === 3) {
		$stmt_data1->bindValue (":depth4", '0');
		$stmt_data1->bindValue (":temperature4", '0');
		$stmt_data1->bindValue (":salinity4", '0');
		$stmt_data1->bindValue (":oxygen4", '0');
	}

	$stmt_data1->bindValue (":receive_date", $mail['r_date']);
	$stmt_data1->bindValue (":body", $mail['body']);
	$stmt_data1->bindValue (":uid", $mail['uid']);
	$stmt_data1->bindValue (":site_name", $site_name);
	$stmt_data1->bindValue (":subject", $mail['subject']);

	$r_result = $stmt_data1->execute();
	my_dump('$mail[\'body\']', $mail['body']);
	echo date('Y-m-d H:i:s : ') . "INSERT ($table_data) - ";
	print_r ($stmt_data1->errorInfo()[0]);
	echo PHP_EOL;

	$taskIdx = $pdo->lastInsertId();

	// 수신된 메일의 사이트가 $table_index에 검색하고 없거나 복수이면 ERROR 처리.
	$col = $index_table_array[1];
	$stmt_data2 = $pdo->prepare("SELECT * FROM {$table_index} WHERE {$col}='{$site_name}'");
	$result = $stmt_data2->execute();

	echo date('Y-m-d H:i:s : ') . "SELECT ($table_index) - ";
	print_r ($stmt_data2->errorInfo()[0]);
	echo PHP_EOL;

	$row = $stmt_data2->fetchAll();

	$id = $row[0]['id'];
	$cnt = count($row);
	if (!$result) {
		echo "ERROR : Not found ROW ({$site_name})!!!" . PHP_EOL;
		exit;
	} elseif (1 < $cnt) {
		echo "ERROR : Too many ROW ({$site_name}) , Count = {$cnt}!!!" . PHP_EOL;
		exit;
	}


	// $table_index 에 해당하는 사이트의 레코드에 데이터를 업데이트한다.
	$sql3 = '';
	foreach ($index_table_array as $key => $value) {
		$sql3 = $sql3 . $value . '=:' . $value . ', ';
	}
	$sql3 = substr($sql3, 0, -2) . ' ';
	$sql3 = $sql3 . "WHERE id=:rid";
	$sql4 = "UPDATE {$table_index} SET {$sql3}";
	$stmt_data2 = $pdo->prepare("$sql4");

	$stmt_data2->bindValue(":{$index_table_array[0]}", $id, PDO::PARAM_INT);
	$stmt_data2->bindValue(":{$index_table_array[1]}", $site_name, PDO::PARAM_STR);
	$stmt_data2->bindValue(":{$index_table_array[2]}", $table_data, PDO::PARAM_STR);
	$stmt_data2->bindValue(":{$index_table_array[3]}", $layer, PDO::PARAM_INT);
	$stmt_data2->bindValue(":{$index_table_array[4]}", $taskIdx, PDO::PARAM_INT);
	$stmt_data2->bindValue(":{$index_table_array[5]}", date('Y/m/d H:i:s', time()));
	$stmt_data2->bindValue(":$index_table_array[6]", ' ', PDO::PARAM_STR);
	$stmt_data2->bindValue(":rid", $id, PDO::PARAM_INT);

	$r_result = $stmt_data2->execute();

	echo date('Y-m-d H:i:s : ') . "UPDATE ($table_index) - ";
	print_r ($stmt_data2->errorInfo()[0]);
	echo PHP_EOL;


	// $table_index에서 'site_name'이 'uid'를 찾는다.
	$stmt_data2 = $pdo->prepare("SELECT * FROM {$table_index} WHERE site_name='uid'");
	$result = $stmt_data2->execute();
	$row = $stmt_data2->fetchAll();

	echo date('Y-m-d H:i:s : ') . "SELECT ($table_index) - ";
	print_r ($stmt_data2->errorInfo()[0]);
	echo PHP_EOL;

	$cnt = count($row);
	if (!$result) {
		echo "ERROR : Not found ROW (site_name = 'uid')!!!" . PHP_EOL;
		exit;
	} elseif (1 < $cnt) {
		echo "ERROR : Too many ROW ({$site_name}) , Count = {$cnt}!!!" . PHP_EOL;
		exit;
	}

	// 현재 Mail의 uid 보다 DB에 저장된 uid가 크면 DB에 현재 Mail의 uid를 저장한다.
	if ($row[0]['last_uid'] < $mail['uid']) {
		// Mail의 uid를 갱신하여 Back Task에서 uid를 이용하여 New Mail이 수신되었는지 판단할 수 있도록 한다.
		$id = $row[0]['id'];
		$stmt_data2 = $pdo->prepare("UPDATE $table_index SET last_uid={$mail['uid']} WHERE id=$id");
		$stmt_data2->execute();
		
		echo date('Y-m-d H:i:s : ') . "UPDATE ($table_index) - ";
		print_r ($stmt_data2->errorInfo()[0]);
		echo PHP_EOL;
	}

	return OK;
}

my_info('START');

$table_index = 'table_index';
$table_data = 'table_data';
$myrow_id = 'id';
$myrow_site = 'site_name';
$myrow_last_uid = 'last_uid';
$myrow_uid = 'uid';
$myrow_date = 'date';
$myrow_time = 'time';
$myrow_body = 'body';

$mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');
if($mysqli->connect_error) {
	my_info ("can't connect MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
	exit;
}

$sql = "SELECT * FROM {$table_index} WHERE $myrow_site='uid'";
$sql_result = $mysqli->query($sql);
if (!$sql_result) {
	my_info ("Sorry, the website is experiencing problems.");
	my_info ("Error: Our query failed to execute and here is why: ");
	my_info ("Query: " . $sql);
	my_info ("Errno: " . $mysqli->errno);
	my_info ("Error: " . $mysqli->error);
	exit;
}

if ($sql_result->num_rows !== 1) {
	my_info ("We could not find a match or too much matches for UID, sorry about that. Please try again." );
	my_dump ('$sql_result->num_rows', $sql_result->num_rows);
	exit;
}

// DB에서 uid 정보를 얻어온다.
$row = $sql_result->fetch_assoc();

my_info('$sql_result');

$hostname = "{imap.gmail.com:993/ssl}INBOX";
$username = "gematektest@gmail.com";
$password = "system1837";

// $username = "nfrdi.me02@gmail.com";
// $password = "djwkdghksrud2510";

// $username = "testhaomaru@gmail.com";
// $password = "haomaru98";

//	$hostname = "{imap.gmail.com:993/imap/ssl/debug/readonly}AWS";
//	$username = "haomaru88@gmail.com";
//	$password = "wnsghglaso98!";

$mbox = imap_open($hostname, $username, $password) or die("can't connect MySQL: " . imap_last_error());

// 지정된 메일함에서 uid를 사용하여 검색한다.
//	@ uid에 1을 더하여 next uid를 검색 조건으로 사용하면 imap_fetch_overview() 에서 무한루프에 빠진다.
$uid = $row[$myrow_last_uid];

// 저장되었던 uid로 검색 범위를 지정하여 메일를 검색한다.
$result = imap_fetch_overview($mbox,"{$uid}:*",FT_UID);

$sql_result->free();
$mysqli->close();

// 마지막 uid 로 검색하였으므로 새메일이 있다면 검색된 메일 갯수가 2개 이상이어야 한다.
if (count($result) <= 1) {
	my_info ('$uid=' . $uid . ', $result[0]->uid='. $result[0]->uid);
	my_info ("No New Messages!");
	exit;
}

// 첫번째 요소를 삭제한다. (첫번째 요소는 이전에 수신된 mail 이다)
array_splice ($result, 0, 1);

$db_host = 'localhost';
$db_dbname = 'gematek_buoy';
$pdo = new PDO ("mysql:host=$db_host;dbname=$db_dbname;", 'juno', 'haomaru98');

my_dump ('$result', $result);
foreach ($result as $key => $value) {
	$body = imap_body ($mbox, $value->uid, FT_UID);
	$body = trim ($body);
	$header = imap_headerinfo ($mbox, imap_msgno($mbox, $value->uid));
	$subject = trim ($header->subject);

	// Gmail에서 PDT 시간대의 날짜와 시간을 받아서 한국시간대로 변환한다.
	$r_date = strtotime($header->date);
	$r_date = date('Y-m-d H:i:s', $r_date);

	// 지오시스템 자료를 수정한다. (이유는 모르지만 $body 에 형식과 맞지 않는 문자가 포함되어 있음)
	$body1 = trim($body);
	$del_string = array ("\r", "\n", "=", ":");
	$body2 = str_replace($del_string, "", $body1);
	$argv = array ('uid'=>$value->uid, 'subject'=>$subject, 'body'=>$body2, 'r_date'=>$r_date);

	my_dump ('$body2', $body2);

	insert_data_table ($argv, $pdo);
}


my_info ("OK!!!");

imap_close($mbox);
//	$sql_result->free();
$mysqli->close();

?>
