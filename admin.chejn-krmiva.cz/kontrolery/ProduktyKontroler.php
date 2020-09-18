<?php

class ProduktyKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.',
      'menu' => 'produkty'
		);
    
  
     
  $spravceProduktu= new SpravceProduktu();
  $spravceObjednavek= new SpravceObjednavek();                                            
                                                  
      $this->data["produkty"] =$spravceProduktu-> vratProduktyNahled();
        $this->data["prodano"] =$spravceObjednavek-> vratPocetProdanychProduktu(); 
      	$this->pohled = 'produkty';
    }
}