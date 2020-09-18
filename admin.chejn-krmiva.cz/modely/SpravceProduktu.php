<?php

class SpravceProduktu {
   

public function vratProdukt($id)
{
     
		return Db::queryOne('
				SELECT *
			FROM `zbozi`
			WHERE `id` = ?
		', array($id));  	

}
	// Vrátí seznam článků v databázi
	public function vratProdukty()
	{
		return Db::queryAll('
			SELECT *
			FROM `zbozi`  
			ORDER BY `id` ASC 
		');
	}
  	public function vratProduktyNahled()
	{
		return Db::queryAll('
			SELECT id,obrazek,nazev,skladem,cena,new,sleva
			FROM `zbozi`  
			ORDER BY `id` ASC 
		');
	}
  public function vratKategorie()
  {
     return Db::queryAll('
			SELECT title,category_id
			FROM `category`  			
		');   
  }
    public function vratKategoriiProduktu($id)
  {
     return Db::queryOne('
			SELECT `id_category` FROM `rozrazeni` WHERE  id_zbozi = ?   			
		',array($id));   
  }
  function vratPocetProduktu()
{
	return Db::query('SELECT id FROM zbozi');
}

  function urlStrany($url, $strana)
{
	return str_replace('{strana}', $strana, $url);
}

 function vratProduktyDleKategorie($kategorie,$min)
 {
    return Db::queryAll('SELECT * FROM `zbozi` INNER JOIN rozrazeni ON rozrazeni.id_zbozi=zbozi.id WHERE rozrazeni.id_category='.$kategorie.' LIMIT '.$min.',8');
 }
  function pocetProduktuDleKategorie($kategorie)
 {
    return Db::query('SELECT zbozi.id FROM `zbozi` INNER JOIN rozrazeni ON rozrazeni.id_zbozi=zbozi.id WHERE rozrazeni.id_category='.$kategorie.'');
 }

}