<?php
  require_once('conn.php');

  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    die('有地方沒輸入資料');
  }

  $stmt = $conn->prepare("SELECT `password` FROM `zoeGuava_users` WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if (password_verify($password, $row['password'])) {

    session_start();
    session_regenerate_id(true);

    // echo 'successful~登入成功';
    // 亂數產生一個通行證 ID，並且在資料庫裡面記下通行證 ID 與會員 ID 的對應關係
    $user_id = session_id();

    $sql_search = "SELECT username FROM zoeGuava_certificates WHERE username='$username'";
    $result_search = $conn->query($sql_search);
    // 查詢是否為第一次登入
    if ($result_search->num_rows > 0) {
      // true->有登入過，更新 user_id
      $sql_certificate = "UPDATE zoeGuava_certificates SET user_id = '$user_id' WHERE username = '$username'";
    } else {
      // false->第一次登入，新增 user_id
      $sql_certificate = "INSERT INTO zoeGuava_certificates(user_id, username) VALUES('$user_id', '$username')";
    }
    $result_certificate = $conn->query($sql_certificate);
    setcookie("user_id", "$user_id", time()+3600*24);

    header('Location: ./index.php?page=1');
  } else {
    echo '帳號或密碼錯誤，請重新輸入' . $conn->error;
  }
?>