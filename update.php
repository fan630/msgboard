<?php
    require_once("./conn.php");

    $id = $_GET['id'];
    //$username = $_COOKIE['member_id'];
    $sql = "SELECT * FROM fan630_comments WHERE id = '$id' ";

    $result= $conn->query($sql);
    $row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/darkly/bootstrap.min.css">
    <title>更改留言</title>
</head>
<body>
    <div id="form__wrapper">
        <h1>更改留言</h1>
        <form action="./handle_update.php" method="POST">
        <div>
            <textarea name="content" class="content" cols="70" rows="10"  placeholder="請輸入新留言" ><?php echo $row['content']?></textarea>
        </div>
        <input type="hidden" name = "id" value="<?php echo $row['id']?>">
        <div>
            <input type="submit" class="btn btn-primary">
        </div>
        </form>
    </div>
</body>
</html>