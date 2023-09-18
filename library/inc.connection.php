<?php
# Konek ke Web Server Lokal
$myHost	= "mgm-server";
$myUser	= "saya";
$myPass	= "akujaya";
$myDbs	= "cms_mgm"; // nama database  


# Konek ke Web Server Lokal
// $koneksidb	= mysql_connect($myHost, $myUser, $myPass);

//$koneksidb = mysqli_connect($myHost,$myUser,$myPass,$myDbs) or  die (mysqli_connect_error());

//$koneksidb = pg_connect($myHost,$myUser,$myPass,$myDbs);  // or  die (mysqli_connect_error());

// $koneksidb == pg_connect(�host='localhost' user='saya' password='akujaya' dbname='wsb_cms'�);

$koneksidb = pg_connect("host=mgm-server port=5432 dbname=cms_mgm user=saya password=akujaya");

if  (! $koneksidb) {
  echo "Koneksi MySQL gagal, periksa Host/ User/ Password-nya  !";
}

# Memilih database pd MySQL Server
//  mysql_select_db($myDbs) or die ("Database <b>$myDbs</b> tidak ditemukan !");
?>