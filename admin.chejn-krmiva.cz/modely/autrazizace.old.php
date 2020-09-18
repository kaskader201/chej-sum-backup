<?php
class Autorizace
{

    private $_siteKey;

    public function __construct()
    {
        $this->siteKey = 'some long site key &&&@,.<>>>*/-156794sad68';
    }


    private function randomString($length = 50)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $string = '';

        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters))];
        }

        return $string;
    }
    protected function hashData($data)
    {
        return hash_hmac('sha384', $data, $this->_siteKey);
    }
//todo: kontrala jestli je admin
  public function isAdmin($id)
     {
        Db::queryOne('Select is_admin from users where id=:id',array(":id"=>$id));
         //$selection being the array of the row returned from the database.
         if($selection['is_admin'] == 1) {
             return true;
         }

         return false;
     }

    public function createUser($name,$email, $password, $is_admin = 0)
    {
        //Generate users salt
        $pocet=Db::query('SELECT id FROM `users` where email=:email',array(":email"=>$email));
            if($pocet>0)
            {
            return false;
            }
        $user_salt = $this->randomString();
        //Salt and Hash the password
        $password = $user_salt . $password;
        $password = $this->hashData($password);

        //Create verification code
        $code = $this->randomString();

        $verification_code=$this->hashData($code);


        $insert= Db::query('INSERT INTO `users`(`name`,`email`, `password`, `user_salt`,verification_code) VALUES (:name,:email,:pass,:salt,:code)',array(":name"=>$name,":email"=>$email,":pass"=>$password,":salt"=>$user_salt,":code"=>$verification_code));

        /* verification code email */
        //todo: dodelat verifikaci emailem + stránku která to bude delat


        $emailClass= new Email();

        $subject = 'Aktivace vašeho úctu';

        $message = '<h1>Dekujeme za registraci</h1> <br /><br />  Aktivaci provedete kliknutím na <a href="http://www.ryzeceske.cz/registrace/overeni/'.$email.'/'.$verification_code.'">tento odkaz http://www.ryzeceske.cz/registrace/overeni/ </a> ';


          if($insert==1)
          {
              if($emailClass->sendBasicEmail($email,"info@ryzeceske.cz",$subject,$message))
              {
                return true;
              }else
              {
                  return false;
              }
          }else
          {
          return false;
          }



    }
    public function showUserName()
    {
     $name=Db::queryOne('SELECT name FROM users WHERE id = :id',array(":id"=>$_SESSION['user_id']));
     return $name[0];
    }


    public function login($email, $password)
    {

      // $selection= Db::queryOne('SELECT * FROM `users` WHERE `email` = :email',array(":email"=>$email)) ;
      // return $selection;


        //Select users row from database base on $email
        $selection =Db::queryOne('SELECT * FROM `users` WHERE `email` ="'.$email.'"');


        //Salt and hash password for checking
        $password = $selection['user_salt'].$password;

        $password = self::hashData($password);
        // return $password." ".$selection['user_salt'];

        //Check email and password hash match database row
        if($selection['password']==$password)
        {
            $match=true;
        }

        //Convert to boolean
        $is_active = $selection['is_active'];
        $verified = $selection['verified'];

        if($match == true) {
            if($is_active == 1) {
                if(($verified == 1)&&($selection['is_admin']==1)) {
                    //Email/Password combination exists, set sessions
                    //First, generate a random string.
                     $non_Login=Db::queryOne('SELECT * FROM  non_logged_in_member where token = "'.$_SESSION["token"].'"');

                     $session_id = $non_Login["session_id"];
                     $token= $non_Login["token"];



                    //Db::queryOne('DELETE FROM `non_logged_in_member` WHERE `token` ="'.$_SESSION["token"].'"');

                   // $random = $this->randomString();
                    //Build the token
                    //$token = $_SERVER['HTTP_USER_AGENT'] . $random;
                    //$token = $this->hashData($token);

                    //Setup sessions vars
                      session_start();
                    $_SESSION['login'] =true;
                    $_SESSION['token'] = $token;
                    $_SESSION['user_id'] = $selection['id'];
                    $_SESSION['admin'] =1;

                 // $session_id=session_id() ;
                    //Delete old logged_in_member records for user


                    // return $sqlInsert;
 // $inserted =Db::query('INSERT INTO `logged_in_member`(`user_id`, `session_id`, `token`,`ip`,`datum`) VALUES (:id,:session,:token,:ip,:datum)',array(":id"=>$selection['id'],":session"=>$session_id,":token"=>$token,":ip"=>$_SERVER['REMOTE_ADDR'],":datum"=>date("Y-m-d H:m:s")));

   //                 Db::query('DELETE FROM `non_logged_in_member` WHERE token=:token',array(":token"=>$token));
                    //Logged in
                    if($inserted == 1) {
                        return 0;
                    }

                    return 0;
                } else {
                    //Not verified
                    return 1;
                }
            } else {
                //Not active
                return 2;
            }
        }

        //No match, reject
        return 4;
    }

    /**
     * @param $number
     */
    public function checkErrorLogin($number)
    {
        $string="";
        switch($number)
        {
            case 1:
                $string="Not verified user or admin.";
                break;
            case 2:
                $string="Not active user.";
                break;
            case 3:
                $string="Not insert in db error.";
                break;
            case 4:
                $string="No match password.";
                break;
        }
        return $string;
    }
    public function checkSession()
    {
        //Select the row
      /*  $db = new mysqli($this->db_server,$this->db_login,$this->db_pass,$this->db_database);
        $db->set_charset("utf8");
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }    */
        //todo: dopsat vyber z databáze
         $sql = 'SELECT token,session_id FROM `logged_in_member` WHERE `session_id` ="'.session_id().'"';

        //Select users row from database base on $email
        $selection = Db::queryOne($sql);
      //  $selection= $selection->fetch_assoc();



        if($selection) {
            //Check ID and Token
            if(session_id() == $selection['session_id'] && $_SESSION['token'] == $selection['token']) {
                //Id and token match, refresh the session for the next request
                $this->refreshSession();
                return true;
            }

        }

        return false;
    }
    public function refreshSession()
    {


        //Regenerate id
        session_regenerate_id();

        //Regenerate token
        $random = $this->randomString();
        //Build the token
        $token = $_SERVER['HTTP_USER_AGENT'] . $random;
        $token = $this->hashData($token);

        //Store in session
        $_SESSION['token'] = $token;

    }
    public function generateSession()
    {
    if(empty($_SESSION['token'])&& $_SESSION['login']==false)
     {
         /* $db = new mysqli($this->db_server,$this->db_login,$this->db_pass,$this->db_database);
        $db->set_charset("utf8");
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }                   */
                   session_start();
                    $random = $this->randomString();
                    //Build the token
                    $token = $_SERVER['HTTP_USER_AGENT'] . $random;
                    $token = $this->hashData($token);
                    $_SESSION['token'] = $token;
                    $session_id=session_id() ;
                     $_SESSION['login'] =false;
                    //Insert new logged_in_member record for user
                    $sqlInsert='INSERT INTO `non_logged_in_member`( `session_id`, `token`,ip,datum) VALUES ("'.$session_id.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'","'.date("Y-m-d H:m:s").'")';
                    // return $sqlInsert;
                    Db::query($sqlInsert);


    }

          return true;
    }


  public function showLogin()
    {
    session_start();
     if($_SESSION["login"]==1)
      {
                                 $text='<a href="akce/logout">Odhlásit se</a><a href="zakaznicky-profil"><i class="fa fa-sign-in"></i> <span class="hidden-xs text-uppercase">'.self::showUserName().'</span></a> ';

      }else{
                            $text='<a href="#" data-toggle="modal" data-target="#login-modal"><i class="fa fa-sign-in"></i> <span class="hidden-xs text-uppercase">Prihlásit se</span></a>
                                <a href="registrace"><i class="fa fa-user"></i> <span class="hidden-xs text-uppercase">Registrovat se</span></a>';
      }
     return $text;
    }



}