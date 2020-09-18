<?php

class IndexKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  	$spravceProduktu = new SpravceProduktu();
		  $stranka=0;
      $stranka=$_GET["stranka"];
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.',
       'modal'=>  'sss'
       		);
        // $this->data['eshop']  =false;


     
     
$celkem = $spravceProduktu->vratPocetProduktu();

 
if (isset($_GET['stranka']))
	$stranka = $_GET['stranka'];
else
{
	$stranka = 1;
 }
$spravceNavigace=new SpravceNavigace(); 
$strankovani=$spravceNavigace->vratStrankovani($celkem,$stranka);

           
    $produkty= $spravceProduktu->vratProdukty(($stranka*6)-6);

       $this->data['aktualniStrana'] =$stranka;    
      
		  $this->data['strankovani'] = $strankovani;
         $this->data['produkty'] =$produkty;
       
      
      	$this->pohled = 'eshop';
    }
}