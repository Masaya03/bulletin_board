<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>m5-01</title>
</head>
<?php
    require_once "tbtest.php";
    
    $init_array = parse_ini_file("sample.ini");
    $tb_pass = $init_array["password"];
    $tb_dsn = $init_array["dsn"];
    $tb_user = $init_array["user"];
    
    $tbtest = new Tbtest($tb_dsn, $tb_user, $tb_pass);
    $tbtest->init();

    $is_edit = 0;
    $edit_num = -1;

    if(isset($_POST["edit"])){
       if(!empty($_POST["num"] && !empty($_POST["password"]))){
            $edit_num = (int)$_POST["num"];
            $edit_pass = $_POST["password"];
    
            $result = $tbtest->Get($edit_num);
            $pass = $result["password"];
            $edit_name = $result["name"];
            $edit_comment = $result["comment"];
            $edit_num= $result["id"];
            
            if($edit_pass == $pass){
                $is_edit = 1;
                $edit_output = $edit_num." ".$edit_name." ".$edit_comment." ".$pass."を編集します<br>";
            }else{
                $edit_output= "passwordが異なります<br>";
            }
       }else{
           echo $edit_output = "入力が足りません<br>";
       }
    }
?>


<body>
    <p1>入力</p1>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value=<?php if($is_edit == 1)
        {echo $edit_name;}else{echo "";} ?> >
        <input type="text" name="comment" placeholder="コメント" value=<?php if($is_edit == 1)
        {echo $edit_comment;}else{echo "";} ?>>
        <!--<input type="password" name="password" placeholder="パスワード">-->
        <input type="text" name="password" placeholder="パスワード"  value=<?php if($is_edit == 1)
        {echo $pass;}else{echo "";} ?>>
        <input type="hidden" name="is_edit" value=<?php echo $is_edit?>>
        <input type="hidden" name="num" value=<?php echo $edit_num?>>
        <input type="submit" name="post" >
    </form>
    <br>
    
    <p1>削除</p1>
    <form action="" method="post">
        <input type="num" name="num" placeholder="削除番号">
        <!--<input type="password" name="password" placeholder="パスワード">-->
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="delete" value="削除" >
    </form>
    <br>
    
    <p1>編集</p1>
    <form action="" method="post">
        <input type="num" name="num" placeholder="編集する番号">
        <!--<input type="password" name="password" placeholder="パスワード">-->
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="edit" value="編集" >
    </form>
    
    <?php
    echo "<br>";
    echo "--------------------------------------------------<br>";
    
    if(isset($edit_output)){
        echo $edit_output;
    }
    
    #投稿する場合
    if(isset($_POST["post"]) && $_POST["is_edit"] == 0){
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
            $comment = $_POST["comment"];
            $name = $_POST["name"];
            $password = $_POST["password"];
            // $output = $pnum."<>".$name."<>".$comment."<>".$date."<>".$password."\n";
            
            $tbtest->Insert($name, $comment, $password);
            echo $name." ".$comment." ".$password."を受け付けました<br>";
        }else{
            echo "入力が足りません<br>";
        }
    }
        
    #削除する場合
    if(isset($_POST["delete"])){
        if(!empty($_POST["num"]) && !empty($_POST["password"])){
            $delete_num = $_POST["num"];
            $delete_pass = $_POST["password"];
            
            // DELETEを行う(idがなければ？)
            $result = $tbtest->Get($delete_num);
            if(isset($result)){
                if($result["password"] == $delete_pass){
                    $tbtest->Delete($delete_num); 
                    echo $result["id"]." ".$result["name"]." ".$result["comment"]." ".$result["password"]."を削除しました<br>";
                }else{
                    echo "passwordが異なります<br>";
                }
            }
        }else{
            echo "入力が足りません<br>";
        }
    }

    #編集する場合
    if(isset($_POST["post"]) && $_POST["is_edit"] == 1){
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) ){
        #       $edit_num = (int)$_POST["edit_num"];
            $edit_num = (int)$_POST["num"];
            $edit_name = $_POST["name"];
            $edit_comment = $_POST["comment"];
            $edit_pass = $_POST["password"];
            
            // UPDATEを行う
            $tbtest->Update($edit_num, $edit_name, $edit_comment, $edit_pass);
            echo $edit_num." ".$edit_name." ".$edit_comment." ".$edit_pass."を編集しました<br>";
        }else{
            echo "入力が足りません<br>";
        }
    }
    
    echo "--------------------------------------------------<br>";
    #コメントの表示を行う
    echo "<br>";
    echo "<h3>コメント</h3>";
    echo "<hr>";

    // 表示を行う
    $tbtest->Print_all();
    ?>
</body>
</html>