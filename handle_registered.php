<?php
	require_once('conn.php');

  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  if (empty($username) || empty($nickname) || empty($password)) {
  	die('有地方沒輸入資料');
  }

  $stmt = $conn->prepare("INSERT INTO zoeGuava_users(username, nickname, password) VALUES(?, ?, ?)");
  $stmt->bind_param("sss", $username, $nickname, $password_hash);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // $stmt->affected_rows:
  // -1 -> error
  //  0 -> 沒有任何改變
  // >0 -> 有改變
  if ($stmt->affected_rows > 0) {
  	// echo 'successful~';
  	// 輸出一個 http 的 response header
  	header('Location: ./login.php');
  } else {
  	echo 'failed' . $conn->error;
  }
?>