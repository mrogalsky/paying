<?php

if (empty($_POST)) {
     header('Location: http://localhost/step1.php');
     exit;
}

include ('db.php');

$random = date("dmyhi");
$skidka = $_POST ['skidka'];

$kupon = 'minus';


$amt = $_POST ['amt'];
$details = $_POST ['details'];
$ext_details = $_POST ['ext_details'];
$skidkas = $amt*0.85; 


$emeil = $_POST ['emeil'];
$name = $_POST ['names'];

$paysistem = 'liqpay';


if ($skidka == $kupon) {
$amts = $skidkas;
} else {
$amts = $amt;
}



function _is_curl_installed() {
    if  (in_array  ('curl', get_loaded_extensions())) {
        return true;
    }
    else {
        return false;
    }
}
function getKurs() {
    global $dna; $dna = true;
    if ( _is_curl_installed() ){
        $url = "https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5";
        $curl = curl_init($url);
        if ( $curl ){
            // Скачанные данные не выводить поток
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
            // Скачиваем
            $page = curl_exec($curl);   //В переменную $page помещается страница
 
            curl_close($curl);
            unset($curl);
 
            $xml = new SimpleXMLElement($page);
            return $xml->row[2]->exchangerate['buy'][0];
        }
    }
}

$summa = $amts;
//$kursUAH = (float)getKurs();
//echo $kursUAH;
//echo $summa;
       //$summa = $summa * $kursUAH;
       //echo $summa;
	   if (empty($emeil) || empty($name) ){$input = '<span style="color:red;"><b>Вы не ввели Email либо Имя. Вернитесь и введите необходимые данные</b></span>';}else{$input =  '<input type="submit"  name="btn_text" class="btn btn-info" value="Оплатить '.$amts.'$"/>';}	   


$private_key = '';
 $merchant_id='';
 $url="https://www.liqpay.com/api/pay";
 $method='card';
 $currency = 'USD';
 $type = 'buy'; 
 $server_url = 'ваш server url';
 $result_url = 'ваш url result';
 
// BASE64(SHA1(privat_key + amount + currency + public_key + order_id + type + description + result_url +server_url)

  $signature = base64_encode(sha1(
     $private_key.
     $summa.
     $currency.
     $merchant_id.
     $random.
     $type.
     $details.
	 $result_url.
	 $server_url
,1));


				
	echo '	<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Оплата товаров liqpay</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
     <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="background:#E1E3E4;width:100%;">
  <div class="container text-center"  style="padding-top:15px;">
  <div class="col-md-8 col-md-offset-2" style="padding:20px;background:#fff;/*min-height:800px;*/-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;">
  Liqpay. <br/>
 <div class="col-md-8 col-md-offset-4 text-left" style="padding-top:20px"><strong>Товар:</strong>
  <p><ins>Название:</ins> '.$details.'</p>
  <p><ins>Тип:</ins> '.$ext_details.'</p>
  <p><ins>Стоимость:</ins> '.$amts.' $</p>
  </div>		
	<form method="POST" accept-charset="utf-8" action="'.$url.'">
<input type="hidden" name="public_key" value="'.$merchant_id.'" />
<input type="hidden" name="amount" value="'.$summa.'" />
<input type="hidden" name="currency" value="'.$currency.'" />
<input type="hidden" name="description" value="'.$details.'" />
<input type="hidden" name="type" value="buy" />
<input type="hidden" name="pay_way" value="'.$method.'" />
<input type="hidden" name="result_url" value="'.$result_url.'" />
<input type="hidden" name="server_url" value="'.$server_url.'" />
<input type="hidden" name="order_id" value="'.$random.'" />
<input type="hidden" name="signature" value="'.$signature.'"/>
<input type="hidden" name="language" value="ru" />
<input type="hidden" name="sandbox" value="0"/>
'.$input.'

</form>
</div></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>';

	   $connect = mysql_connect($config['server'], $config['login'], $config['passw']) or die("Error!"); 
	   mysql_set_charset('utf8',$connect);// подключаемся к MySQL или, в случаи  ошибки, прекращаем выполнение кода 
mysql_select_db($config['name_db'], $connect) or die("Error!"); // выбираем БД  или, в случаии ошибки, прекращаем выполнение кода  
 	 $sql = "INSERT INTO pay(orderid, email, name, amts, details, ext_details, signature, paysistem) VALUES('".$random."', '".$emeil."', '".$name."', '".$amts."', '".$details."', '".$ext_details."', '".$signature."', '".$paysistem."')";
	 $result = mysql_query ( $sql );
	
	 

?>
