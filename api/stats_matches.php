<?php
    include "../config/database.php";
    $conn = ConnectDatabase();
    Header("Content-Type: text/plain; charset=UTF-8");
    Header("X-Content-Type-Options: nosniff");
    $result = $conn->query("SELECT * FROM stats_matches");
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
                $temp = array("match_id" => $row["match_id"], "start_time" => $row["start_time"], "end_time" => $row["end_time"], "map" => $row["map"], "team1_score" => $row["team1_score"], "team2_score" => $row["team2_score"], "captain1" => $row["captain1"], "captain2" => $row["captain2"], "hostname" => $row["hostname"]);
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