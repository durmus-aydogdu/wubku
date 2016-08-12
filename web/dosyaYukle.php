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
	<title>WUBKU - uygulama güncelle </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php'; 
include 'fonksiyonlar.php'; 
include 'ustMenuYazilimci.php'; 

 
$uygulamaId=isset($_REQUEST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaId"])) : 'null';
$dosyaEkle=isset($_REQUEST["dosyaEkle"]) ? mysql_real_escape_string(strip_tags($_REQUEST["dosyaEkle"])) : 'null';
$sonuc="null";

 

if( $dosyaEkle != 'null') {

  foreach ($_FILES['dosya']['name'] as $d => $name) {     
 
	$dosyaAdi = $_FILES['dosya']['name'][$d];
	$dosyaGeciciAd  = $_FILES['dosya']['tmp_name'][$d];
	$dosyaYolId=isset($_POST["uygulamaDizinler"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaDizinler"])) : 'null'; 
	$uygulamaId=isset($_POST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaId"])) : 'null';
 
	$sunucuKokDizin =  mysql_fetch_row(mysql_query("select sunucuKokDizin from tblAyarlar")) or die(mysql_error());
	$uygulamaKokDizin =  mysql_fetch_row(mysql_query("select uygulamaKokDizin, uygulamaAd from tblUygulamalar where uygulamaId='$uygulamaId'")) or die(mysql_error());
 
	$dosyaOzetDegeri=shell_exec('sha512sum '.$dosyaGeciciAd.' | cut -d\' \' -f1');
	$dosya= addslashes(fread(fopen($dosyaGeciciAd, "rb"),filesize($dosyaGeciciAd)));
	$dizinYolu = mysql_fetch_row(mysql_query("select dizinYol  from tblDizinler where dizinId='$dosyaYolId' and FKUygulamaId='$uygulamaId'")) or die(mysql_error());
	
	$dizinAdi = $dizinYolu[0];
	if ( $dizinYolu[0] == "/"  )
		$dizinYolu[0]="";
	
		
	$dosyaYolu=	$sunucuKokDizin[0]."".$uygulamaKokDizin[0]."".$dizinYolu[0];
	
	if (mysql_query("INSERT INTO tblDosyalar (dosyaAd,FKDizinId,dosyaOzetDeger,dosyaDurum,dosya,FKKullaniciId,FKUygulamaId) values('$dosyaAdi', '$dosyaYolId', '$dosyaOzetDegeri',1,'$dosya', '$kulId','$uygulamaId')")) { 

		if (move_uploaded_file($dosyaGeciciAd,$dosyaYolu."".$dosyaAdi)) {
			
			uygulamaninOzetDegeriniHesapla( $dosyaYolu, $uygulamaId );
			
			$mesaj=$kulId. " numaralý ".$kulAd." kullanýcýsý ". $uygulamaKokDizin[1]. " uygulamasýnýn  ". $dizinAdi . " adlý dizinine ". $dosyaAdi." dosyasýný ekledi.";
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId);
			
			
			if ($sonuc == 0 ) {
				$sonuc = "Dosya yüklendi.";
			}
			else 
				$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
		else
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
	}
	else{
		$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";

	}
 }
}
$uygulamaDizinleri = mysql_query("select * from tblDizinler where FKUygulamaId='$uygulamaId'");


?> 
 
     <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulama Güncelleme</h2>
        <p>Uygulamaya ait dosyalarý görebilir ve dosyalarý güncelleþtirebilirsiniz.</p>
      </div>
 
   <form class="form-signin" role="form" name="frmDosyaYukle" method="post" action="dosyaYukle.php" enctype="multipart/form-data" >
 
		 <div class="form-group">
                <label> Dosya Yolu </label>
				 <select id="uygulamaDizinler" name="uygulamaDizinler"  required  class="form-control">
					 <option selected="selected"> Dizin Seçiniz </option>
									<?php
										while ($dizinler = mysql_fetch_array($uygulamaDizinleri)) {
											$dizinYol = $dizinler['dizinYol'];
											$dizinId = $dizinler['dizinId'];	 
									 
											echo "<option value=\"$dizinId\">$dizinYol</option>";
										 
										}
										?>
									</select>

        </div>
		 <div class="form-group">
                <label> Dosya </label>
					<input name="dosya[]" type="file" id="dosya" multiple="multiple">
        </div>
 
        <button type="submit" class="btn btn-success btn-sm form-control" name="dosyaEkle" value="dosyaEkle">Kaydet</button>
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
		<input type="hidden" name="uygulamaId" value="<?php echo $uygulamaId ?>" />
      </form>
	  
	  
		<table class="table table-striped table-hover">
					 
					 <tbody>
					<thead>
					<tr>
						<th>Dosya Adý </th>
						<th>Dosya Yolu </th>
						<th>Ýþlem</th>
					</tr>
				</thead>
				</tbody>
				<?php 

$uygulamaDosyaBilgileriSorgu = mysql_query("select dosyaId,dizinYol,dosyaAd  from tblDosyalar as dos, tblDizinler as diz  where dos.FKUygulamaId=diz.FKUygulamaId and dos.FKDizinId=diz.dizinId and dos.FKUygulamaId='$uygulamaId'");
 
	while ($uygulamaDosyaBilgileri = mysql_fetch_array($uygulamaDosyaBilgileriSorgu)){

		$dosyaId=$uygulamaDosyaBilgileri["dosyaId"];
		$dosyaAd=$uygulamaDosyaBilgileri["dosyaAd"];
		$dosyaYolu=$uygulamaDosyaBilgileri["dizinYol"];

		echo "<tr> 
			<td>$dosyaAd</td> 
			<td>$dosyaYolu</td>
			<td><a href=\"dosyaSil.php?dosyaId=$dosyaId\" class=\"btn btn-alert\"> <button class=\"btn btn-danger\">Sil</button> </a> <a href=\"dosyaDuzenle.php?dosyaId=$dosyaId\"> <button class=\"btn btn-info\"> Düzenle </button> </a>  </td>
		</tr>";
	}

?>
</tbody>
</table>



    </div>
	  
		 



 
 
</body>
</html>
 
