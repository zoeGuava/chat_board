<?php
	require_once('conn.php');
	include_once('utils.php');
?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="style.css">
		<title>留言板</title>
	</head>
	<body>
		<div class="wrapper">
			<div class="container">
				<div class="container_alert">
					<h3>本站為練習用網站，因教學用途刻意忽略資安的實作<br>註冊時請勿使用任何真實的帳號或密碼</h3>
				</div>
				<div class="container_add">
					<!-- 登入、登出 -->
					<form>
						<?php
							if(!isset($_COOKIE["user_id"])) {
							    echo '<div class="login_title">請先登入 or 註冊</div>';
							    echo '<div class="login_item">';
							    echo '<a href="login.php">登入</a>';
							    echo '<a href="registered.php">註冊</a>';
							    echo '</div>';
							} else {
									$user_id = $_COOKIE["user_id"];

								  // 把 certificates 資料庫裡面符合 cookie user_id 的 username 抓出來
								  $cookie_sql = $conn->prepare("
										SELECT username 
										from zoeGuava_certificates 
										where user_id = ?");
								  $cookie_sql->bind_param("s", $user_id);
								  $cookie_sql->execute();
								  $cookie_result = $cookie_sql->get_result();
								  $cookie_row = $cookie_result->fetch_assoc();
								  $cookie_username = $cookie_row['username'];

								  // 比對 users 資料庫裡面符合 cookie 所存帳號的 username, nickname 抓出來
								  $certificate_stmt = $conn->prepare("
										SELECT u.username, u.nickname 
										from zoeGuava_certificates as c
										LEFT JOIN zoeGuava_users as u
										ON u.username = ?");
								  $certificate_stmt->bind_param("s", $cookie_username);
								  $certificate_stmt->execute();
								  $certificate_result = $certificate_stmt->get_result();
								  $certificate_row = $certificate_result->fetch_assoc();

							    echo '<div>帳號：' . $certificate_row["username"] . '</div>';
							    echo '<div>暱稱：' . $certificate_row["nickname"] . '</div>';
							    echo '<input type="hidden" name="nickname" id="nickname" value="' . $certificate_row["nickname"] . '">';
							    echo '<input type="hidden" name="username" id="username" value="' . $certificate_row["username"] . '">';
							    echo '<input type="hidden" name="parent_id" id="parent_id" value=0>';
							    echo '留言內容：<textarea name="comments" rows="2" class="send_comments"></textarea>';
							    echo '<input class="send_comment_btn_main" type="submit" value="送出留言">';
							    $login = true;
							}
						?>
					</form>
					<form method="POST" action="handle_logout.php">
						<?php
						  if (isset($_COOKIE["user_id"])) {
						  	echo '<input type="submit" value="點此登出">';
						  }
						?>
					</form>
				</div>
				<div class="container_page">
					<?php
					  // 產生分頁
				    include('page.php');
					?>
				</div>
				<div class="container_list">
					<!-- 留言板 -->
					<?php
					  // $start 是在 page.php 裡面的 $start = ($page-1) * $limit;
					  // 所產生的是每一頁第一個留言在資料庫中的編號
						// $sql = 'SELECT * from zoeGuava_comments ORDER BY created_at DESC LIMIT ' . $start . ',' . $limit;
					  $stmt = $conn->prepare("
							SELECT *
							from zoeGuava_comments as c 
							WHERE c.parent_id = 0
							ORDER BY created_at DESC 
							LIMIT ? , ?");
					  $stmt->bind_param("ii", $start, $limit);
					  $stmt->execute();
					  $result = $stmt->get_result();

						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								echo '<div class="row">';
								echo '<div class="comment_head">';
								echo $comment_title;
								echo '<div class="comment_main">';
								echo '<div class="nickname">' . $row['nickname'] . '</div>';
								// 解決 XSS 問題，把輸出轉換為純文字
								echo '<div class="comments">' . htmlspecialchars($row['comments'], ENT_QUOTES, 'utf-8') . '</div>';
								echo '<div class="created_time">' . $row['created_at'] . '</div>';
								echo '</div>';
								// 如果欄位中的暱稱符合 cookie 中所存的；則在旁邊加上編輯、刪除的按鈕
								if ($login === true && $row['nickname'] === $certificate_row['nickname']) {
									// utils.php
									set_btn($row['id']);
								}

							  $stmt_sub = $conn->prepare("
									SELECT *
									from zoeGuava_comments as c 
									WHERE c.parent_id = ?
									ORDER BY created_at ASC");
							  $stmt_sub->bind_param("i", $row['id']);
							  $stmt_sub->execute();
							  $result_sub = $stmt_sub->get_result();
							  
								if ($result_sub->num_rows > 0) {
									echo '
										<div class="accordion" id="accordionComments_'.$row["id"].'">
										  <div class="card">
										    <div class="card-header" id="heading_'.$row["id"].'">
										      <button class="btn btn-link col" type="button" data-toggle="collapse" data-target="#collapse_'.$row["id"].'" aria-expanded="true" aria-controls="collapse_'.$row["id"].'">
										      	查看留言
										      </button>
										    </div>
										    <div id="collapse_'.$row["id"].'" class="collapse hide" aria-labelledby="heading_'.$row["id"].'" data-parent="#accordionComments_'.$row["id"].'">
										    	<div class="comment_head">	
									';
									while ($row_sub = $result_sub->fetch_assoc()) {
										if ($row_sub['nickname'] === $row['nickname']) {
											echo '<div class="comment_sub comment_parent">';
										} else {
											echo '<div class="comment_sub">';											
										}
										if ($login === true && $row_sub['nickname'] === $certificate_row['nickname']) {
											set_btn($row_sub['id']);
										}
										echo '
											<div>暱稱：'.$row_sub["nickname"].'</div>
											<div>內容：'.htmlspecialchars($row_sub["comments"], ENT_QUOTES, "utf-8").'</div>
											<div>留言時間：'.$row_sub["created_at"].'</div>
										';
										echo '</div>';
									}
									echo '</div></div></div></div>';
								} else {
									echo '<div class="comment_status">還沒有人回覆喔！</div>';
								}
								if(isset($_COOKIE["user_id"])) {
									echo '
										<form class="comment_reply">
											<div>帳號：' . $certificate_row["username"] . '</div>
											<div>暱稱：' . $certificate_row["nickname"] . '</div>
											<div>輸入留言內容：</div>
											<textarea name="comments" rows="5" cols="35" class="edit_comments"></textarea>
											<input type="hidden" name="nickname" value="' . $certificate_row["nickname"] . '">
											<input type="hidden" name="username" value="' . $certificate_row["username"] . '">
											<input type="hidden" name="parent_id" value="' . $row["id"] . '">
											<input class="send_comment_btn" type="submit" data-comment-id="' . $row["id"] . '" value="送出">
										</form>
									';
								}
								echo '</div>';
								echo '</div>';
							}
						}
					?>
				</div>
			</div>
		</div>
		<!-- Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="main.js"></script>
	</body>
	</html>	