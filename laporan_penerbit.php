<?php
// Periksa session Login dan Koneksi
include_once "library/inc.seslogin.php";
include_once "library/inc.connection.php";
?>
<h2> LAPORAN DATA SALES</h2>
<table class="table-list" width="600" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td width="20" bgcolor="#CCCCCC"><strong>No</strong></td>
    <td width="45" bgcolor="#CCCCCC"><strong>Kode</strong></td>
    <td width="513" bgcolor="#CCCCCC"><strong>Nama Sales </strong></td>
  </tr>
  
<?php
// Skrip menampilkan data Penerbit
$mySql 	= "SELECT * FROM msales ORDER BY kode ASC";
$myQry 	= pg_query($koneksidb, $mySql)  or die ("Query  salah : ".pg_last_error());
$nomor  = 0; 
while ($myData = pg_fetch_array($myQry)) {
	$nomor++;
?>

  <tr>
	<td> <?php echo $nomor; ?> </td>
	<td> <?php echo $myData['kode']; ?> </td>
	<td> <?php echo $myData['nama']; ?> </td>
  </tr>
  
<?php } ?>

</table>
<br />
<a href="cetak/penerbit.php" target="_blank">Cetak</a>