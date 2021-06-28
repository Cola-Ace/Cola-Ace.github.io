<?php
    include "../config/database.php";
    $conn = ConnectDatabase();
    $list_rows = $_POST["list_rows"];
    $result = $conn->query("SELECT count(*) FROM sb_bans");
    $row = $result->fetch_assoc();
    $count = $row["count(*)"];
    $page = 1;
    while (true){
        $count -= $list_rows;
        if ($count <= 1){
            break;
        }
        $page++;
    }
    print_r($page);
?>