<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <title>掲示板made by 久田</title>
  </head>

  <body>
    <h1>簡易掲示板です</h1>

    <form action="mission_4-1hisadadisplay.php" method="post">

      <?php
      //part3-1
      $dsn='mysql:dbname=tt_813_99sv_coco_com;host=localhost';
      $user='tt-813.99sv-coco.c';
      $password='n8ZJw6yW';
      $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

      //part3-2
      $sql="CREATE TABLE IF NOT EXISTS tbhisada"
      ."("
      ."id INT,"
      ."name char(32),"
      ."comment TEXT,"
      ."created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,"
      ."password TEXT"
      .");";
      $stmt=$pdo->query($sql);


      //編集その１
      $name=$_POST['name'];
      $comment=$_POST["comment"];

      $pass=$_POST['pass'];
      $epass=$_POST['epass'];
      $dpass=$_POST['dpass'];

      //パスワード比較用のループ処理
      $sql='SELECT*FROM tbhisada';
      $stmt=$pdo->query($sql);
      $results=$stmt->fetchAll();
      foreach($results as $row){


        //もし編集ボタンが押されたなら
        if(!empty($_POST['edit']) && $row['password']===$epass){

          $edinum=$_POST["edit"];
          $edi10=intval($edinum);
           if($row['id']==$edi10){
            $hiddnum=$row['id'];
            $formname=$row['name'];
            $formtext=$row['comment'];
            $second="新";
           }
          }
        }
       ?>

      <p><input type="text" name="name" placeholder="名前" value="<?php echo $formname;?>"></p>
      <p><input type="text" name="comment" placeholder="コメント" value="<?php echo $formtext;?>"></p>
      <p><input type="text" name="pass" placeholder="<?php echo $second;?>パスワード"></p>
        <input type="submit" name="s">
      <p><input type="text" name="delete" placeholder="削除対象番号"></p>
      <p><input type="text" name="dpass" placeholder="パスワード"></p>
        <input type="submit" name="s" value="削除">
      <p><input type="text" name="edit" placeholder="編集対象番号"></p>
      <p><input type="text" name="epass" placeholder="パスワード"></p>
        <input type="submit" value="編集" name="s">

        <input type="hidden" name="check" value="<?php echo $hiddnum; ?>">

    </form>



    <?php
      //パスワード比較用のループ処理
      $sql='SELECT*FROM tbhisada';
      $stmt=$pdo->query($sql);
      $results=$stmt->fetchAll();
      foreach($results as $row){
        //編集その２、もし 隠れテキストに数字入ってたら、、、
        if(!empty($_POST["check"]) && !empty($_POST['pass'])) {
          //part3-7実際に更新する内容の決定
          $id=$_POST['check'];
          $name=$_POST['name'];
          $comment=$_POST['comment'];

          $sql='update tbhisada set
          name=:name,
          comment=:comment,
          password=:password
          where id =:id';
          $stmt=$pdo->prepare($sql);
          $stmt->bindParam(':name', $name, PDO::PARAM_STR);
          $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
          $stmt->execute();
        }


        //part3-8　記述内容の削除
        if(!empty($_POST['delete']) && $row['password']===$dpass){
          $id=$_POST['delete'];
          $sql='delete from tbhisada where id=:id';
          $stmt=$pdo->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

        }
      }



    //空なら受け付けないよ
    if(!empty($_POST['comment'])
      && !empty($_POST['name'])
      && empty($_POST['delete'])
      && empty($_POST['edit'])
      && empty($_POST['check'])
      && !empty($_POST['pass'])){
      //part3-5 記述内容の記入
      $sql=$pdo->prepare("INSERT INTO tbhisada(id, name, comment, created_at, password) VALUES(:id,:name,:comment,:created_at,:password)");
      $sql->bindParam(':id', $id, PDO::PARAM_INT);
      $sql->bindParam(':name', $name, PDO::PARAM_STR);
      $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql->bindParam(':created_at', $created_at, PDO::PARAM_STR);
      $sql->bindParam(':password', $pass, PDO::PARAM_STR);

        //ここで実際に記述する内容の決定
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $password=$_POST['password'];
        $sql->execute();
    }else{
      $comment=$_POST['comment'];
    }


      //投稿番号を呼び出すよ
      $sql='SELECT*FROM tbhisada ORDER BY id';
      $stmt=$pdo->query($sql);
      $results=$stmt->fetchAll();
      foreach($results as $row){
        echo "番号:",$row['id'];
        echo "名前:",$row['name'];
        echo "コメント:",$row['comment'];
        echo "日時:",$row['created_at'].'<br>';
      }
    ?>
  </body>

</html>
