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

    <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="yonetici.php">WUBKU</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
		  
		   <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Uygulamalar <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="uygulamalar.php">Uygulamalara Gözat</a></li>
                <li><a href="uygulamaEkle.php">Uygulama Ekle</a></li>
              </ul>
            </li>
			
			<li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Kullanýcýlar <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="kullanicilar.php">Kullanýcýlara Gözat</a></li>
                <li><a href="kullaniciEkle.php">Kullanýcý Ekle</a></li>
              </ul>
            </li>
 
			<li><a href="saldiriDurumu.php">Saldýrý Durumu</a></li>
			<li><a href="profil.php">Profil</a></li>
			<li><a href="ayarlar.php">Ayarlar</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
			<li> <?php echo $calismaDurumu ?></li>
            <li><a href="giris.php?islem=cikis">Çýkýþ</a></li>
			
          </ul>
        </div>
      </div>
    </div>