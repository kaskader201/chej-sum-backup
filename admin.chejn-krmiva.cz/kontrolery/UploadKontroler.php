<?php
 class UploadKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{
       if(isset($_FILES['upload']['name'])){
                    $total = count($_FILES['upload']['name']);

                    $pocet=0;

                    // Loop through each file
                    for($i=0; $i<$total; $i++) {
                      //Get the temp file path
                      $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

                      //Make sure we have a filepath
                            if ($tmpFilePath != ""){
                              //Setup our new file path
                              $newFilePath = "../chejn-krmiva.cz/img/produkty/" . $_FILES['upload']['name'][$i];

                              //Upload the file into the temp dir
                                      if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                                         $pocet++;
                                        //Handle other code here

                                      }
                              }


                      }
                       if($pocet==$total)
          {
           $this->data["info"] ="Obrázek/y byly úspěšně nahrány.";
          }
            }



  $this->pohled="upload";
 }
}