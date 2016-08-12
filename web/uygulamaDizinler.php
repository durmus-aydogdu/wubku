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
 
	<title>WUBKU - dizinler </title>
	 
</head>
<body>
<?php 

include 'vtBaglanti.php';
include 'oturumKontrol.php';
include 'ustMenuYazilimci.php';
include 'fonksiyonlar.php';
 

$dizinEkle=isset($_POST["dizinEkle"]) ? mysql_real_escape_string(strip_tags($_POST["dizinEkle"])) : 'null';
$uygulamaIdR=isset($_REQUEST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_REQUEST["uygulamaId"])) : 'null';
$sonuc="null";
 
if($dizinEkle != "null") { 

	$dizinYolu=isset($_POST["dizinYolu"]) ? mysql_real_escape_string(strip_tags($_POST["dizinYolu"])) : 'null'; 
	$uygulamaId=isset($_POST["uygulamaId"]) ? mysql_real_escape_string(strip_tags($_POST["uygulamaId"])) : 'null'; 
	 
	$dizinAdKontrol=substr($dizinYolu, -1); 
	
	if ( !($dizinAdKontrol == "/") )
			$dizinYolu = $dizinYolu."/";
	
	if(mysql_query("insert into tblDizinler (dizinYol,FKUygulamaId) values('$dizinYolu','$uygulamaId')")) {

			$sunucuKokDizin = mysql_fetch_row(mysql_query("select sunucuKokDizin  from tblAyarlar")) or die(mysql_error());
			$uygulamaId = mysql_fetch_row(mysql_query("select uygulamaId, uygulamaKokDizin,uygulamaAd from tblUygulamalar where uygulamaId='$uygulamaId' ")) or die(mysql_error());

			dizinOlustur( $sunucuKokDizin[0]."".$uygulamaId[1]."".$dizinYolu, $sunucuKokDizin[0]."".$uygulamaId[1], $uygulamaId[0]  );
			
			$mesaj=$kulId. " numaralý ".$kulAd." kullanýcýsý ". $uygulamaId[2]. " uygulamasýna ". $dizinYolu . " adlý dizin ekledi.";
 
			$sonuc=islemiKaydet ($mesaj,$uygulamaId[0]);
		
			if ($sonuc == 0 ) {
				$sonuc="Dizin Eklendi.";
			}
			else 
				$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
			
		}
		else {
			
			$sonuc="Ýþlem yapýlýrken hata oluþtu. Tekrar Deneyiniz.";
		}
 
}

?> 

  
    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>Uygulama Dizinleri</h2>
        <p>Uygulamalarýn dizinlerini görebilir ve düzenleme yapabilirsiniz.</p>
      </div>
	  	
		  <form class="form-signin" role="form" name="frmDizinGuncelle" method="post" action="uygulamaDizinler.php">
 
		 <div class="form-group">
                <label> Dizin Yolu</label>
				<input type="text" class="form-control" id="dizinYolu" name="dizinYolu" required autofocus>
        </div>
		 <button type="submit" class="btn btn-success btn-sm form-control" name="dizinEkle" value="dizinEkle">Kaydet</button>
		 
		<input type="hidden" name="uygulamaId" value="<?php echo $uygulamaIdR ?>" />
      </form>
	  
 
		<div class="col-md-4 col-md-offset-4">
		<table class="table table-striped table-hover">
					 
					
					<thead>
					<tr>
						<th> Dizin Bilgisi </th>
						<th> Ýþlem </th> 
					</tr>
				</thead>
				 <tbody>
						
		
<?php 
 
$dizinSorgu = mysql_query("select * from tblDizinler where FKUygulamaId='$uygulamaIdR'");

while ($dizinBilgileri = mysql_fetch_array($dizinSorgu)){
	$dizinYol=$dizinBilgileri["dizinYol"];
	$dizinId=$dizinBilgileri["dizinId"];
 	
  echo "
	 	<tr> 
			<td>$dizinYol</td>
			<td><a href=\"dizinSil.php?dizinId=$dizinId\"><button class=\"btn btn-danger\">Sil</button></a> <a href=\"dizinDuzenle.php?dizinId=$dizinId\"><button class=\"btn btn-info\">Düzenle</button></a></td>
		</tr>
		";
 }
	 
	?>	
		
		<?php 
			if ($sonuc != "null")
				echo "<div class=\"alert alert-warning\"> $sonuc </div>"; 
		?>
     </tbody>
	 </table>
 
	 </div>
    </div>
 
</body>
</html>