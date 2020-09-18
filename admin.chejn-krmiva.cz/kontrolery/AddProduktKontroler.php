<?php

class AddProduktKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.',
      'menu' => 'AddProdukt'
      
		);
    
   if($parametry[0]=="ulozit")
    {
             $nazevobrazku="";
            if(isset($_FILES['obrazek']['name'])){
                    $total = count($_FILES['obrazek']['name']);


                      //Get the temp file path
                      $tmpFilePath = $_FILES['obrazek']['tmp_name'];

                      //Make sure we have a filepath
                            if ($tmpFilePath != ""){
                              //Setup our new file path
                              $newFilePath = "../chejn-krmiva.cz/img/produkty/" . $_FILES['obrazek']['name'];
                               $nazevobrazku  =$_FILES['obrazek']['name'];
                              //Upload the file into the temp dir
                                      if(move_uploaded_file($tmpFilePath, $newFilePath)) {

                                        //Handle other code here

                                      }
                              }



            }
            $t="";
      foreach ($_POST as $key=>$value) {
      	    $t.= "`".$key."`, ";
      }


           
         $x= Db::query('INSERT INTO `zbozi`(`nazev`, `kratkyPopis`, `dlouhyPopis`, `slozeni`, `davkovani`, `hmotnost`, `cena`, `cena2`, `vbaleni`, `obrazek`, `url`, `skladem`, `new`, `sleva`, `lepek`, `info`, `upozorneni`, `neobsahuje`, `limit`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',array($_POST["nazev"],$_POST["kratkyPopis"],$_POST["dlouhyPopis"],$_POST["slozeni"],$_POST["davkovani"],$_POST["hmostnost"],$_POST["cena"],$_POST["cena2"],$_POST["vbaleni"],$nazevobrazku,$_POST["url"],$_POST["skladem"],$_POST["new"],$_POST["sleva"],$_POST["lepek"],$_POST["info"],$_POST["upozorneni"],$_POST["neobsahuje"],$_POST["limit"]));
        
         Db::query('INSERT INTO `rozrazeni`(`id_category`, `id_zbozi`) VALUES (?,?)',array($_POST["category"],Db::getLastId()));
          	$this->presmeruj('produkty');
    }else
    {          
    
                        $spravceProduktu = new SpravceProduktu();                     
                     $this->data["kategorie"] =$spravceProduktu->vratKategorie();
   
      	$this->pohled = 'add_produkt';
        }
    }
}