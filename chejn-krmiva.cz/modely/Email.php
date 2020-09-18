<?php

class Email {
   
public function sendEmail($to,$from, $subject, $text,$admin=false)
{

require_once 'email/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

//$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp-136226.m26.wedos.net';  // Specify main and backup SMTP servers
  $mail->CharSet='utf-8';
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'objednavka@chejn-krmiva.cz';                 // SMTP username
$mail->Password = 'a18#O7i%W';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
 $mail->setLanguage('cs', '/language');
$mail->setFrom($from);
$mail->addAddress($to);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@chejn-krmiva.cz');
//$mail->addCC('cc@example.com');
//$mail->addBCC('kaskader202@gmail.com');
//$mail->addBCC('objednavka@chejn-krmiva.cz');
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML
  $text=self::makeHtml($admin);
$mail->Subject = $subject;
$mail->Body    = $text;
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

/*
$headers   = array();
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-type: text/html; charset=utf-8";
$headers[] = "From: Chejn <{$from}>";
$headers[] = "Reply-To: Chejn <{$from}>";
$headers[] = "Subject: {$subject}";
$headers[] = "X-Mailer: PHP/".phpversion();
 */
//mail($to, $subject, $email, implode("\r\n", $headers));

 
 
//mail($to, $subject, $text, implode("\r\n", $headers));
$mail->send();

}



private function makeHtml($admin=false)
{
 $spravceKosiku = new SpravceKosiku();
 $id_zakaznika=  $spravceKosiku->getUserId();
$fakturace=Db::queryOne("Select * from fakturace where id_zakaznika = :idzakaznika order by id DESC",array(":idzakaznika"=>$id_zakaznika));

    $cenaPlatby= $spravceKosiku->getPricePaymentMethodName($fakturace['platba']); 
      /*Id faktury*/
$id_fakturace=$fakturace["id"];


  $kosik =$spravceKosiku->vratVeciKEmailu($id_fakturace);

$text='<!DOCTYPE html>
<html lang="cz"> 
<head>
<meta charset="utf-8">  
<title>Objednavka č.: '.$id_fakturace.'</title>
<style>
table tr td {padding-right: 20px;}
</style>
</head>

<body style="text-align:left;">
';

 if(!$admin)
 {
  $text.='Dobrý den,<br /> <br />
 
potvrzujeme převzetí Vaší objednávky.  <br />   <br />
  ';
if("Převodem z účtu"==$fakturace["platba"])  
  {
$text.='
Objednali jste zboží s možností <b>platby předem</b>. Během max. dvou dnů dostanete naši fakturu s veškerými platebními údaji. Jakmile bude faktura uhrazena, odešleme Vaši zásilku, dostanete e-mail s jejím číslem a poté Vás bude kontaktovat řidič PPL před předáním zboží.    <br /> 
';
 }
else{
$text.='
Objednávali jste zboží <b>na dobírku</b>. Během max. dvou dnů dostanete informaci o odeslání zásilky a její číslo, poté Vás bude kontaktovat řidič GEIS před samotným předání zboží. Platba u řidiče je možná jak v hotovosti, tak platební kartou.<br /> ';
}
}

$text.='<div class="table-responsive">

<br>
<h2>Fakturační údaje a informace o doručení</h2><br />

<table class="table">
 <thead>
  <tr>
                                                    <th>Jméno: </th><td> '.$fakturace["jmeno"].'</td><th>Ulice: </th><td> '.$fakturace["ulice"].'</td>
                                                    </tr>
                                                    <tr>
                                                    <th>Příjmení: </th><td> '.$fakturace["prijmeni"].' </td><th>Město: </th><td> '.$fakturace["mesto"].'</td>
                                                    </tr>
                                                    <tr>
                                                    <th>Firma: </th><td>'.$fakturace["firma"] .'</td><th>PSČ: </th><td>'. $fakturace["psc"] .'</td>
                                                    </tr>
                                                       <tr>
                                                    <th>Telefon: </th><td>'. $fakturace["telefon"].'</td><th>Email: </th><td>'. $fakturace["email"] .'</td>
                                                    </tr>
                                                      <tr>
                                                    <th>Platba: </th><td>'.$fakturace["platba"] .'</td><th>Doprava: </th><td>'. $fakturace["doprava"] .'</td>
                                                    </tr>
                                                       <tr>
                                                    <th>Poznámka: </th><td colspan="3">'.$fakturace["poznamka"] .'</td>
                                                    </tr>
                                            
</thead>
</table> 
  <h2>Objednávka</h2>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Položka</th>
                                                    <th>Kvantita</th>
                                                    <th>Cena za jednotku</th>
                                                    
                                                    <th colspan="2">Celkem</th>
                                                </tr>
                                            </thead>
                                            <tbody> ';
                                            
                                            $celkem=0; $cena=0;
                                       foreach ($kosik as $zbozi){
                                         if($zbozi["kusu"]/$zbozi["limit"]>=1)
                                                      {
                                                     $cena = $zbozi['cena2'];
                                                      } else
                                                      {
                                                         $cena=$zbozi['cena'];
                                                      }
                                                      
                                             $celkem+=$cena*$zbozi["kusu"]*(1-$zbozi['sleva']);
                                       $text.='
                                                    <tr>
  <td> 
    
    <a href="http://chejn-krmiva.cz/zbozi/'.str_replace(" ","-",$zbozi["url"]).'">
      <img src="http://chejn-krmiva.cz/img/produkty/'.$zbozi["obrazek"].'" alt="'.$zbozi["nazev"].'" height="100px">
    </a>

  </td>
  <td>
    <a href="http://chejn-krmiva.cz/zbozi/'.str_replace(" ","-",$zbozi["url"]).'">'.$zbozi["nazev"].'</a>
  </td>
  <td>
   '.$zbozi["kusu"].'
  </td>
 <td>
    '.number_format($cena*(1-$zbozi['sleva']), 2, ',', ' ').' Kč
  </td>

  <td>
 '.number_format($cena*$zbozi["kusu"]*(1-$zbozi['sleva']), 2, ',', ' ').'Kč
  </td>
    
</tr>   

';
                                             
                                          }

$text.='</tbody>       
     <tfoot>
     <tr>
      <th colspan="5">Platba</th>
      <th>'.number_format($cenaPlatby, 2, ',', ' ').' Kč</th>      
     </tr>
     <tr>
      <th colspan="5">Celkem</th>
      <th>'.number_format($celkem+$cenaPlatby, 2, ',', ' ').'Kč</th>
     </tr>
    </tfoot>
   </table><br /><br />  ';
                                        
                                        
                                  if(!$admin)
 {       $text.='
Děkujeme za Vaši objednávku.   <br /> 
S pozdravem  <br />   
Společnost Josef Chejn, spol. s r. o.   <br />
Křenice 42            <br />
<a href="www.chejn-krmiva.cz">www.chejn-krmiva.cz ';

     }
     $text.='
</body>
</html>';

return $text;

}

}

