<?php
  require_once('./conn.php');

  $array = array();
  $array['id'] = $_POST['id'];

  $id = $array['id'];

  // 驗證是否為本人
  // $certf_sql = "SELECT id FROM zoeGuava_comments WHERE ";
  // 拿 user_id 去判斷 或是用 session 的方式

  $stmt = $conn->prepare("DELETE FROM zoeGuava_comments WHERE id = ? OR parent_id = ?");
  $stmt->bind_param("ii", $id, $id);
  // $stmt->execute();

  if ($stmt->execute()) {
    // header("Location: ./index.php?page=1");
    die('成功傳過來執行惹ㄛ');
    // die(json_encode(array(
    //   'id' => $row["id"]
    //  )));
  } else {
    die("刪除失敗...");
  }

?>