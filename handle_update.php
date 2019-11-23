<?php

    require_once("./conn.php");
    include_once("./check_token.php");

    $content = $_POST['content'];
    $id = $_POST['id'];

    $sql = "UPDATE fan630_comments SET content =? WHERE username =? and id =? "; 
    $stmt= $conn->prepare($sql);

    $stmt-> bind_param("sss",$content,$username,$id);

    if($stmt->execute()){
        header('Location:./index.php');
    }else{
        echo ('failed:'. $conn->error);
    }  
            

?>