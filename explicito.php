<?php
$connString = 'host = localhost dbname= teste user=postgres password=senha';

 
try{ 

    $db = pg_connect($connString);
    echo "Connected to Postgres with PDO";

}catch(PDOExeption $e){
    echo "Conection fail";
    die($e->getMessage());
}

$initialTime = 0;
$finalTime = 0;
$erro = 0;
 
$handle = fopen("reddit_vm.csv", "r");

$initialTime = microtime(true);
pg_query($db, "BEGIN");
while ($line = fgetcsv($handle,1000,",")){

      $insert["title"] = mb_convert_encoding($line[0], "UTF-8");
      $insert["score"] =  intval(mb_convert_encoding($line[1], "UTF-8"));
      $insert["id"] = intval(mb_convert_encoding($line[2], "UTF-8"));
      $insert["url"] = mb_convert_encoding($line[3], "UTF-8");
      $insert["comms_num"] = mb_convert_encoding($line[4], "UTF-8");
      $insert["created"] = mb_convert_encoding($line[5], "UTF-8");
      $insert["body"] = mb_convert_encoding($line[6], "UTF-8");
      $insert["timestamp"] = mb_convert_encoding($line[7], "UTF-8");

    $res = pg_insert($db, 'covid', $insert);
    if(!$res){
        $erro++;
    }
}
if ($erro > 0) {
	pg_query($db, "ROLLBACK");
}
else{
    pg_query($db, "COMMIT");
}

 

$finalTime = microtime(true);
fclose($handle);
pg_close($db);
echo "</br> Explicito = " . strval($finalTime-$initialTime) . " sec </br>";
?>