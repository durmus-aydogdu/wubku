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

<?php include 'baslik.php' ?>
	<title>WUBKU - dosya düzenle </title>
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'fonksiyonlar.php';
include 'ustMenuYazilimci.php';

$dosyaDuzenle=isset($_REQUEST["dosyaDuzenle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["dosyaDuzenle"])) : 'null';
$dosyaId=isset($_REQUEST["dosyaId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["dosyaId"])) : 'null';
$sonuc="null";
 
if($dosyaDuzenle != "null") { 


if($_FILES['dosya']['size'] > 0) {

	$dosyaAdi = $_FILES['dosya']['name'];
	$dosyaGeciciAd  = $_FILES['dosya']['tmp_name'];
	
	$dosyaId=isset($_POST["dosyaId"]) ? mysql_real_escape_string(strip_tags($_POST["dosyaId"])) : 'null';
 
	$dosyaOzetDegeri=shell_exec('sha512sum '.$dosyaGeciciAd.' | cut -d\' \' -f1');
	$dosya= addslashes(fread(fopen($dosyaGeciciAd, "rb"),filesize($dosyaGeciciAd)));
 
	$eskiDosyaAdi = mysql_fetch_row(mysql_query("select dizinYol,dosyaAd from tblDizinler, tblDosyalar where FKDizinId=dizinId and dosyaId='$dosyaId' ")) or die(mysql_error());
			
			
			
	 if(mysql_query("update tblDosyalar set dosyaAd='$dosyaAdi',dosya='$dosya',dosyaOzetDeger='$dosyaOzetDegeri'  where dosyaId='$dosyaId'")) {
	 

			$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
			$dosyaYolu = mysql_fetch_row(mysql_query("select dizinYol,dosyaAd from tblDizinler, tblDosyalar where FKDizinId=dizinId and dosyaId='$dosyaId' ")) or die(mysql_error());
			
			$uygulamaId = mysql_fetch_row(mysql_query("select FKUygulamaId, uygulamaKokDizin, uygulamaAd  from tblDosyalar, tblUygulamalar  where FKUygulamaId=uygulamaId and dosyaId='$dosyaId'")) or die(mysql_error());
			uygulamaninOzetDegeriniHesapla( $sunucuKokDizin[0]."".$uygulamaId[1], $uygulamaId[0] );
			
			
			$mesaj=$kulId. " numaralý ".$kulAd." kullanýcýsý ". $uygulamaId[2]. " uygulamasinin ". $eskiDosyaAdi[0] . " adlý dizinindeki ".$eskiDosyaAdi[1]." adlý dosyayý ".$dosyaYolu[1]." olarak güncelledi.";
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId[0]);
		
			if ($sonuc == 0 ) {
				$sonuc="Dosya Güncellendi.";
			}
			else
				$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";

		}
		else {
			
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}



} else {

	$dosyaYolu=isset($_POST["dosyaYolu"]) ? mysql_real_escape_string(strip_tags($_POST["dosyaYolu"])) : 'null';
	$dosyaId=isset($_POST["dosyaId"]) ? mysql_real_escape_string(strip_tags($_POST["dosyaId"])) : 'null'; 

	 if(mysql_query("update tblDosyalar set  dosyaYolu='$dosyaYolu' where dosyaId='$dosyaId'")) {
	 
			$sonuc="Dosya Bilgileri Güncellendi.";
		}
		else {
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
}

}
$dosyaBilgileri = mysql_fetch_row(mysql_query("select dosyaId,dosyaAd, dizinYol from tblDosyalar as dos, tblDizinler as diz  where dos.FKUygulamaId=diz.FKUygulamaId and dos.FKDizinId=diz.dizinId and dosyaId='$dosyaId'"));

//$uygulamaDizinBilgileri = mysql_query("select dosyaAd from tblDosyalar as d, tblUygulamalar as u, tblDizinler as f where u.uygulamaId=d.FKUygulamaId and f.FKUygulamaId=u.uygulamaId and uygulamaDurum=1 and dosyaId='$dosyaId'");





?> 

   
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Dosya Düzenle</h2>
        <p>Uygulamaya ait dosyalarý düzenleme iþlemini buradan yapabilirsiniz.</p>
      </div>
 
	  <form class="form-signin" role="form" name="frmDosyaDuzenle" method="post" action="dosyaDuzenle.php" enctype="multipart/form-data" >
	  
		 <div class="form-group">
                <label> Dosya </label>
					<a href = "dosyalar.php?dosyaId=<?php echo $dosyaBilgileri[0] ?>"> <?php echo $dosyaBilgileri[1]; ?> </a>
					<input name="dosya" type="file" id="dosya">
        </div>
 
        <button type="submit" class="btn btn-success btn-sm form-control" name="dosyaDuzenle" value="dosyaDuzenle">Güncelle</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
		<input type="hidden" name="dosyaId" value="<?php echo $dosyaBilgileri[0]; ?>" />
      </form>
    </div>
 
</body>
</html>