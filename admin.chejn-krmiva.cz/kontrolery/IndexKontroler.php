<?php

class IndexKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.' ,
      'menu' => 'index'
		);
     
  $spravceObjednavek= new SpravceObjednavek();
                        $objednavky= $spravceObjednavek-> vratObjednavky(7);
                                                                                
       foreach ($objednavky as $key =>  $objednavka) {
      
       	$objednavky[$key]["stav"] = $spravceObjednavek->vratStavObjednavky($objednavka["stav"]);
       }
                                             
         $this->data["prodano"] =$spravceObjednavek-> vratPocetProdanychProduktu();          
       $this->data["objednavky"] = $objednavky;
      	$this->pohled = 'index';
    }
}