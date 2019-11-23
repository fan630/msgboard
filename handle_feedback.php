<?php
    require_once('./conn.php');
    include("./check_token.php");
    include("./utils.php");

    if(!isset($_POST['content'])|| empty($_POST['content'])||!isset($_POST['nickname'])|| empty($_POST['nickname'])){
        printMessage('請在空格中輸入資料!','./index.php');
    }else{
        $parent_id = $_POST['parent_id'];
        $feedback_content = $_POST['content'];
        $nickname = $_POST['nickname'];
        
        // echo $username. $parent_id . $feedback_content . $nickname;
        $stmt = $conn->prepare("INSERT INTO fan630_comments(parent_id, username, nickname, content) VALUES (?,?,?,?)");
        $stmt -> bind_param('ssss',$parent_id, $username, $nickname, $feedback_content);
    
        if($stmt -> execute()){
            header('Location:./index.php');
        }else{
           echo $conn->error;
        }
    }

?>