<?php

class SpravceNavigace {
   



 function vratStrankovani($celkem,$stranka,$pocetNaStrance=6)
 {


 $maxStranka=ceil($celkem/$pocetNaStrance);
 $strankovani[]="";
 
 for($i=1;$i<=$maxStranka;$i++)
 {   
      $strankovani[$i]["cislo"]=$i;
      $strankovani[$i]["url"]=$i;
      
      if($i==$stranka)
      {
      $strankovani[$i]["active"]=true; 
      }else{
      $strankovani[$i]["active"]="";
      }
        
 
 
 }
 
 
   $strankovani[0]["cislo"]="-&laquo-";
   $strankovani[0]["url"]=1;
  
   $strankovani[count($strankovani)]["cislo"]= "-&raquo;-";
   $strankovani[count($strankovani)-1]["url"]= $maxStranka;
   
   return $strankovani;
 }
}