#!/bin/bash

# Web Uygulamaları Bütünlük Kontrol Uygulaması (WUBKU),  web uygulama kaynak kodu üzerinden bütünlük kontrolü yaparak, uygulamanın bütünlüğünün korunmasını sağlamaktadır.

#  Copyright (C) <2015>  <Durmuş AYDOĞDU>

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

uygulamaSunucuKokDizin=$(mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "select sunucuKokDizin  from tblAyarlar")
chown -R www-data $uygulamaSunucuKokDizin
butunUygulamalarDosyaOzetDegeri=$(find $uygulamaSunucuKokDizin -type f  -print0 | xargs -0 sha512sum | sha512sum | cut -d' ' -f1);
butunUygulamalarDizinOzetDegeri=$(find $uygulamaSunucuKokDizin  -type d -print0 | sha512sum | cut -d' ' -f1);

toplamOzetDeger=$(echo $butunUygulamalarDosyaOzetDegeri $butunUygulamalarDizinOzetDegeri |sha512sum | cut -d' ' -f1);
mysql -u $vtKullaniciAdi -p$vtSifresi -D $vtAdi -s -N -e "update tblUygulamaBilgileri set uygulamaOzetDeger='$toplamOzetDeger'"

