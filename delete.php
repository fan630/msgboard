<?php
    require_once("./conn.php");
    include("./check_token.php");
    include_once('./utils.php');

    $id = $_POST['id'];

    $stmt=$conn->prepare("DELETE FROM fan630_comments WHERE (username =? and id =?) or parent_id =?");
    $stmt->bind_param("sss",$username,$id,$id);
    $stmt->execute();

    if($stmt->execute()){
        //雖然是array但是可以放key和value , 這不就是object?
        //echo 出來的東西會被放到頁面的response中
        echo json_encode(array(
            'result' => 'sucess',
            'message' => 'successfully'
        ));
    }else{
        echo json_encode(array(
            'result' => 'failed',
            'message' => 'delete failed'
        ));
    }
?>