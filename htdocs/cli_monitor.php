<?php
//	$new_line = '<br>';
	$new_line = "\r\n";

	function my_dump ($data) {
		var_dump($data);
		echo  "\r\n";
	}

	function console_log ($data) {
		echo "<script>console.log('ConsoleLog : ". $data."');</script><br/>";
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
	$sql = "SELECT * FROM {$table_index}";
	if (!$sql_result = $mysqli->query($sql)) {
		// Oh no! The query failed.
		echo "Sorry, the website is experiencing problems.";

		// Again, do not do this on a public site, but we'll show you how
		// to get the error information
		echo "Error: Our query failed to execute and here is why: $new_line";
		echo "Query: " . $sql . "$new_line";
		echo "Errno: " . $mysqli->errno . "$new_line";
		echo "Error: " . $mysqli->error . "$new_line";
		exit;
	}

	if ($sql_result->num_rows === 0) {
		// Oh, no rows! Sometimes that's expected and okay, sometimes
		// it is not. You decide. In this case, maybe actor_id was too
		// large?
		echo "We could not find a match for Next UID, sorry about that. Please try again.$new_line";
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

	$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());

	// 지정된 메일함에서 uid를 사용하여 검색한다.
	$uid = $row[$myrow_next_uid];

	// 저장되었던 uid로 검색 범위를 지정하여 메일를 검색한다.
	$result = imap_fetch_overview($mbox,"{$uid}:*",FT_UID);

	// 저장되었던 uid보다 첫 메일의 uid 보다 크면 새 메일이 없는것으로 판단한다.
	if ($result[0]->uid < $uid) {
		printf ("uid:{$uid}  result->uid:{$result[0]->uid} $new_line");
		echo "No New Messages!$new_line";
		$sql_result->free();
		$mysqli->close();
		exit;
	}

//    // Next UID를 얻는다.
//	 	$status = imap_status($mbox, $hostname, SA_UIDNEXT );
//    $new_uid = $status->uidnext;

	$new_uid = 0;
	$my_query1 = 'SELECT * FROM test_data';
	$my_query2 = "INSERT $table_data 
						($myrow_uid, $myrow_date, $myrow_time, $myrow_body) 
						VALUES ('%d', '%s', '%s', '%s')";
	foreach ($result as $item) {
		$body = imap_body ($mbox, $item->uid, FT_UID);

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
			echo 'ERROR!';
			echo "Errno: " . $mysqli->errno . "$new_line";
			echo "Error: " . $mysqli->error . "$new_line";
		}
		else {
			console_log($sql_result);
		}
		console_log('uid : ' . $new_uid);
	}

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
