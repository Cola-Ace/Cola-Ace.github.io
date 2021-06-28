<?php
    include "../config/database.php";
    $conn = ConnectDatabase();
    Header("Content-Type: text/plain; charset=UTF-8");
    Header("X-Content-Type-Options: nosniff");
    $result = $conn->query("SELECT * FROM sb_bans");
    $list_rows = $_POST["list_rows"];
    $page = $_POST["page"];
    $info = [];
    $count = 0;
    if ($result->num_rows > 0){
        $start = $list_rows * ($page - 1);
        $end = ($list_rows * $page) - 1;
        $rows = 0;
        while ($row = $result->fetch_assoc()){
            if ($rows > $end){
                break;
            }
            if ($rows >= $start){
                $temp = array("bid" => $row["bid"], "ip" => $row["ip"], "authid" => $row["authid"], "name" => $row["name"], "created" => $row["created"], "ends" => $row["ends"], "length" => $row["length"], "reason" => $row["reason"], "RemovedOn" => $row["RemovedOn"]);
                $info[] = $temp;
                $count++;
            }
            $rows++;
        }
        $return = array("Status" => 0, "count" => $count, "Data" => $info);
    } else {
        $return = array("Status" => -1, "Reason" => "No Data");
    }
    print_r(json_encode($return, JSON_UNESCAPED_UNICODE));
?>