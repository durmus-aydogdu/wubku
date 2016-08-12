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
	<title>WUBKU - kayýt takip </title>
	 
</head>
<body>
 
<?php 
include 'vtBaglanti.php';
include 'ustMenuYonetici.php';
include 'oturumKontrol.php';
 
?>


    <div class="container">

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h2>WUBKU Kayýt Takip</h2>
        <p>Sistemin tespit ettiði saldýrýlarý ve yapýlan iþlemleri buradan takip edebilirsiniz.</p>
      </div>
	  	
	  <div class="row">
	  <div class="col-sm-9">
	  <center> <a href="#saldirilar" data-toggle="tab" class="btn btn-default">Saldýrýlar</a> 
       <a href="#islemler" data-toggle="tab" class="btn btn-default">Ýþlemler</a> </center>
		</div>		
		</div>		
	  <div id="myTabContent" class="tab-content">
		
		<div class="tab-pane fade in" id="saldirilar">
		
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Tarih</th>
						<th>Saldýrý&nbsp;Mesaj</th>
						<th>Eposta&nbsp;Gönderildi&nbsp;Mi</th>
					</tr>
				</thead>
				<tbody>	
		
				<?php 
 
					$saldirilarSorgu = mysql_query("select * from tblSaldirilar order by saldiriTarih desc");
					$saldiriSayisi= mysql_num_rows($saldirilarSorgu);
					echo "<center><h1> Toplam Saldýrý Sayýsý :  $saldiriSayisi</h1></center>";
					while ($saldiriBilgileri = mysql_fetch_array($saldirilarSorgu)){
						$saldiriTarih=$saldiriBilgileri["saldiriTarih"];
						$saldiriMesaj=$saldiriBilgileri["saldiriMesaj"];
						$epostaGonderildiMi=$saldiriBilgileri["epostaGonderildiMi"];

						if ( $epostaGonderildiMi == 1)
								$epostaGonderildiMi="Evet";
						else
								$epostaGonderildiMi="Hayýr";
						echo "
						<tr> 
							<td>$saldiriTarih</td> 
							<td>$saldiriMesaj</td>
							<td>$epostaGonderildiMi</td>
						</tr>
						";
					}
				?>
				</tbody>
				</table>
           </div>
		   
		  <div class="tab-pane fade in" id="islemler">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Tarih</th>
						<th>Ýþlem</th>
					</tr>
				</thead>
				<tbody>	
		
				<?php 
 
					$islemSorgu = mysql_query("select * from tblIslemler order by islemTarih desc");
					$islemSayisi= mysql_num_rows($islemSorgu);
					echo "<center><h1> Toplam Ýþlem Sayýsý :  $islemSayisi</h1></center>";
					while ($islemBilgileri = mysql_fetch_array($islemSorgu)){
						$islemTarih=$islemBilgileri["islemTarih"];
						$islemMesaj=$islemBilgileri["islemMesaj"];

						echo "
						<tr> 
							<td>$islemTarih</td> 
							<td>$islemMesaj</td>
						</tr>
						";
					}
				?>
				</tbody>
				</table>
				
           </div>
		</div>		   
    </div>
 
</body>
</html>