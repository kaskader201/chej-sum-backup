<?php

class Email {
   
public function sendEmail($to,$from, $subject, $text)
{
$headers   = array();
$headers[] = "MIME-Version: 1.0";
$headers[] = "Content-type: text/html; charset=utf-8";
$headers[] = "From: {$from}";
$headers[] = "To: {$to}";
$headers[] = "Subject: {$subject}";
$headers[] = "X-Mailer: PHP/".phpversion();

 $text=self::makeHtml($text);
return mail($to, $subject, $text, implode("\r\n", $headers));
}

private makeHtml($text)
{
$top='<!DOCTYPE html>
<html lang="cz"> 
<head></head>

<body>';

$bot='</body>

</html>';
 }
return $top.$text.$bot;

}

}

