<?php require_once('conn.php'); ?>

<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<title>會員註冊</title>
	</head>
	<body>
		<div class="wrapper">
			<div class="container" style="width: 500px;">
				<div class="container_alert">
					<h3>本站為練習用網站，因教學用途刻意忽略資安的實作<br>註冊時請勿使用任何真實的帳號或密碼</h3>
				</div>
				<div class="container_add">
					<form method="POST" action="handle_registered.php" >
						帳號：<input type="text" name="username">
						暱稱：<input type="text" name="nickname">
						密碼：<input type="password" name="password">
						<input type="submit" value="註冊、前往登入" style="margin-top: 10px;">
					</form>
				</div>
			</div>
		</div>
	</body>
	</html>	