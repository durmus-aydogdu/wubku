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

	<title>WUBKU - uygulamalar</title>
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';

if ( $kulRol == 1 )
	include 'ustMenuYonetici.php';
else if ( $kulRol == 2 )
	include 'ustMenuYazilimci.php';
else 
	include 'ustMenuIzleyici.php';

$uygulamaEkle=isset($_REQUEST["uygulamaEkle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaEkle"])) : 'null';
$sonuc=isset($_REQUEST["sonuc"]) ? mysql_real_escape_string(strip_tags($_REQUEST["sonuc"])) : 'null';
 
if($uygulamaEkle != "null") { 

	$uygulamaAdi=isset($_POST["uygulamaAdi"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaAdi"])) : 'null';
	$uygulamaKokDizin=isset($_POST["uygulamaKokDizin"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaKokDizin"])) : 'null';
	$tarih = date('Y-m-d H:i:s');
	 if(mysql_query("insert into tblUygulamalar (uygulamaAd,uygulamaDurum,eklenmeTarihi,uygulamaOzetDeger,uygulamaKokDizin) values('$uygulamaAdi',1,'$tarih','null','$uygulamaKokDizin')")) {

			$sonuc="Uygualama Eklendi.";
		}
		else {
			
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
 
}
?> 
 

    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulamalar</h2>
        <p>Uygulamalarý görebilir ve düzenleme yapabilirsiniz.</p>
      </div>
	  	
		<table class="table table-striped table-hover">
					 
					 <tbody>
					<thead>
					<tr>
						<th>Adý</th>
						<th>Kök Dizini</th>
						<th>Eklenme Tarihi</th>
						<th>Durumu</th>
						<th>Ýþlem</th>
					</tr>
				</thead>
						
		
<?php 

if ( $kulRol == 1 ) {
$uygulamalarSorgu = mysql_query("select * from tblUygulamalar");

while ($uygulamaBilgileri = mysql_fetch_array($uygulamalarSorgu)){
	$uygulamaId=$uygulamaBilgileri["uygulamaId"];
	$uygulamaAd=$uygulamaBilgileri["uygulamaAd"];
	$uygulamaDurum=$uygulamaBilgileri["uygulamaDurum"];
	$eklenmeTarihi=$uygulamaBilgileri["eklenmeTarihi"];
	$uygulamaKokDizin=$uygulamaBilgileri["uygulamaKokDizin"];
 
	if ($uygulamaDurum == 1)
		$uygulamaDurum="Aktif";
	else
		$uygulamaDurum="Pasif";
		
  echo "
	 	<tr> 
			<td>$uygulamaAd</td> 
			<td>$uygulamaKokDizin</td>
			<td>$eklenmeTarihi</td>
			<td>$uygulamaDurum</td>
			<td><a href=\"uygulamaSil.php?uygulamaId=$uygulamaId\"> <button class=\"btn btn-danger\">Sil</button></a> <a href=\"uygulamaDuzenle.php?uygulamaId=$uygulamaId\"><button class=\"btn btn-info\">Bilgiler</button></a></td>
		</tr>
		";
 }
}
else  {

$uygulamalarSorgu = mysql_query(" select * from tblYetkiler, tblUygulamalar where FKUygulamaId = uygulamaId and FKKullaniciId='$kulId'");

while ($uygulamaBilgileri = mysql_fetch_array($uygulamalarSorgu)){
	$uygulamaId=$uygulamaBilgileri["uygulamaId"];
	$uygulamaAd=$uygulamaBilgileri["uygulamaAd"];
	$uygulamaDurum=$uygulamaBilgileri["uygulamaDurum"];
	$eklenmeTarihi=$uygulamaBilgileri["eklenmeTarihi"];
	$uygulamaKokDizin=$uygulamaBilgileri["uygulamaKokDizin"];
 
	if ($uygulamaDurum == 1)
		$uygulamaDurum="Aktif";
	else
		$uygulamaDurum="Pasif";
		
  echo "
	 	<tr> 
			<td>$uygulamaAd</td> 
			<td>$uygulamaKokDizin</td>
			<td>$eklenmeTarihi</td>
			<td>$uygulamaDurum</td>
			<td><a href=\"dosyaYukle.php?uygulamaId=$uygulamaId\"><button class=\"btn btn-info\">Güncelle</button></a> <a href=\"uygulamaDizinler.php?uygulamaId=$uygulamaId\"><button class=\"btn btn-primary\">Dizinler</button></a></td>
		</tr>
		";
 }
 
}
	 
	?>	
		
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
     
    </div>
 
</body>
</html>