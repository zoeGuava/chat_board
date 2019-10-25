<?php
	require_once('./conn.php');

	$id = $_GET['id'];
	$sql = 'SELECT * FROM zoeGuava_comments WHERE id = ' . $id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>留言板</title>
	<link rel="stylesheet" href="style.css">	
</head>
<style>
	.container {
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 45%;
	}
</style>
<body>
	<div class="container">
		<h1>編輯留言</h1>
		<form method="POST" action="./handle_update.php">
			<div>帳號：<?php echo $row['username']; ?></div>
			<div>暱稱：<?php echo $row['nickname']; ?></div>
			<div>內容：<textarea name="comments" rows="10" cols="30"><?php echo $row['comments']; ?></textarea></div>
			<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
			<input type="submit" value="送出">
		</form>
	</div>
</body>
</html>