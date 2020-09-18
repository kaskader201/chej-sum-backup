<?php

class KockyKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  	$spravceProduktu = new SpravceProduktu();
		 
      
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.'
		);
     


     
     
 $celkem = $spravceProduktu->pocetProduktuDleKategorie(2);
 
if (isset($_GET['stranka']))
	$stranka = $_GET['stranka'];
else
{
	$stranka = 1;
 }
  $spravceNavigace=new SpravceNavigace();
  $strankovani=$spravceNavigace->vratStrankovani($celkem,$stranka,9);

           
     //  $produkty= $spravceProduktu->vratProdukty(($stranka*6)-6);



       $this->data['aktualniStrana'] =$stranka;    
      
		   $this->data['strankovani'] = $strankovani;
      $produkty= $spravceProduktu->vratProduktyDleKategorie(2,($stranka*9)-9,9);       
       $this->data['produkty'] =$produkty;
		   $this->data['strankovani'] = $strankovani;

      	$this->pohled = 'kocky';
    }
}