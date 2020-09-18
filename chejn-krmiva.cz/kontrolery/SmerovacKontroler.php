<?php

// Router je speciální typ controlleru, který podle URL adresy zavolá
// správný controller a jím vytvořený pohled vloží do šablony stránky

class SmerovacKontroler extends Kontroler
{
	// Instance controlleru
	protected $kontroler;
 

	// Metoda převede pomlčkovou variantu controlleru na název třídy
	private function pomlckyDoVelbloudiNotace($text) 
	{
		$veta = str_replace('-', ' ', $text);
		$veta = ucwords($veta);
		$veta = str_replace(' ', '', $veta);
		return $veta;
	}
	
	// Naparsuje URL adresu podle lomítek a vrátí pole parametrů
	private function parsujURL($url)
	{
		// Naparsuje jednotlivé části URL adresy do asociativního pole
        $naparsovanaURL = parse_url($url);
		// Odstranění počátečního lomítka
		$naparsovanaURL["path"] = ltrim($naparsovanaURL["path"], "/");
		// Odstranění bílých znaků kolem adresy
		$naparsovanaURL["path"] = trim($naparsovanaURL["path"]);
		// Rozbití řetězce podle lomítek
		$rozdelenaCesta = explode("/", $naparsovanaURL["path"]);
		return $rozdelenaCesta;
	}

    // Naparsování URL adresy a vytvoření příslušného controlleru
    public function zpracuj($parametry)
    {
		$naparsovanaURL = $this->parsujURL($parametry[0]);
				
		if (empty($naparsovanaURL[0]))		
			$this->presmeruj('eshop');		
		// kontroler je 1. parametr URL
		$tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';
		
		if (file_exists('kontrolery/' . $tridaKontroleru . '.php'))
			$this->kontroler = new $tridaKontroleru;
		else {
    			$this->presmeruj('eshop');
    }

		
		// Volání controlleru
        $this->kontroler->zpracuj($naparsovanaURL);
        
		   //kontrola session
      $aut=new Autorizace();
       $aut->generateSession();
      	$spravceProduktu = new SpravceProduktu();
           
           
           
           
                                      
		// Nastavení proměnných pro šablonu
		$this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
		$this->data['popis'] = $this->kontroler->hlavicka['popis'];
		$this->data['klicova_slova'] = $this->kontroler->hlavicka['klicova_slova'];
     $this->data['pocet_kocky'] = $spravceProduktu->pocetProduktuDleKategorie(2);
     $this->data['pocet_psi'] = $spravceProduktu->pocetProduktuDleKategorie(1);
      /*              $poziceOtazniku=strpos($parametry[0],"?");
     if($poziceOtazniku==0)
     {
       $poziceOtazniku=1;
     }  */
     
     //substr(ltrim($parametry[0], "/"),($poziceOtazniku-1))
       $this->data['aktualni_sekce'] =ltrim($parametry[0], "/");
   // $this->data['login'] = 
		$this->data['kosik'] = SpravceKosiku::getCountProduct() ;	
    
    		$this->data['cesta'] = $parametry[0];
      
    
		// Nastavení hlavní šablony
		$this->pohled = 'rozlozeni';
    }

}