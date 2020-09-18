<?php

// Controller pro výpis Košíku

class KosikKontroler extends Kontroler
{     
        public function zkontroluj()
        {
           $spravceKosiku = new SpravceKosiku();
            return $spravceKosiku->getCount();
        }
          
    public function zpracuj($parametry)
    {        
		// Vytvoření instance modelu, který nám umožní pracovat s články
      
        $spravceKosiku = new SpravceKosiku(); 
		// Je zadáno URL článku
   if ($parametry[0]=="platba")
		{
             if(self::zkontroluj()==0)
           {
              	$this->presmeruj('kosik'); 
           }

     // $spravceplatby = new SpravcePlatby(); 
         	 
	           
      
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => "Chejn eshop košík - Platba",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
			
			// Naplnění proměnných pro šablonu		
            $kosikNavrat =0;
            $this->data['kosik'] =$kosikNavrat;
		
			 	$this->data['celkem'] = $spravceKosiku->getSumPrice();
			// Nastavení šablony
			$this->pohled = 'platba'; 
		}  
  
    elseif ($parametry[0]=="doprava")
		{   
        if($_POST["payment"]==""&&!isset( $_COOKIE['platba']))
           {
              	$this->presmeruj('kosik/platba'); 
           }


     // $spravceplatby = new SpravcePlatby(); 
           $platba= $_POST["payment"];
            setcookie("platba", $platba, time() + (86400 * 30), "/");
	         $_SESSION['platba']= $platba;
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => "Chejn eshop košík - doprava",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
			
			// Naplnění proměnných pro šablonu		
		//	$this->data['kosik'] =$kosikNavrat;
		      $this->data['platba']  =$_SESSION['platba'];
          
			 	$this->data['celkem'] = $spravceKosiku->getSumPrice();
			// Nastavení šablony
			$this->pohled = 'doprava'; 
		} 
     /* Košík -> vyplnění adresy a údajů */
     elseif ($parametry[0]=="adresa")
		{
     if($_POST["delivery"]==""&&!isset($_COOKIE['doprava']))
           {
              	$this->presmeruj('kosik/doprava'); 
           }   
    
   
     // $spravceplatby = new SpravcePlatby(); 
       
	     $doprava = $_POST['delivery'];
        setcookie("doprava", $doprava, time() + (86400 * 30), "/");
       $_SESSION['doprava']= $doprava;
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => "Chejn eshop košík",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
			
     
			$this->pohled = 'adresa'; 
		}
    /* Košík -> dokončení */
      elseif ($parametry[0]=="dokonceni")
		{
        if(self::zkontroluj()==0)
           {
              	$this->presmeruj('kosik'); 
           }
 
     // $spravceplatby = new SpravcePlatby(); 
     
     foreach($_POST as $key => $value) {
  if($value==""&&$key!="company")
  {
    	$this->presmeruj('kosik/adresa'); 
  }
}
     
     $celkem=$spravceKosiku->getPricePaymentMethod($_COOKIE['platba'])+ $spravceKosiku->getPriceDeliveryMethod($_COOKIE['doprava'])+$spravceKosiku->getSumPrice();
      $id_zakaznika=  $spravceKosiku->getUserId();
     $doprava= $spravceKosiku->getDeliveryMethod($_COOKIE['doprava']);
     $platba=$spravceKosiku->getPaymentMethod($_COOKIE['platba']);
     
  $fakturace=array(":id_zakaznika"=>$id_zakaznika,":jmeno"=>$_POST['firstname'],":prijmeni"=>$_POST['lastname'],":firma"=>$_POST['company'],":ulice"=>$_POST['street'],":mesto"=>$_POST['city'],":psc"=>$_POST['zip'],":telefon"=>$_POST['phone'],":email"=>$_POST['email'],":doprava"=>$doprava,":platba"=>$platba,":celkem"=>$celkem,":datum"=>date("Y-m-d H:i:s"));
     
             $_SESSION['firstname']= $_POST['firstname'];
             $_SESSION['lastname']= $_POST['lastname'];
             $_SESSION['company']= $_POST['company'];
             $_SESSION['street']= $_POST['street'];
             $_SESSION['city']= $_POST['city'];
             $_SESSION['zip']= $_POST['zip'];
             $_SESSION['phone']= $_POST['phone'];
             $_SESSION['email']= $_POST['email'];



       
          Db::query('INSERT INTO `fakturace`(`id_zakaznika`,`jmeno`, `prijmeni`, `firma`, `ulice`, `mesto`, `psc`, `telefon`, `email`, `doprava`, `platba`, `celkem`, `datum`)
 VALUES (:id_zakaznika,:jmeno,:prijmeni,:firma,:ulice,:mesto,:psc,:telefon,:email,:doprava,:platba,:celkem,:datum)',$fakturace);
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	         
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => "Chejn eshop košík",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
			
			// Naplnění proměnných pro šablonu		
       	
		    $this->data['kosik'] = $spravceKosiku->vratKosik();
		    $this->data['fakturace']  =$fakturace;
        $this->data['platba']= $platba;
        $this->data['doprava']= $doprava;
        $this->data['cenaPlatby']= $spravceKosiku->getPricePaymentMethod($_SESSION['platba']); 
        $this->data['cenaDopravy']=$spravceKosiku->getPriceDeliveryMethod($_SESSION['doprava']);
			 	
         $this->data['celkem'] = $spravceKosiku->getSumPrice();
			// Nastavení šablony
			$this->pohled = 'dokonceni'; 
		}
    
    
    elseif ($parametry[0]=="odeslat")
		{   

              if(self::zkontroluj()==0)
           {
              	$this->presmeruj('kosik'); 
           }
    	$this->hlavicka = array(
				'titulek' => "Chejn eshop košík",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
     // $spravceplatby = new SpravcePlatby(); 
      
      
      
      
      
      
      
      
      /** kotrola Dat podle db **/
      $id_zakaznika=  $spravceKosiku->getUserId();
       /*Id zaznamu faktury*/
      $id_fakturace=Db::queryOne("Select id from fakturace where id_zakaznika = :idzakaznika order by id DESC",array(":idzakaznika"=>$id_zakaznika));
      /*Id faktury*/
       $id_fakturace=$id_fakturace["id"];
       
      $dataZKosiku = $spravceKosiku->vratVeciKFakturaci();
                                        
      Db::query('UPDATE `fakturace` SET `poznamka`=:poznamka WHERE id = :id',array( ":poznamka"=>  $_POST['poznamka'], ":id"=>$id_fakturace));
             
             
      foreach ( $dataZKosiku as $value) {
      	 Db::query('INSERT INTO `objednavky`(`id_fakturace`, `id_zbozi`, `kusu`,sleva) VALUES (:id_fakturace,:id_zbozi,:kusu,:sleva)',array(":id_fakturace"=>$id_fakturace,":id_zbozi"=>$value["id_zbozi"],":kusu"=> $value["kusu"],":sleva"=>$value["sleva"]));
         
         
         
         
         
         
               $pocetNaSklade= Db::queryOne('SELECT skladem FROM zbozi WHERE id=:id',array(":id"=>$value["id_zbozi"]));
               
               $novyPocetNaSklade=$pocetNaSklade[0]-$value["kusu"];
               
               
                Db::query('UPDATE `zbozi` SET `skladem`=:skladem WHERE id = :id',array( ":skladem"=>  $novyPocetNaSklade, ":id"=>$value["id_zbozi"]));
             Db::query('UPDATE `fakturace` SET `poznamka`=:poznamka WHERE id = :id',array( ":poznamka"=>  $_POST['poznamka'], ":id"=>$id_fakturace));
         
         Db::query("DELETE FROM `kosik` WHERE `id`=:id",array(":id"=>$value["id"]));
      }
      /** tvorba faktury **/
      
      
      
      /** připravení emailu **/
          $email = new Email();
          
          $email_text_admin="<h1>Nová objednávka č.:".$id_fakturace.'</h1><br /><a href="http://admin.chejn-krmiva.cz/objednavka/'.$id_fakturace.'">Přejít na podrobnosti objednávky</a>';
          
       //     $email->sendEmail("media@canis-media.cz","system@chejn-krmiva.cz", "Nová objednávka", $email_text_admin,true);
          //  $email->sendEmail("kaskader202@gmail.com","system@chejn-krmiva.cz", "Nová objednávka kopie", $email_text_admin,true);
                        
                        
                        
          
 
                       
                       
           $from="objednavka@chejn-krmiva.cz";
           $email_to=Db::queryOne('SELECT email FROM `fakturace` WHERE `id_zakaznika` ="'.$id_zakaznika.'" ');


            $to=$email_to["email"];

                 // $this->data['cenaDopravy']=  $email_to;
            $subject="Potvrzení objednávky Chejn";
            $email_text="";
            
            
            
            
              //klient
              $email->sendEmail($to,$from, $subject, $email_text,false);
               //firma
             $email->sendEmail('info@chejn-krmiva.cz',$from, $subject." kopie pro firmu", $email_text,false);
             $email->sendEmail('objednavka@chejn-krmiva.cz',$from, $subject." kopie pro zalohu", $email_text,false);
           //$email->sendEmail("info@chejn-krmiva.cz",$from, $subject, $email_text,false);
           //  $email->sendEmail("kaskader202@gmail.com",$from, $subject." Admin", $email_text,false);
	
			// Hlavička stránky
		
			
			// Naplnění proměnných pro šablonu		
       	
			  /*$this->data['kosik'] = $spravceKosiku->vratKosik();
		   	$this->data['fakturace']  =$fakturace;
        
        $this->data['platba']= $spravceKosiku->getPaymentMethod($_SESSION['platba']);
        $this->data['doprava']= $spravceKosiku->getPaymentMethod($_SESSION['doprava']);
        $this->data['cenaPlatby']= $spravceKosiku->getPricePaymentMethod($_SESSION['platba']); 
        $this->data['cenaDopravy']=$spravceKosiku->getPriceDeliveryMethod($_SESSION['doprava']);
			 	
         $this->data['celkem'] = $spravceKosiku->getSumPrice();   */
         
         
         
         
			// Nastavení šablony
			$this->pohled = 'odeslat'; 
		}
		else
		// Není zadáno , vypišem košik
    		{
             $spravceKosiku = new SpravceKosiku();
			// Získání článku podle URL
			$kosikNavrat = $spravceKosiku->vratKosik();
			// Pokud nebyl článek s danou URL nalezen, přesměrujeme na ChybaKontroler
	
			// Hlavička stránky
			$this->hlavicka = array(
				'titulek' => "Chejn eshop košík",
				'klicova_slova' => "",
				'popis' => $parametry,
			);
			
			// Naplnění proměnných pro šablonu		
			$this->data['kosik'] =$kosikNavrat;
		
			 	$this->data['celkem'] = number_format($spravceKosiku->getSumPrice(), 2, ',', ' ');
			// Nastavení šablony
			$this->pohled = 'kosik';
		}     
    }
}