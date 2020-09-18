<?php

// Controller pro výpis produktu

class ZboziKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
		// Vytvoření instance modelu, který nám umožní pracovat s články
		$spravceProduktu = new SpravceProduktu();
		
		// Je zadáno URL článku
		if (!empty($parametry[0]))
		{
			// Získání článku podle URL
			$produkt = $spravceProduktu->vratProdukt(str_replace("-"," ",$parametry[0]));
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	if (!$produkt)
				$this->presmeruj('chyba');
		 
			// Hlavička stránky
	   	$this->hlavicka = array(
				'nazev' => $produkt['nazev'],
				'klicova_slova' => $produkt['klicova_slova'],
				'popis' => $produkt['popis'],
			);   
			
			// Naplnění proměnných pro šablonu		
      $this->data['id_produktu'] = $produkt['id'];
		  $this->data['url']=$produkt;
			$this->data['nazev'] = $produkt['nazev'];
			$this->data['kratkyPopis'] = $produkt['kratkyPopis'];
      $this->data['dlouhyPopis'] = $produkt['dlouhyPopis'];
    	$this->data['slozeni'] = $produkt['slozeni'];
      $this->data['hmotnost'] = number_format($produkt['hmotnost'], 0, ' ', ' ');
			$this->data['obrazek'] = $produkt['obrazek'];
      $this->data['new'] = $produkt['new'];
      $this->data['sleva'] = $produkt['sleva']; 
      $this->data['lepek'] = $produkt['lepek'];
      $this->data['dostupnost'] =$spravceProduktu->vypisDostupnost($produkt['skladem'])  ;
      $this->data['cena'] = $produkt['cena'];
       $this->data['cena2'] = $produkt['cena2'];
        $this->data['vbaleni'] = $produkt['vbaleni'];
          $this->data['limit'] = $produkt['limit'];
		   $this->data['davkovani'] = $produkt['davkovani'];
       $this->data['info'] = $produkt['info'];
            $this->data['upozorneni'] = $produkt['upozorneni'];
                $this->data['neobsahuje'] = $produkt['neobsahuje'];
       
			// Nastavení šablony
			$this->pohled = 'produkt';
		} else if($parametry[0])
    {
    
    }
		else
		// Není zadáno URL článku, vypíšeme všechny
		{

		$this->pohled = 'rozcesti';
		}
    }
}