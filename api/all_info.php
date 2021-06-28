<?php
    include "../config/database.php";
    $conn = ConnectDatabase();

    require "./SourceQuery/bootstrap.php";
    use xPaw\SourceQuery\SourceQuery;
    Header("Content-Type: text/plain; charset=UTF-8");
    Header("X-Content-Type-Options: nosniff");
    $Query = new SourceQuery();
    $count = 0;
    $players = 0;
    $info = [];
    $result = $conn->query("SELECT * FROM sb_servers");
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            try {
                $Query->Connect($row["ip"], $row["port"], 1, SourceQuery::SOURCE);
                $server = $Query->GetInfo();
                $server_players = $Query->GetPlayers();
                $real_players = $server["Players"] - $server["Bots"];
                $temp = array("Status" => 0, "sid" => (int)$row["sid"], "IP" => $row["ip"], "Port" => (int)$row["port"], "HostName" => $server["HostName"], "Map" => $server["Map"], "Players" => (int)$server["Players"], "MaxPlayers" => (int)$server["MaxPlayers"], "Bots" => (int)$server["Bots"], "Real-Players" => $real_players, "Players-Data" => $server_players);
                $players += $real_players;
            } catch (Exception $e) {
                $temp = array("Status" => -1, "Reason" => "Cannot Connect To Server", "sid" => (int)$row["sid"], "IP" => $row["ip"], "Port" => (int)$row["port"]);
            } finally {
                $Query->Disconnect();
            }
            $info[] = $temp;
            $count++;
        }
    }
    $return = array("Count" => $count, "All-Real-Players" => $players, "Data" => $info);
    print_r(json_encode($return, JSON_UNESCAPED_UNICODE));
?>