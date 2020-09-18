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
			$this->presmeruj('index');		
		// kontroler je 1. parametr URL
		$tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';
		
		if (file_exists('kontrolery/' . $tridaKontroleru . '.php'))
			$this->kontroler = new $tridaKontroleru;
		else
			$this->presmeruj('chyba');
		
		// Volání controlleru
        $this->kontroler->zpracuj($naparsovanaURL);
        
		   //kontrola session
      $aut=new Autorizace();
       $aut->generateSession();
      // $aut->createUser("admin","adminadmin123","Administrator")   ;
      	$spravceProduktu = new SpravceProduktu();

		// Nastavení proměnných pro šablonu

   if(($_SESSION['admin']!=1) && (substr($parametry[0],1)!="login"))
      {

          $this->presmeruj('login');

      }else if(($_SESSION['admin']==1)&& (substr($parametry[0],1)=="login"))
      {
               $this->presmeruj('index');
      }


      $jmeno=Db::queryOne('select name from users where id=:id',array(":id"=>$_SESSION['user_id']));

		// Nastavení proměnných pro šablonu
		$this->data['titulek'] = $this->kontroler->hlavicka['titulek'];
		$this->data['popis'] = $this->kontroler->hlavicka['popis'];
		$this->data['klicova_slova'] = $this->kontroler->hlavicka['klicova_slova'];
  	$this->data['menu'] =  substr($parametry[0],1);
    $this->data['jmeno'] =$jmeno[0];



    if(substr($parametry[0],1)=="login")
    {
     	$this->pohled = 'loginRozlozeni';
    }
    else
    {
    	$this->pohled = 'rozlozeni';
    }
    }

}