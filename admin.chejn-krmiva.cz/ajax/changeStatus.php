<?php
session_start();
require_once("../modely/Db.php");

 Db::connect("wm124.wedos.net", "w136226_eshop", "EnjdcW4A", "d136226_eshop");
$id_objednavky=$_POST["id_objednavky"]; 
$status=$_POST["status"]; 


$x= Db::query('UPDATE `fakturace` SET `stav`= :stav WHERE `id`=:id',array(":stav"=>$status,":id"=>$id_objednavky));



    // print_r($existuje);
?>                                         