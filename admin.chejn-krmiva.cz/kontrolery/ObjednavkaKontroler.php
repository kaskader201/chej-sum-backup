<?php

class ObjednavkaKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.'
		);
    
    $cisloObjednavky =$parametry[0];
     
  $spravceObjednavek= new SpravceObjednavek(); 
  
       $objednavka=$spravceObjednavek-> vratObjednavku($cisloObjednavky);                                      
                                                  
      $this->data["udaje"] =$objednavka;
        
    
      $this->data["stav"]=$spravceObjednavek->vratStavObjednavky($objednavka["stav"]);   
      $this->data["objednavka"] =$spravceObjednavek-> vratZboziKObjednavce($cisloObjednavky);
      	$this->pohled = 'objednavka';
    }
    
  
}