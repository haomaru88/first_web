<?php
//	$new_line = '<br>';
	$new_line = "\n";

//	function my_dump ($data) {
//		global $new_line;
//
//		var_dump($data);
//		echo  "$new_line";
//	}

	function console_log ($data) {
		global $new_line;

		$now = date('Y-m-d H:i:s');
		printf ("{$now} : {$data}$new_line");
	}

	$table_index = 'test_index';
	$table_data = 'test_data';
	$myrow_id = 'id';
	$myrow_site = 'site';
	$myrow_next_uid = 'next_uid';
	$myrow_uid = 'uid';
	$myrow_date = 'date';
	$myrow_time = 'time';
	$myrow_body = 'body';

	$mysqli = new mysqli('localhost', 'juno', 'haomaru98', 'gematek_buoy');
	if ($mysqli->connect_errno) {
		console_log('ERROR Failed to connect to MySQL Errno=' . $mysqli->connect_errno . 'Error=' . $mysqli->error);
		exit;
	}

	$sql = "SELECT * FROM {$table_index}";
	if (!$sql_result = $mysqli->query($sql)) {
		console_log('ERROR query: ' . $sql . ' Errno=' . $mysqli->connect_errno . ' Error=' . $mysqli->error);
		exit;
	}

	if ($sql_result->num_rows === 0) {
		console_log('ERROR No data in ' . $table_index);
		exit;
	}

	// DB에서 Next Uid 정보를 얻어온다.
	$row = $sql_result->fetch_assoc();

	$hostname = "{imap.gmail.com:993/ssl}INBOX";
	$username = "testhaomaru@gmail.com";
	$password = "haomaru98";
//	$hostname = "{imap.gmail.com:993/imap/ssl/debug/readonly}AWS";
//	$username = "haomaru88@gmail.com";
//	$password = "wnsghglaso98!";

	// 메일서버에 접속한다.
	$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());

	// 지정된 메일함에서 uid를 사용하여 검색한다.
	$uid = $row[$myrow_next_uid];

	// 저장되었던 uid로 검색 범위를 지정하여 메일를 검색한다.
	$result = imap_fetch_overview($mbox,"{$uid}:*",FT_UID);

	// 저장되었던 uid보다 첫 메일의 uid가 크면 새 메일이 없는것으로 판단한다.
	if ($result[0]->uid < $uid) {
		console_log ("No New Messages!  nextuid={$uid}, result->uid={$result[0]->uid}");
		$sql_result->free();
		$mysqli->close();
		exit;
	}

//    // Next UID를 얻는다.
//	 	$status = imap_status($mbox, $hostname, SA_UIDNEXT );
//    $new_uid = $status->uidnext;

	$new_uid = 0;
	$my_query1 = 'SELECT * FROM test_data';
	$my_query2 = "INSERT $table_data ($myrow_uid, $myrow_date, $myrow_time, $myrow_body) VALUES ('%d','%s','%s','%s')";
	foreach ($result as $item) {
		$body = imap_body ($mbox, $item->uid, FT_UID);
		$body = trim($body);		// 메일 본문 마지막에 \r\n 을 삭제한다.
//		$body = $mysqli->real_escape_string($body);
		console_log($body);

		date_default_timezone_set("Asia/Seoul");
		$time = strtotime ($item->date);
		$t_date = date ("Y-m-d", $time);
		$t_time = date ("H:i:s", $time);

		//		 $subject = explode(' ', $item->subject);
		//		 $t_site = $subject[0];
		$t_uid = $item->uid;
		$new_uid = $t_uid + 1;
		$t_body = $body;

		$t_query = sprintf($my_query2, $t_uid, $t_date, $t_time, $t_body);
		$sql_result = $mysqli->query($t_query);
		if (!$sql_result) {
			console_log("ERROR t_query=$t_query, Errno=$mysqli->errno, Error=$mysqli->error");
		}
		else {
			console_log("OK INSERT t_query=$t_query");
		}
	}

	$my_query3 = "UPDATE $table_index SET next_uid='$new_uid' WHERE $myrow_site = '$myrow_uid'";
	$sql_result = $mysqli->query($my_query3);
	if (!$sql_result) {
		console_log("ERROR UPDATE my_query3='$my_query3' Errno=$mysqli->errno Error=$mysqli->error");
	}
	else {
		console_log("OK UPDATE my_query3='$my_query3'");
	}

	imap_close($mbox);
//	$sql_result->free();
	$mysqli->close();

