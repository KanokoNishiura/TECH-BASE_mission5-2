<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body bgcolor="#e6e6fa">
<br>
<div style="margin-left:100px; margin-top:60px;">
<h1>ほげほげ掲示板</h1>
<br>
<br>
<!--新規投稿-->
<form action="" method="post">
    <label>お名前</label><br>
    <input type="text" name="name" required autofocus><br>
    <label>コメント</label><br>
    <textarea name="comment" cols="80" rows="3" placeholder="こちらにご入力ください。" required></textarea><br>
    <input type="password" name="password" placeholder="パスワードを登録" required>
    <span style="margin:20px;"></span>
    <input type="submit" name="submit" value="投稿"><br>
</form><br>
<hr width="800px" align="left">

<!--コメント修正-->
<form action="" method="post"><br>
    <label>コメント修正</label><br>
    <textarea name="edi_com" cols="80" rows="3" placeholder="修正の際はこちらにご入力ください。" required></textarea><br>
    <input type="number" name="edi_id" placeholder="投稿番号" required><br>
    <input type="password" name="pass" placeholder="パスワード" required>
    <span style="margin:20px;"></span>
    <input type="submit" name="edit" value="編集"><br>
</form>

<!--コメント削除-->
<form action="" method="post"><br>
    <label>コメント削除</label><br>
    <input type="number" name="del_id" placeholder="投稿番号" required><br>
    <input type="password" name="pass" placeholder="パスワード" required>
    <span style="margin:20px;"></span>
    <input type="submit" name="delete" value="削除">
</form>
<br>
<br><hr width="800px" align="left"><hr width="800px" align="left">

<?php
    
//try-catch
try{    
    
    //データベース接続
    $dsn = "mysql:dbname=hogehoge;host=localhost";
    $user = "tb-hogehoge";
    $password = "hogepass";
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS forum"
    ." (id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name TEXT,"
    . "comment TEXT," 
    . "date TEXT,"
    . "password TEXT);";
    $stmt = $pdo -> query($sql);
    
        //投稿が押されたら...
        if(isset($_POST["submit"])){
            
            //定義
            $name = $_POST["name"];
            $comment = str_replace(PHP_EOL, "", $_POST["comment"]);
            $password = $_POST["password"];
            date_default_timezone_set('Asia/Tokyo');
            $date = date("Y/m/d H:i");
            
            //名前とコメントが空文字でないなら追記
            if(!preg_match("/^\s*$/", $name) && !preg_match("/^\s*$/", $comment)){
                
                $sql = $pdo -> prepare("INSERT INTO forum"
                ." (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                $sql -> bindParam(":name", $name, PDO::PARAM_STR);
                $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
                $sql -> bindParam(":date", $date, PDO::PARAM_STR);
                $sql -> bindParam(":password", $password, PDO::PARAM_STR);
                $sql -> execute();
            }
            
            //空文字を含む場合は...
            else{
                echo "入力内容をお確かめください。<br><br>";
            }
        }
        
        //編集が押されたら...
        elseif(isset($_POST["edit"])){

            //定義
            $edi_id = $_POST["edi_id"];
            $comment = str_replace(PHP_EOL, "", $_POST["edi_com"]);
            $edi_com = $comment."(編集済み)";
            $pass = $_POST["pass"];
            
            //コメントが空文字でないなら編集
            if(!preg_match("/^\s*$/", $_POST["edi_com"])){    
                
                $sql = 'UPDATE forum SET comment=:edi_com WHERE id=:edi_id AND password=:pass';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':edi_com', $edi_com, PDO::PARAM_STR);
                $stmt -> bindParam(':edi_id', $edi_id, PDO::PARAM_INT);
                $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt -> execute();
            
            }
            
            //空文字を含む場合は...
            else{
                echo "入力内容をお確かめください。<br><br>";
            }
            
        }    
        
        //削除が押されたら...
        elseif(isset($_POST["delete"])){
                
            //コメントを削除
            // $del_id = $_POST["del_id"];
            // $pass = $_POST["pass"];
            // $sql = 'DELETE from forum where id=:del_id AND password=:pass';
            // $stmt = $pdo -> prepare($sql);
            // $stmt -> bindParam(':del_id', $del_id, PDO::PARAM_INT);
            // $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            // $stmt -> execute();
            
            //削除メッセージを表示する場合
            $del_id = $_POST["del_id"];
            $pass = $_POST["pass"];
            $del_com = "---投稿は削除されました---";
            $sql = 'UPDATE forum SET comment=:del_com WHERE id=:del_id AND password=:pass';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':del_com', $del_com, PDO::PARAM_STR);
            $stmt -> bindParam(':del_id', $id, PDO::PARAM_INT);
            $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt -> execute();
            
        }
        
        
    //テーブルの表示
    $sql = "SELECT * FROM forum";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
        foreach ($results as $row){                 
            echo $row['id']."\t";
            echo $row['name']."\t";
            echo $row['comment']."\t";
            // echo $row['password']."\t";
            echo $row['date'].'<br>';
            echo "<hr width='800px' align='left'>";
        }

         
         
//try-catch           
}catch(PDOException $e) {
	echo $e->getMessage();
	die();
}           
            
?>
</div>
<br>
<br>
</html>
