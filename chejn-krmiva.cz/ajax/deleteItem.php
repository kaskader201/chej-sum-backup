<?php
session_start();
require_once("../modely/Db.php");
Db::connect("wm124.wedos.net", "w136226_eshop", "EnjdcW4A", "d136226_eshop");

$id=$_GET["id"];
 $id_user =$_SESSION['user_id'];

if($id_user==null)
{
$id_user=$_SESSION['token'];
}
Db::query('DELETE FROM `kosik` WHERE  id_user="'.$id_user.'" AND `id`="'.$id.'"');

  header("Location: " . $_SERVER["HTTP_REFERER"]);
  die;



?>