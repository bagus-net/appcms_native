<?php 
include_once "library/inc.connection.php";

# Menemukan tombol Login diklik
if(isset($_POST['btnLogin'])){
	# Baca variabel form
	$txtUser 		= $_POST['txtUser'];
	$txtUser 		= str_replace("'","&acute;",$txtUser);
	
	$txtPassword	= $_POST['txtPassword'];
	$txtPassword	= str_replace("'","&acute;",$txtPassword);
	
	// Skrip validasi form
	$pesanError = array();
	if (trim($txtUser)=="") {
		$pesanError[] = "Data <b> Username </b>  tidak boleh kosong, silahkan dilengkapi !";		
	}
	if (trim($txtPassword)=="") {
		$pesanError[] = "Data <b> Password </b> tidak boleh kosong, silahkan dilengkapi !";		
	}
	
	# JIKA ADA PESAN ERROR DARI VALIDASI
	if (count($pesanError)>=1 ){
		echo "<div class='mssgBox'>";
		echo "<img src='images/attention.png'> <br><hr>";
			$noPesan=0;
			foreach ($pesanError as $indeks=>$pesan_tampil) { 
			$noPesan++;
				echo "&nbsp;&nbsp; $noPesan. $pesan_tampil<br>";	
			} 
		echo "</div> <br>"; 
		
		// Tampilkan lagi form login
		include "login.php";
	}
	else {
		# CEK LOGIN KE DATABASE
//		$mySql = "SELECT * FROM users WHERE username='$txtUser' AND password='".md5($txtPassword)."' ";

//	    $mySql = "select * from users where nama='$_POST[txtUser]' and pass='$_POST[txtPassword]'";

		$mySql = "SELECT * FROM users";		
//		$myQry = pg_query($koneksidb,$mySql);   // or die ("Query Salah : ".pg_error()); 
		$myQry = pg_query($koneksidb, $mySql) or die('Query failed: ' . pg_last_error());		
//   	    $mydata = mysqli_query($koneksi,$sql);
		$myData= pg_fetch_array($myQry); 
		
		
		# JIKA LOGIN SUKSES
		if(pg_num_rows($myQry) >=1) {
			$_SESSION['SES_LOGIN'] 	= $myData['nama']; 
						
			// Refresh
			echo "<meta http-equiv='refresh' content='0; url=?open=Halaman-Utama'>";
		}
		else {
			 echo "Login Anda tidak diterima, Username dan Password salah !";
		}
	}
} 
else {
	// Refresh
	echo "<meta http-equiv='refresh' content='0; url=?open=Login'>";
} 
?>
 
