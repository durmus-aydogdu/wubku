#!/bin/bash


# Web Uygulamalarý Bütünlük Kontrol Uygulamasý (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanýn bütünlüðünün korunmasýný saðlamaktadýr.

#  Copyright (C) <2015>  <Durmuþ AYDOÐDU>

#   This program is free software: you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation, either version 3 of the License, or
#   any later version.

#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.

#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>

vtSifresi=$(cat $1  | grep vtSifresi | cut -d'=' -f2-);
vtAdi=$(cat $1  | grep vtAdi | cut -d'=' -f2-);
vtKullaniciAdi=$(cat $1  | grep vtKullaniciAdi | cut -d'=' -f2-);

uygulamaDizinOzetDeger=$(find $2 -type d -print0 | sha512sum | cut -d' ' -f1); 
uygulamaDosyaOzetDeger=$(find $2 -type f  -print0 | xargs -0 sha512sum | sha512sum | cut -d' ' -f1);

mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "update tblUygulamalar  set uygulamaDizinOzetDeger='$uygulamaDizinOzetDeger' where uygulamaId='$3'";
mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "update tblUygulamalar  set uygulamaDosyaOzetDeger='$uygulamaDosyaOzetDeger' where uygulamaId='$3'";
