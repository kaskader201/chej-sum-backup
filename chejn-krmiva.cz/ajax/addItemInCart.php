<?php
session_start();
require_once("../modely/Db.php");
require_once("../modely/SpravceKosiku.php");
Db::connect("wm124.wedos.net", "w136226_eshop", "EnjdcW4A", "d136226_eshop");

   $spravceKosiku = new SpravceKosiku();
$id_zbozi=$_POST["id_zbozi"]; 
$pocet=$_POST["pocet"];


      /** záloha**/
$text = "Uzivatel: ".$spravceKosiku->getUserId().", ID Zbozi: ".$id_zbozi.", Počet: ".$pocet.", Datum a čas: ".date("H:i:s d.m.Y")."\n\r";
$soubor = fopen("zaloha ".date("d.m.Y").".txt", "a+");
fwrite($soubor, $text);
fclose($soubor);

if($pocet<1)
$pocet=1;

$id_user =$_SESSION['user_id'];




if($id_user==null)
{
$id_user=$_SESSION['token'];
}
$existuje=Db::query('SELECT `id` FROM `kosik` WHERE id_zbozi= :id_zbozi and id_user="'.$id_user.'"',array(":id_zbozi"=>$id_zbozi));

if($existuje>0)
{
$existuje=Db::queryOne('SELECT `id`,`kusu` FROM `kosik` WHERE id_zbozi= :id_zbozi and id_user="'.$id_user.'"',array(":id_zbozi"=>$id_zbozi));
$kusu =$existuje["kusu"]+$pocet;

$x= Db::query('UPDATE `kosik` SET `kusu`= :kusu WHERE `id`=:id',array(":kusu"=>$kusu,":id"=>$existuje["id"]));


}else
{

$sql='INSERT INTO `kosik`(`id_zbozi`, `id_user`, `login`, `kusu`,cas) VALUES (?,?,?,?,?)'; 
$pole=array($id_zbozi,$id_user,$_SESSION['login'],$pocet,date("Y-m-d H:i:s"));
Db::query($sql,$pole);
}



 
 $pocet =$spravceKosiku->getCountProduct();
 $pocet= rtrim($pocet);

 print_r($pocet);
    // print_r($existuje);
?>                                         