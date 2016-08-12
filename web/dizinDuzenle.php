<?php
/* 
  Web Uygulamalar� B�t�nl�k Kontrol Uygulamas� (WUBKU),  web uygulama kaynak kodu �zerinden b�t�nl�k kontrol� yaparak, uygulaman�n b�t�nl���n�n korunmas�n� sa�lamaktad�r.
  Copyright (C) <2015>  <Durmu� AYDO�DU>

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


<?php include 'baslik.php' ?>
 
	<title>WUBKU - dizin d�zenle </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYazilimci.php';
include 'fonksiyonlar.php';
 

$dizinId=isset($_REQUEST["dizinId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["dizinId"])) : 'null';
$islem=isset($_POST["islem"]) ? mysql_real_escape_string(strip_tags($_POST["islem"])) : 'null';
$sonuc="null";
 
if($islem == "dizinDuzenle") { 

	$dizinId=isset($_POST["dizinId"]) ? mysql_real_escape_string(strip_tags($_POST["dizinId"])) : 'null'; 
	$dizinYolu=isset($_POST["dizinYolu"]) ? mysql_real_escape_string(strip_tags($_POST["dizinYolu"])) : 'null'; 
 
	$dizinAdKontrol=substr($dizinYolu, -1); 
	
	if ( !($dizinAdKontrol == "/") )
			$dizinYolu = $dizinYolu."/";
	
	$dizinSahibiMi=mysql_num_rows(mysql_query("select dizinId from tblDizinler as d, tblYetkiler as y where d.FKUygulamaId=y.FKUygulamaId and FKKullaniciId='$kulId' and dizinId='$dizinId'"));

	if($dizinSahibiMi==1){ 

	$eskiDizinAd = mysql_fetch_row(mysql_query("select dizinYol  from tblDizinler where dizinId='$dizinId'")) or die(mysql_error());
		
	
	
		
	if(mysql_query("update tblDizinler set dizinYol = '$dizinYolu' where dizinId='$dizinId'")) {

			$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
			$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaId, uygulamaKokDizin, uygulamaAd from tblUygulamalar, tblDizinler  where uygulamaId=FKUygulamaId and dizinId='$dizinId' ")) or die(mysql_error());

			
			dizinDuzenle ( $sunucuKokDizin[0]."".$uygulamaId[1]."".$eskiDizinAd[0],  $sunucuKokDizin[0]."".$uygulamaId[1]."".$dizinYolu , $sunucuKokDizin[0]."".$uygulamaId[1], $uygulamaId[0] );
			
			$mesaj=$kulId. " numaral� ".$kulAd." kullan�c�s� ". $uygulamaId[2]. " uygulamasinin ". $eskiDizinAd[0] . " adl� dizinini ".$dizinYolu." olarak de�i�tirdi.";
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId[0]);
		
			if ($sonuc == 0 ) {
				$sonuc="Dizin D�zenlendi.";
			}
			else
				$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
			
		}
		else {
			
			$sonuc="��lem yap�l�rken hata olu�tu. Tekrar Deneyiniz.";
		}
	}
	else
		$sonuc="Bu i�lemi yapmaya yetkili de�ilsiniz.";
 
}

$dizinBilgileri = mysql_fetch_row(mysql_query("select dizinId, dizinYol from tblDizinler where dizinId='$dizinId'")) or die(mysql_error());



?> 

  
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulama Dizinleri</h2>
        <p>Uygulamalar�n dizinlerini g�rebilir ve d�zenleme yapabilirsiniz.</p>
      </div>
	  	
		  <form class="form-signin" role="form" name="frmDizinGuncelle" method="post" action="dizinDuzenle.php">
 
		 <div class="form-group">
                <label> Dizin Yolu</label>
				<input type="text" class="form-control" id="dizinYolu" name="dizinYolu" value = "<?php echo $dizinBilgileri[1] ?>" required autofocus>
        </div>
		 <button type="submit" class="btn btn-success btn-sm form-control" name="islem" value="dizinDuzenle">G�ncelle</button>
		 
		<input type="hidden" name="dizinId" value="<?php echo $dizinBilgileri[0] ?>" />
      </form>
 
	 </div>
 
</body>
</html>