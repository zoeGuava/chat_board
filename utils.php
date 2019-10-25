<?php
// 預設為未登入
$login = false;

// 編輯、刪除 的按鈕
function set_btn($target_id) {
  echo '
    <div class="set_btn">
      <a href="update.php?id=' . $target_id . '" class="btn_edit">編輯</a>
      <a href="delete.php?id=' . $target_id . '" class="btn_delete" data-comment-id="' . $target_id . '">刪除</a>
    </div>
  ';
}

// 留言欄位標題
$comment_title = '
  <div class="comment_title">
    <div class="title_nickname">暱稱</div>
    <div class="title_comments">留言內容</div>
    <div class="title_time">留言時間</div>
  </div>
';


?>