<?php

// Controller pro výpis Produktu

class ProduktKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
		// Vytvoření instance modelu, který nám umožní pracovat s články
		$spravceProduktu = new SpravceProduktu();
		
		// Je zadáno nazev produktu
		if (!empty($parametry[0]))
		{
			// Získání článku podle URL
			$produkt = $spravceProduktu->vratProdukt($parametry[0]);
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
			if (!$produkt)
				$this->presmeruj('chyba');
		
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => $produkt['titulek'],
				'klicova_slova' => $produkt['klicova_slova'],
				'popis' => $produkt['popisek'],
			);
			
			// Naplnění proměnných pro šablonu		
    	$this->data['id_produktu'] = $produkt["info"];
			$this->data['nazev'] = $produkt['nazev'];
			$this->data['kratkyPopis'] = $produkt['kratkyPopis'];
      $this->data['dlouhyPopis'] = $produkt['dlouhyPopis'];
      $this->data['slozeni'] = $produkt['slozeni'];
			$this->data['obrazek'] = $produkt['obrazek'];
      $this->data['sleva'] = $produkt['sleva'];
       $this->data['add'] = $produkt['info'];
    
      $this->data['cena'] = $produkt['cena'];
            
      

			
			// Nastavení šablony
			$this->pohled = 'produkt';
		}
		else
		// Není zadáno URL článku, vypíšeme všechny
		{
			$clanky = $spravceClanku->vratClanky();
			$this->data['clanky'] = $clanky;
			$this->pohled = 'clanky';
		}
    }
}