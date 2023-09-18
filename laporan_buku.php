<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";
?>
<h2> LAPORAN DATA BUKU </h2>
<table class="table-list" width="994" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="35" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="97" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="218" bgcolor="#CCCCCC"><strong>Judul Buku</strong></td>
    <td width="194" bgcolor="#CCCCCC"><strong>Penulis</strong></td>
    <td width="169" bgcolor="#CCCCCC"><strong>Penerbit </strong></td>
    <td width="133" bgcolor="#CCCCCC"><strong>Lokasi Rak </strong></td>
    <td width="112" bgcolor="#CCCCCC"><strong>Jenis</strong></td>
  </tr>
	<?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT buku.*, penerbit.nm_penerbit, jenis.nm_jenis FROM buku  
				LEFT JOIN penerbit ON buku.kd_penerbit=penerbit.kd_penerbit 
				LEFT JOIN jenis ON buku.kd_jenis=jenis.kd_jenis 
				ORDER BY buku.kd_buku ASC";
	$myQry 	= mysql_query($mySql, $koneksidb)  or die ("Query salah 1 : ".mysql_error());
	$nomor  = 0; 
	while ($myData = mysql_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kd_buku'];
		
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['kd_buku']; ?></td>
    <td><?php echo $myData['judul_buku']; ?></td>
    <td><?php echo $myData['penulis']; ?></td>
    <td><?php echo $myData['nm_penerbit']; ?></td>
    <td><?php echo $myData['lokasi_rak']; ?></td>
    <td><?php echo $myData['nm_jenis']; ?></td>
  </tr>
  <?php } ?>
</table>
<a href="cetak/buku.php" target="_blank"><img src="images/btn_print2.png" height="18" border="0" title="Cetak ke Format HTML"/></a>