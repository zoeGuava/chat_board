## 簡介

**[留言板連結](http://zoeguava.com/chat_board/index.php?page=1)**

這個留言板是「[程式導師實驗計畫第三期](https://github.com/Lidemy/mentor-program-3rd-zoeGuava)」的作業之一，占了課程四分之一左右的比重，作業涵蓋範圍：
1. 建立 PHP 執行環境
2. 透過 MySQL 與資料庫溝通，並建立會員登入系統
3. 使用 Ajax 改善使用者新增、刪除留言時的體驗
4. 顧及 SQL Injection、XSS、Hashing 等常見資安問題
5. 購買網域名稱與主機將此專案正式上傳到網路上

## 檔案結構

- .gitignore
	- 資料庫登入：conn.php
- **顯示頁面**
	- 主頁面：index.php
	- 登入頁面：login.php
	- 會員註冊：registered.php
	- 頁碼顯示：page.php
- **JavaScript \ CSS**
	- Ajax：main.js
	- CSS：style.css
- **執行動作**
	- 刪除留言：delete.php
  - 新增留言：handle_add.php
  - 驗證登入：handle_login.php
  - 更改狀態為登出：handle_logout.php
  - 驗證註冊：handle_registered.php
  - 執行留言修改：handle_update.php
  - 預設狀態、按鈕渲染、欄位標題渲染：utils.php

## 啟動步驟

### 環境建置

1. 此作業是用 XAMPP(Apache + MariaDB + PHP + Perl) 來建立作業環境。[下載網址](https://www.apachefriends.org/zh_tw/index.html)
2. 下載完成後點開 XAMPP，在 **Manage Servers** 的標籤頁將 **MySQL Database** 與 **Apache Web Server** 啟動（燈號為綠色代表啟動完成）。
3. 啟動完成後點擊 **Go to Application** 或是在瀏覽器貼上**localhost**，有顯示頁面即表示成功啟動。

### 啟動留言板

1. 開啟 XAMPP 資料夾，並到 **htdocs** 資料夾建立一個新資料夾
> htdocs 資料夾 MAC 預設路徑：應用程式/XAMPP/xamppfiles/htdocs/newFolder
2. 點擊本頁面右上角的 **Clone or download** 將留言板下載
3. 把下載下來的 chat_board-master 資料夾放到 XAMPP/xamppfiles/htdocs/newFolder 內
> 路徑：XAMPP/xamppfiles/htdocs/newFolder/chat_board-master
4. 打開瀏覽器於網址列輸入 url：`localhost/newFolder/chat_board-master/index.php`
> localhost 是從 htdocs 資料夾開始的
5. 此時會因為沒有 **conn.php** 檔案而跳出錯誤訊息：`Warning: require_once(conn.php): failed to open stream`
6. **conn.php** 檔案含有連接資料庫所需的 IP 位址與資料庫的帳號密碼，因資安考量並未上傳至 GitHub，下面附上 conn.php 寫法：

```php
<?php
  $servername = 'localhost';
  $username ='root';
  $password = '';
  $dbname = 'chat_board';

  $conn = new mysqli($servername, $username, $password, $dbname);
  $conn->query('SET NAMES UTF8'); // 設定編碼
  $conn->query("SET time_zone = '+08:00'"); // 設定時區
  if ($conn->connect_error) { // 連線出錯時回報錯誤
    die('connection failed: ' . $conn->connect_error);
  }
?>
```


### 建立資料庫

先確認已將 `conn.php` 新增至檔案夾裡再往下進行。
要拿 `conn.php` 來連接的就是資料庫，而資料庫有各種選擇可以使用，這邊用的是 phpMyAdmin，已經在一開始下載的 XAMPP 裡面了，只要在瀏覽器輸入 url:`localhost/phpmyadmin` 就可以進入管理資料庫的介面。

1. 在左側點擊新增，建立新資料庫：chat_board \ latin1_swedish_ci
2. 在**結構**裡建立 **chat_board**
3. 點進去 **chat_board** 之後建立三個資料表
	- zoeGuava_users
	- zoeGuava_comments
	- zoeGuava_certificates
4. 到各個資料表內建立結構

#### zoeGuava_users

| 名稱 | 型態 | 編碼與排序 |額外資訊|
| ---- | ----  | ---- | ---- |
|  id  | int(12) |    | AUTO_INCREMENT |
|  username  | varchar(16) |  utf8_unicode_ci  |    |
|  nickname  | varchar(64) |  utf8_unicode_ci  |    |
|  password  | varchar(100) |  latin1_swedish_ci  |    |

#### zoeGuava_comments

| 名稱 | 型態 | 編碼與排序 |額外資訊|
| ---- | ----  | ---- | ---- |
|  id  | int(12) |    | AUTO_INCREMENT |
|  parent_id  | int(12) |  latin1_swedish_ci  |    |
|  username  | varchar(16) |  utf8_unicode_ci  |    |
|  nickname  | varchar(64) |  utf8_unicode_ci  |    |
|  comments  | text |  utf8_unicode_ci  |    |
|  created_at  | datetime |     |  預設值：CURRENT_TIMESTAMP  |

#### zoeGuava_certificates

| 名稱 | 型態 | 編碼與排序 |額外資訊|
| ---- | ----  | ---- | ---- |
|  user_id  | varchar(16) | latin1_swedish_ci |  |
|  username  | varchar(16) |  utf8_unicode_ci  |    |

初步設置已結束，開啟瀏覽器後輸入網址：`localhost/newFolder/chat_board-master/index.php` 進入主畫面即可開始使用。


## 參考資料

- 此 README.md 寫法為參考 [standard-readme](https://github.com/RichardLitt/standard-readme) 所寫成
- PHP\MySQL 基礎概念課程：[ BE101 用 PHP 與 MySQL 學習後端基礎](https://lidemy.com/p/be101-php-mysql)

## 更新紀錄

- 2020/05/24：新增 README.md