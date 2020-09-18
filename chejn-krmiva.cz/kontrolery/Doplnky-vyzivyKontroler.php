<?php

class Doplnky-vyzivy extends Kontroler
{
	public function zpracuj($parametry)
	{
  	$spravceProduktu = new SpravceProduktu();
		 
      
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.'
		);
     


     
     
 $celkem = $spravceProduktu->pocetProduktuDleKategorie(3);
 
if (isset($_GET['stranka']))
	$stranka = $_GET['stranka'];
else
{
	$stranka = 1;
 }
 
 
  $spravceNavigace=new SpravceNavigace();
  $strankovani=$spravceNavigace->vratStrankovani($celkem,$stranka,9);

           
      // $produkty= $spravceProduktu->vratProdukty(($stranka*8)-8);



       $this->data['aktualniStrana'] =$stranka;    
      
		   $this->data['strankovani'] = $strankovani;
      $produkty= $spravceProduktu->vratProduktyDleKategorie(3,($stranka*9)-9,9);       
       $this->data['produkty'] =$produkty;
		   $this->data['strankovani'] = $strankovani;

      	$this->pohled = 'psi';
    }
}