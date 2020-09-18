<?php

class LoginKontroler extends Kontroler
{
	public function zpracuj($parametry)
	{

    $this->hlavicka = array(
			'titulek' => 'Hlavní strana',
			'klicova_slova' => 'kontakt, email, formulář',
			'popis' => 'Kontaktní formulář našeho webu.' ,
      'menu' => 'index'
		);

    if($parametry[0]=="logout")
    {
      $id=$_SESSION['user_id'] ;

         Db::query('DELETE FROM `logged_in_member` WHERE `user_id`=:id',array(":id"=>$id));
         session_start();
          session_destroy();
          session_unset();
           $_SESSION['admin']=0;
          session_regenerate_id(true);
         $this->presmeruj("index");

    }

        if(isset($_POST["email"])&&isset($_POST["heslo"]))
        {
          $aut = new Autorizace();
          $z= $aut->login($_POST["email"],$_POST["heslo"]);

            if( $z==0)
            {
            $this->presmeruj("index");
            } else
            {
                $this->data["x"]= $aut->checkErrorLogin($z);
            }
        }

      	$this->pohled = 'login';
    }


}