<?php
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
include_once "library/inc.library.php";

?>
<h2> LAPORAN DATA CUSTOMER </h2>
<br />

<table class="table-list" width="800" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td width="27" align="center" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="36" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="162" bgcolor="#CCCCCC"><strong>Nama</strong></td>
    <td width="311" bgcolor="#CCCCCC"><strong>Alamat</strong></td>
    <td width="90" bgcolor="#CCCCCC"><strong>No. Telepon </strong></td>
    <td width="62" bgcolor="#CCCCCC"><strong>Kontak</strong></td>
    <td width="76" bgcolor="#CCCCCC"><strong> Limit </strong></td>
  </tr>
	<?php
	// Skrip menampilkan data dari database
	$mySql 	= "SELECT * FROM mcust  ORDER BY kode";
	$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query salah 1 : ".pg_last_error());
	$nomor  = 0; 
	while ($myData = pg_fetch_array($myQry)) {
		$nomor++;
		$Kode	= $myData['kode'];
	?>
  <tr>
    <td align="center"><?php echo $nomor; ?></td>
    <td><?php echo $myData['kode']; ?></td>
    <td><?php echo $myData['nama']; ?></td>
    <td><?php echo $myData['alamat']; ?></td>
    <td><?php echo $myData['telp']; ?></td>
    <td><?php echo $myData['kontak']; ?></td>
    <td><div align='right'> <?php echo number_format($myData['balance']); ?></td>
  </tr>
  <?php } ?>
</table>
<a href="cetak/mahasiswa.php" target="_blank">Cetak</a>
