<?php

class SpravceProduktu {
   

public function vratProdukt($name)
{
     
		return Db::queryOne('
				SELECT *
			FROM `zbozi`
			WHERE `url` = ?
		', array($name));  	

}

	public function vratProdukty($min)
	{
		$seznam= Db::queryAll('
   SELECT * FROM `zbozi` ORDER BY `new` DESC,`id` ASC
		');
 $aktual=array();
   for ($i = 0;$i<count($seznam) ;$i++ ) {
   	                        if($i>=$min&&$i<$min+6){
                              array_push($aktual,$seznam[$i]);
                             }
   }
   return $aktual;
	}
  
  function vratPocetProduktu()
{
	return Db::query('SELECT id FROM zbozi');
}

  function urlStrany($url, $strana)
{
	return str_replace('{strana}', $strana, $url);
}

 function vratProduktyDleKategorie($kategorie,$min,$pocet)
 {
    return Db::queryAll('SELECT * FROM `zbozi` INNER JOIN rozrazeni ON rozrazeni.id_zbozi=zbozi.id WHERE rozrazeni.id_category='.$kategorie.' ORDER BY `new` DESC LIMIT '.$min.','.$pocet.'');
 }
  function pocetProduktuDleKategorie($kategorie)
 {
    return Db::query('SELECT zbozi.id FROM `zbozi` INNER JOIN rozrazeni ON rozrazeni.id_zbozi=zbozi.id WHERE rozrazeni.id_category='.$kategorie.'');
 }
   function vypisDostupnost($pocet)
{

//$pocet=Db::querySingle('SELECT `skladem` FROM `zbozi` WHERE `id`=:id',array(":id"=>$id_produktu));
/*
 * Vyprodáno(červeně),
 * 1-10ks(oranžově),
 * 11-20ks(tmavě zelená)
 * 21+ks(sv.zelená)
 * */
 if($pocet==0)
 {
     return 'Vyprodáno';
 }else 
 {

     return'<span style="color:#00CC00">Skladem</span>';
 }
}
}