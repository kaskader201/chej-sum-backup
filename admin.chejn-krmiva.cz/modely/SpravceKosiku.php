<?php

class SpravceKosiku {
   
public function getPaymentMethod($id)
{
   $id=Db::queryOne('SELECT nazev FROM  platby where id =:id',array(":id"=>$id));
   return $id[0];
}
public function getPricePaymentMethod($id)
{
   $id=Db::queryOne('SELECT cena FROM  platby where id =:id',array(":id"=>$id));
   return $id[0];
}
public function getDeliveryMethod($id)
{
   $id=Db::queryOne('SELECT nazev FROM  doprava where id =:id',array(":id"=>$id));
   return $id[0];
}
public function getPriceDeliveryMethod($id)
{
   $id=Db::queryOne('SELECT cena FROM  doprava where id =:id',array(":id"=>$id));
   return $id[0];
}
public function getUserId()
{             
  if(!empty($_SESSION['user_id']))
  {
      $id_user= $_SESSION['user_id'];
  }else{
  
  $id_user= $_SESSION['token'];
  }
  
  return  $id_user;
}

// $zbozi=Db::queryAll("SELECT * FROM  NSHOPITEM where id >= 1 LIMIT 6");
public function getCount()
{                                                                               
  $pocet=Db::query('SELECT id FROM  kosik where id_user ="'.self::getUserId().'"');
  return $pocet;
}
 public function vratKosik()
{

      return Db::queryAll('SELECT nazev,cena,obrazek,url,kusu,kosik.id FROM `zbozi` INNER JOIN kosik ON `kosik`.id_zbozi=`zbozi`.`id` WHERE id_user ="'.self::getUserId().'" ');
  

}
 public function vratVeciKFakturaci()
{

      return Db::queryAll('SELECT kosik.id, kosik.kusu FROM `zbozi` INNER JOIN kosik ON `kosik`.id_zbozi=`zbozi`.`id` WHERE id_user ="'.self::getUserId().'" ');
  

}
public function getSumPrice($dph=false)
{
$celkovaCena=0;
  $kosik=Db::queryAll('SELECT id_zbozi,kusu FROM  kosik where id_user = :iduser',array(":iduser"=>self::getUserId()));
    for($i=0;$i<self::getCount();$i++)
    {     
     $zbozi=Db::queryOne('SELECT cena FROM  zbozi where id = "'.$kosik[$i]["0"].'"');
       $celkovaCena+= $zbozi["cena"]*(1-$zbozi["sleva"])*$kosik[$i]["1"];
     }                                                                              
     if($dph)
     {
    $celkovaCena*=1.21;
    
     }
      $celkovaCena= number_format ($celkovaCena,2,',', ' ');
  return $celkovaCena;
}

}

