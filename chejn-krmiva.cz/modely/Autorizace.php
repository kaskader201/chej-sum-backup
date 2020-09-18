<?php

//Db::connect() ;
/**
 * Created by PhpStorm.
 * User: Jelinek
 * Date: 7.01.2016
 * Time: 1:31
 */
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
        return hash_hmac('sha512', $data, $this->_siteKey);
    }
//todo: kontrala jestli je admin
    /* public function isAdmin()
     {
         //$selection being the array of the row returned from the database.
         if($selection['is_admin'] == 1) {
             return true;
         }

         return false;
     }*/

    public function createUser($email, $password, $is_admin = 0)
    {
        //Generate users salt

        $user_salt = $this->randomString();
        //Salt and Hash the password
        $password = $user_salt . $password;
        $password = $this->hashData($password);

        //Create verification code
        $code = $this->randomString();

        $verification_code=$this->hashData($code);



        $db = new mysqli($this->db_server,$this->db_login,$this->db_pass,$this->db_database);
        $db->set_charset("utf8");
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql= 'INSERT INTO `users`(`email`, `password`, `user_salt`,verification_code) VALUES ("'.$email.'","'.$password.'","'.$user_salt.'","'.$code.'")';
       DB:query($sql);

        /* verification code email */
        //todo: dodělat verifikaci emailem + stránku která to bude dělat
        $subject = 'Your verification code';
        $header = 'Sent by me web site';
        $message = 'Your verification code is' . $verification_code;

        mail($email,$subject,$message,$header);

        return true;


    }
    public function showUserName()
    {
     $name=Db::queryOne('SELECT name FROM users WHERE id = :id',array(":id"=>$_SESSION['user_id']));
     return $name[0];
    }
    
  
    public function login($email, $password)
    {

        $db = new mysqli($this->db_server,$this->db_login,$this->db_pass,$this->db_database);
        $db->set_charset("utf8");
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }
      // $selection= Db::queryOne('SELECT * FROM `users` WHERE `email` = :email',array(":email"=>$email)) ;
      // return $selection;
        $sql = ('SELECT * FROM `users` WHERE `email` ="'.$email.'"');
         
        //Select users row from database base on $email
        $selection =$db->query($sql);
        $selection= $selection->fetch_assoc();  
        //  $user_salt="dx4bne70jtl4hwdez8qy9o5i94jfw6h8gfa3hgti3u9pd9dd";
        //Salt and hash password for checking
        $passwordOrigin = $password;
        $password = $selection['user_salt'].$password;

        $password = $this->hashData($password);
        // return $password." ".$selection['user_salt'];

        //Check email and password hash match database row
        if($selection['password']==$password || sha1($passwordOrigin) == $selection['password'])
        {
            $match=true;
        }

        //Convert to boolean
        $is_active = (boolean) $selection['is_active'];
        $verified = (boolean) $selection['verified'];

        if($match == true) {
            if($is_active == true) {
                if($verified == true) {
                    //Email/Password combination exists, set sessions
                    //First, generate a random string.
                     $non_Login=Db::dotazJeden('SELECT * FROM  non_logged_in_member where token = "'.$_SESSION["token"].'"');
                     $session_id = $non_Login["session_id"];
                     $token= $non_Login["token"];
                     
                     $pocet= Db::dotaz('SELECT id_user FROM  kosik WHERE id_user = "'.$_SESSION["token"].'"');
                      $id=Db::dotazVsechny('SELECT id FROM  kosik WHERE id_user = "'.$_SESSION["token"].'"');
                   for ($i=0;$i< $pocet;$i++) {
                    Db::dotaz('UPDATE `kosik` SET `id_user`="'.$selection['id'].'" WHERE `id` ="'.$id[$i][0].'"  ');
                   	
                   }
                   
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
                 // $session_id=session_id() ;
                    //Delete old logged_in_member records for user
                      
                    //Insert new logged_in_member record for user
                    $sqlInsert='INSERT INTO `logged_in_member`(`user_id`, `session_id`, `token`) VALUES ("'.$selection['id'].'","'.$session_id.'","'.$token.'")';
                    // return $sqlInsert;
                    $inserted =$db->query($sqlInsert);
                    $db->close();
                    //Logged in
                    if($inserted != false) {
                        return 0;
                    }

                    return 3;
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
                $string="Not verified user.";
                break;
            case 2:
                $string="Not active user.";
                break;
            case 3:
                $string="Not insert in db error.";
                break;
            case 4:
                $string="No match users.";
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
        }                      */
                    $random = $this->randomString();
                    //Build the token
                    $token = $_SERVER['HTTP_USER_AGENT'] . $random;
                    $token = $this->hashData($token);
                    $_SESSION['token'] = $token;
                    $session_id=session_id() ;
                     $_SESSION['login'] =false;
                    //Insert new logged_in_member record for user
                    $sqlInsert='INSERT INTO `non_logged_in_member`( `session_id`, `token`,ip) VALUES ("'.$session_id.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
                    // return $sqlInsert;
                    Db::query($sqlInsert);
                   
    
    }

          return true;
    }


  public function showLogin()
    {
     if($_SESSION["login"]==1)
      {    
                                 $text='<a href="customer-orders.php"><i class="fa fa-sign-in"></i> <span class="hidden-xs text-uppercase">'.self::showUserName().'</span></a> '; 
                            
      }else{
                            $text='<a href="#" data-toggle="modal" data-target="#login-modal"><i class="fa fa-sign-in"></i> <span class="hidden-xs text-uppercase">Přihlásit se</span></a>
                                <a href="customer-register.php"><i class="fa fa-user"></i> <span class="hidden-xs text-uppercase">Registrovat se</span></a>'; 
      }
     return $text;
    }



}