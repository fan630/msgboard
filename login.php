<?php
    session_start();
    require_once('./conn.php');
    include_once('./utils.php');

    if
    (
    isset($_POST['username'])&&
    isset($_POST['password'])&&
    !empty($_POST['username'])&&
    !empty($_POST['password'])
    )
    
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        /*確認有此會員*/
        $stmt = $conn->prepare("SELECT * FROM fan630_users where username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt -> get_result();


        if($result->num_rows > 0){
            while($row= $result->fetch_assoc()){
                /*確認密碼是否吻合*/
                    if(password_verify($password,$hashed_password)){
                        $_SESSION['username']=$username;
                        header('Location:./index.php');
                    }
                    else{
                        printMessage('請輸入正確帳號密碼!','./index.php');
                        exit();
                    }
                }
            }
        }   


?>  


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css">
    <title>登入頁面</title>
</head>
<body>
    <div id="form__wrapper" class="d-flex justify-content-center">
        <form class="form mt-3" action="./login.php" method="POST" >
            <div>
                請輸入會員帳號:
                <input type="text" name='username'>
            </div>
            <div>請輸入會員密碼:
                <input type="password" name='password'>
            </div>
            <div>
                <input type="hidden" name = 'id'>
            </div>
            <div>
                <input type="submit" class="btn btn-primary" /> 
            </div>
        </form>
    </div>
</body>
</html>



