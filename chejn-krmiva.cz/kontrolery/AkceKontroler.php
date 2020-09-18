<?php
class AkceKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {
        if($parametry[0]=="novinky")
        {
        $email = $_POST["email"];
        $ip=$_SERVER["REMOTE_ADDR"];
        Db::query('INSERT INTO `novinky`(`email`, `datum`, `ip`) VALUES (:email,:datum,:ip)',array("email"=>$email,":datum"=>date("Y-m-d H:i:s"),":ip"=>$ip));
         $this->presmeruj("index");
        }
    
    }
}