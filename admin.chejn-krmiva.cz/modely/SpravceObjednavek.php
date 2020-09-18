<?php

class SpravceObjednavek {
   

public function vratObjednavku($cislo)
{
     
    return Db::queryOne('SELECT * FROM `fakturace` INNER JOIN objednavky ON fakturace.id=objednavky.id_fakturace  WHERE fakturace.id= :id',array(":id"=>$cislo));
     
}
public function vratZboziKObjednavce($cislo)
{

     return Db::queryAll('SELECT nazev,cena,obrazek,objednavky.sleva,objednavky.kusu FROM objednavky INNER JOIN zbozi ON objednavky.id_zbozi=zbozi.id where objednavky.id_fakturace= :id',array(":id"=>$cislo));
}
	// Vrátí seznam článků v databázi
	public function vratObjednavky($pocet)
	{
		return Db::queryAll('
			SELECT *                                          
			FROM `fakturace`  
			ORDER BY `id` DESC LIMIT  '.$pocet.'
		');
	}
  	public function vratSeznamObjednavek()
	{
		return Db::queryAll('
			SELECT *                                          
			FROM `fakturace`  
			ORDER BY `id` DESC
		');
	}
  
  
  function vratPocetProdanychProduktu()
{
	return Db::queryOne('SELECT SUM(kusu) 
FROM objednavky; 
');
}
  function vratPocetObjednavek()
{
	return Db::queryOne('SELECT COUNT(id) 
FROM fakturace; 
');
}
function vratStavObjednavky($stav)
{

 switch ($stav) {
         case "Zpracovává se" :
           return '<span class="label label-info label-flat">Zpracovává se</span>';
           break;
           case "Připravuje se" :
           return '<span class="label label-sm label-warning label-flat">Připravuje se</spn>';
           break;
           case "Odeslaná" :
           return '<a class="label label-sm  label-success label-flat">Odeslaná</a>';
           break;
           case "Stornováno" :
          return'<a class="label label-sm  label-danger label-flat">Stornováno</a>';
           break;
      default:
      	    return $stav ; 
      	break;
      }
}

 

}