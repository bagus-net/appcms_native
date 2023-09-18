<?php
if(isset($_SESSION['SES_LOGIN'])){
# JIKA YANG LOGIN LEVEL ADMIN, menu di bawah yang dijalankan
?>
<ul>
	<li><a href="?open" target="_self">Home</a></li>
	<li><a href="?open=Master" target="_self">Data-Master</a></li>
<!--	<li><a href="?open=User-Data" target="_self">Data User</a></li>
	<li><a href='?open=Penerbit-Data' title='Penerbit' target="_self">Data Sales</a></li>
	<li><a href="?open=Kategori-Data" target="_self">Data Jenis </a></li>
	<li><a href="?open=Buku-Data" target="_self">Data Box</a></li>
	<li><a href="?open=Mcust-Data" target="_self">Data Customer </a></li>
	<li><a href="?open=Mahasiswa-Data" target="_self">Data Mahasiswa </a></li> -->
	<li><a href="peminjaman/" target="_blank">Tinjauan Order </a></li>
<!--	<li><a href="pengembalian/" target="_blank">Pengembalian Buku </a></li> -->
	<li><a href="?open=Laporan" target="_self">Laporan</a></li>
	<li> <a href="?open=Logout" target="_self"> Logout </a></li>
</ul>
<?php
}
else {
# JIKA BELUM LOGIN (BELUM ADA SESION LEVEL YG DIBACA)
?>
<ul>
	<li><a href='?open=Login' title='Login System'>Login</a></li>	
</ul>
<?php
}
?>