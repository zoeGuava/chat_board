<?php
  require_once('conn.php');

	$total_sql = 'SELECT COUNT(id) from zoeGuava_comments'; // 留言總數
	$total_result = $conn->query($total_sql);
	$total_row = $total_result->fetch_assoc();
	$total_comments = $total_row['COUNT(id)'];
	
	$limit = 20; // 一頁 20 則留言
	$total_page = ceil($total_comments / $limit); // 總頁數

	if ( !isset($_GET["page"]) ) {
		  $page = 1; //設定起始頁
	  } else {
			$page = intval($_GET["page"]); //確認頁數只能夠是數值資料
			$page = ($page > 0) ? $page : 1; //確認頁數大於零
	}

	$start = ($page-1) * $limit; // 每一頁第一個留言在資料庫中的編號

  $php_self = basename($_SERVER['PHP_SELF']);
	$query_string = basename($_SERVER['QUERY_STRING']); // 判定現在所在的頁面
	for ($i=1; $i <= $total_page ; $i++) {
  	$active = ($query_string === 'page=' . $i) ? "active" : ""; // 在所在頁面加上 .active
		echo '<a href="' . $php_self . '?page=' . $i . '" class="page_item ' . $active .'">' . $i . '</a>';
	}
?>