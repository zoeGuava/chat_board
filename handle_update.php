<?php
  require_once('./conn.php');

  $comments = $_POST['comments'];
  $id = $_POST['id'];

  if(empty($comments) || empty($id)) {
    die('empty data');
  }

  $stmt = $conn->prepare("UPDATE zoeGuava_comments SET comments = ? WHERE id = ?");
  $stmt->bind_param("si", $comments, $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($stmt->affected_rows !== -1) { // 沒編輯的時候也要能送出
    header("Location: ./index.php?page=1");
  } else {
    echo $conn->error;
    die('failed. ' . $conn->error);
  }
?>