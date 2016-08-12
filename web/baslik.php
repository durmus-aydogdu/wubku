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

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta name="content-type" content="text/html charset=utf-8" />
	<meta name="content-type" content="text/html charset=windows1254" />
	<meta http-equiv="Content-Type" content="text/html; lang=tr" />

	<meta name="content-language" content="tr-TR" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	 
	<link href="css/bootstrap.css" rel="stylesheet">	
	<link href="css/signin.css" rel="stylesheet">
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	
	<?php
		$calismaDurumu=shell_exec('ps -C wubku.sh | wc -l');
		if ( $calismaDurumu > 1)
		 $calismaDurumu = "<a class=\"alert alert-success small\"> WUBKU Çalýþýyor  </a>"; 
			 
		else
			$calismaDurumu = "<a class=\"alert alert-danger small\"> WUBKU Çalýþmýyor  </a>";
	?>