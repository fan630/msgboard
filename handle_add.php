<?php

    require_once('./conn.php');
    include_once('check_token.php');
    include("./utils.php");

    if(!isset($_POST['content'])|| empty($_POST['content'])){
        printMessage('請輸入留言!','./index.php');
        exit();
    }

    $content = $_POST['content'];
    $parent_id = $_POST['parent_id'];
    $nickname= $_POST['nickname'];

    $stmt = $conn->prepare("INSERT INTO fan630_comments(username,content,parent_id, nickname) VALUES (?,?,?,?)");
    $stmt -> bind_param('ssss',$username, $content, $parent_id, $nickname);
    $last_id = $conn->insert_id;


    if($parent_id ==='0'){
        if($stmt -> execute()){

            $last_id = $conn->insert_id;

            //再從table裡面找出created_at的資訊
            $sql = "SELECT * FROM fan630_comments ORDER BY created_at DESC";
            $result = $conn->query($sql);
                
            if($result->num_rows >0){
                    $row = $result->fetch_assoc();
                }

            $createdTime = $row['created_at'];
            $username = $row['username'];
                    
            echo json_encode(array(
                    "result" => 'success',
                    "id" => $last_id,
                    "createdTime" => $createdTime,
                    "username" =>$username
                ));
            }else{
            echo json_encode(array(
                     "result" => 'failed'
                ));
            }
    }else{
        header('Location:./handle_feedback.php');
    }



?>






