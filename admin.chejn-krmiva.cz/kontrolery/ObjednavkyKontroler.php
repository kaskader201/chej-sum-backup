<?php

class ObjednavkyKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.',
      'menu' => 'objednavky'
		);
    
    $cisloObjednavky =$parametry[0];
     
  $spravceObjednavek= new SpravceObjednavek();
                                             
      $objednavky=$spravceObjednavek-> vratSeznamObjednavek();
                                                  
       foreach ($objednavky as $key =>  $objednavka) {
      
       	$objednavky[$key]["stav"] = $spravceObjednavek->vratStavObjednavky($objednavka["stav"]);
       }
     
         $this->data["prodano"] =$spravceObjednavek-> vratPocetObjednavek(); 
      $this->data["objednavky"] = $objednavky;
     
      	$this->pohled = 'objednavky';
    }
}