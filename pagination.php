<?php 
    //user input 
    //第幾頁?
    //是否有得到get的值? 有就是我get的到的page,沒有就是第一頁
    $page = isset($_GET['page']) ?(int)$_GET['page']:1 ;
    
    //每頁顯示幾筆資料
    //是否有拿到perPage的資料,有的話就是我點選的perPage,沒有的話就顯示第五個.
    $perPage = isset($_GET['perPage']) && $_GET['perPage']<=50 ? (int)$_GET['perPage']:20; 
    
    //positioning 位置,判斷是否大於1, 如果是
    $start = ($page>1) ? ($page * $perPage) - $perPage:0;
    
    //頁數
    $result_count = $conn->query("SELECT COUNT(id) FROM fan630_comments WHERE parent_id =0 ");
    $sum1 = $result_count->fetch_assoc();
    $sum = $sum1['COUNT(id)'];
    $pages = ceil($sum/$perPage); 
?>
