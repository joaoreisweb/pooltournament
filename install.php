<?php

include_once('core/init.php');

$db = new MySQLDriver($_ENV['DB_SERVER'],$_ENV['DB_NAME'],$_ENV['DB_USER'],$_ENV['DB_PASSWORD']);
$db->db_connect();

$table_players = "pool_"."players";
$table_games = "pool_"."games";


$checktable = "SELECT * FROM ".$table_players;
$exists = is_array($db->execQuery($checktable));

if(!$exists){

    echo "<br>Start installation.";

    $createTablePlayers= "CREATE TABLE IF NOT EXISTS ".$table_players." (`id` int(11) NOT NULL, `name` varchar(191) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;ALTER TABLE ".$table_players." ADD PRIMARY KEY (`id`);";
    $db->execQuery($createTablePlayers);

    $createTableGames= "CREATE TABLE IF NOT EXISTS ".$table_games." (`id` int(11) NOT NULL,`date` date NOT NULL,`players` varchar(50) NOT NULL,`winner_id` int(11) DEFAULT NULL,`looser_id` int(11) DEFAULT NULL,`looser_balls` int(11) DEFAULT NULL,`status` enum('Waiting','Played','Absent') NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;ALTER TABLE ".$table_games." ADD PRIMARY KEY (`id`);";
    $db->execQuery($createTableGames);

    echo "<br>Insert tournament data.";

    $createPlayers = "INSERT INTO ".$table_players." (`id`, `name`) VALUES(1, 'João'),(2, 'Ana'),(3, 'Afonso'),(4, 'António');";
    $db->execQuery($createPlayers);

    $createTournament="INSERT INTO ".$table_games." (`id`, `date`, `players`, `winner_id`, `looser_id`, `looser_balls`, `status`) VALUES (1, '2021-06-01', '1,2', 0, 0, 0, 'Waiting'),(2, '2021-06-02', '2,3', 0, 0, 0, 'Waiting'),(3, '2021-06-03', '1,3', 0, 0, 0, 'Waiting'),(4, '2021-06-04', '1,4', 0, 0, 0, 'Waiting'),(5, '2021-06-05', '4,2', 0, 0, 0, 'Waiting'),(6, '2021-06-07', '4,3', 0, 0, 0, 'Waiting');";
    $db->execQuery($createTournament);

    echo "<br>The installation is complete.";

}else{

    echo "<br>Already installed.";
}