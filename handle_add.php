<?php
  require_once('conn.php');

  $array = array();
  $array['username'] = $_POST['username'];
  $array['nickname'] = $_POST['nickname'];
  $array['comments'] = $_POST['comments'];
  $array['parent_id'] = $_POST['parent_id'];

  $username = $array['username'];
  $nickname = $array['nickname'];
  $comments = $array['comments'];
  $parent_id = $array['parent_id'];

  if (empty($comments)) {
    die('請輸入留言');
  }

  $stmt = $conn->prepare("INSERT INTO zoeGuava_comments(username, nickname, comments, parent_id) VALUES(?, ?, ?, ?)");
  $stmt->bind_param("ssss", $username, $nickname, $comments, $parent_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($stmt->affected_rows > 0) {
    
    $last_id = $stmt->insert_id;
    $new = $conn->prepare("SELECT * FROM zoeGuava_comments WHERE id = ?");
    $new->bind_param("i", $last_id);
    $new->execute();
    $new_result = $new->get_result();
    $new_row = $new_result->fetch_assoc();

    die (json_encode(array(
      'username' => $new_row["username"],
      'nickname' => $new_row["nickname"],
      'comments' => htmlspecialchars($new_row["comments"], ENT_QUOTES, 'utf-8'),
      'id' => $new_row["id"],
      'created_time' => $new_row["created_at"]
      ))
    );

  } else {
    echo '出錯原因：' . $conn->error;
  }
?>