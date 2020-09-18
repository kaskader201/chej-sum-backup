<?php

class EditProduktuKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
  
    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.'
		);
    
   if($parametry[0]=="ulozit")
    {
                                        
      
                
              
         
         $x= Db::query('UPDATE `zbozi` SET `nazev`=?,`kratkyPopis`=?,`dlouhyPopis`=?,`slozeni`=?,`davkovani`=?,`hmotnost`=?,`cena`=?,`cena2`=?,`vbaleni`=?,`obrazek`=?,`url`=?,`skladem`=?,`new`=?,`sleva`=?,`lepek`=?,`info`=?,`upozorneni`=?,`neobsahuje`=?,`limit`=? WHERE `id`=?',array($_POST["nazev"],$_POST["kratkyPopis"],$_POST["dlouhyPopis"],$_POST["slozeni"],$_POST["davkovani"],$_POST["hmostnost"],$_POST["cena"],$_POST["cena2"],$_POST["vbaleni"],$_POST["obrazek"],$_POST["url"],$_POST["skladem"],$_POST["new"],$_POST["sleva"],$_POST["lepek"],$_POST["info"],$_POST["upozorneni"],$_POST["neobsahuje"],$_POST["limit"],$_POST["id"]));
      
      Db::query('UPDATE `rozrazeni` SET `id_category`=? WHERE `id_zbozi`=?',array($_POST["category"],$_POST["id"]));
          	$this->presmeruj('produkty');
    }else if($parametry[0]=="odstranit")    
    {
           Db::query('DELETE FROM `zbozi` WHERE `id`=:id',array(":id"=>$_POST["id"]));
           
           
           $this->presmeruj('produkty');
    
    }else{  
            
    $cisloProduktu =$parametry[0];
     
  $spravceProduktu= new SpravceProduktu();
                                             
                                                  
             $this->data["udaje"] =$spravceProduktu-> vratProdukt($cisloProduktu);
             $this->data["patri"] =   $spravceProduktu->vratKategoriiProduktu($cisloProduktu);
             $this->data["kategorie"] =$spravceProduktu->vratKategorie();
      	$this->pohled = 'edit_produktu';
        }
    }
}