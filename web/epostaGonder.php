<?php
/* 
  Web Uygulamalarý Bütünlük Kontrol Uygulamasý (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanýn bütünlüðünün korunmasýný saðlamaktadýr.
  Copyright (C) <2015>  <Durmuþ AYDOÐDU>

   This program is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>

*/
?>

<?php

include('vtBaglanti.php');
require 'phpmailer.php';
require_once('smtp.php');

$epostaGonderSorgu = mysql_query("select saldiriId,saldiriTarih, saldiriMesaj, eposta from tblKullanici as k, tblYetkiler as y, tblSaldirilar as s where k.kullaniciId=y.FKKullaniciId and y.FKUygulamaId=s.FKUygulamaId and epostaGonderildiMi=0 order by saldiriTarih asc limit 100") or die(mysql_error() );

while ($epostaGonder= mysql_fetch_array($epostaGonderSorgu)){
	$epostaKime=$epostaGonder["eposta"];
	$epostaMesaj=$epostaGonder["saldiriMesaj"];
	$epostaTarih=$epostaGonder["saldiriTarih"];
	$saldiriId=$epostaGonder["saldiriId"];
	
	$mail = new PHPMailer();
	$mail->CharSet ="ISO-8859-9";//Set the character set you need to specify
	$mail->IsSMTP(); // Use SMTP service
	$mail->SMTPAuth = true;
	$mail->Host = 'smtp.gmail.com';   
	$mail->IsHTML(true);
	$mail->From = 'wubku@gmail.com';
	$mail->FromName = 'WUBKU';            
	$mail->SMTPSecure = 'tls';                
	$mail->Port       = '587';                         
	$mail->Username   = 'wubku@gmail.com';             
	$mail->Password   = 'epostaHesabýþifresi';                                
	$mail->SetFrom('wubku@gmail.com', 'WUBBKU');
	$mail->Subject =  "WUBKU - Uygulamanýzda saldýrý tespit edildi";
	$mail->Body = $epostaTarih." tarihinde ".$epostaMesaj;
	$mail->AddAddress($epostaKime);
	if ($mail->Send()) 
		mysql_query("update tblSaldirilar set  epostaGonderildiMi=1 where saldiriId='$saldiriId'") or die(mysql_error());
}
?>

