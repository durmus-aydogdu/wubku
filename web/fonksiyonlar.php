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
 
function uygulamaninOzetDegeriniHesapla ( $uygulamaYol ,$uygulamaId ) {
	shell_exec('/opt/wubku/bin/uygulamaOzetDegeriHesapla.sh /opt/wubku/bin/vtBilgiler.txt '.$uygulamaYol." ".$uygulamaId);
	butunUygulamalarinOzetDegeriniHesapla();
}

function dizinOlustur ( $dizinYolu , $uygulamaYol ,$uygulamaId ) {
	shell_exec('/opt/wubku/bin/dizinEkle.sh '.$dizinYolu);
	uygulamaninOzetDegeriniHesapla ( $uygulamaYol ,$uygulamaId );
}

function dizinDuzenle ( $dizinYoluEski ,  $dizinYoluYeni, $uygulamaYol ,$uygulamaId ) {
	shell_exec('/opt/wubku/bin/dizinDuzenle.sh '.$dizinYoluEski." ".$dizinYoluYeni );
	uygulamaninOzetDegeriniHesapla ( $uygulamaYol ,$uygulamaId );
} 


function wubkuDurdur () {
	shell_exec('/opt/wubku/bin/wubkuDurdur.sh'); 
}
 
function wubkuDevamEt () {
	shell_exec('/opt/wubku/bin/wubkuDevamEt.sh'); 
}

function dizinSil ( $dizinYolu, $uygulamaYol ,$uygulamaId ) {
	 shell_exec('/opt/wubku/bin/dizinSil.sh '.$dizinYolu);	
	 uygulamaninOzetDegeriniHesapla ( $uygulamaYol ,$uygulamaId );
}

function dosyaSil ( $dosyaYolu, $uygulamaYol ,$uygulamaId ) {
	 shell_exec('/opt/wubku/bin/dosyaSil.sh '.$dosyaYolu);	
	 uygulamaninOzetDegeriniHesapla ( $uygulamaYol ,$uygulamaId );
}

function butunUygulamalarinOzetDegeriniHesapla () {
	 shell_exec('/opt/wubku/bin/butunUygulamalarinOzetDegeriniHesapla.sh /opt/wubku/bin/vtBilgiler.txt');	
}

function islemiKaydet ($islemMesaj, $FKUygulamaId) {

	$islemTarih = date('Y-m-d H:i:s');
	 
	if(mysql_query("insert into tblIslemler (islemMesaj,islemTarih,FKUygulamaId) values('$islemMesaj','$islemTarih','$FKUygulamaId')"))	
		return 0;
	else
		return 1;
}


function ipAdresiBul(){  
	if(isset($_SERVER['HTTP_CLIENT_IP'])){

		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}

	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	else if(isset($_SERVER['HTTP_X_FORWARDED'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED'];
	}

	else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	}

	else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_FORWARDED_FOR'];
	}

	else if(isset($_SERVER['HTTP_FORWARDED'])){
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_FORWARDED'];
	}
	else{
			$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}


?>