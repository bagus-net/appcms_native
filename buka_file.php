<?php
# KONTROL MENU PROGRAM
if(isset($_GET['open'])) {
	// Jika mendapatkan variabel URL ?open
	switch($_GET['open']){	

 			
		case '' :
			if(!file_exists ("info.php")) die ("File tidak ada !"); 
			include "info.php";	break;
			
		case 'Halaman-Utama' :
			if(!file_exists ("info.php")) die ("File tidak ada !"); 
			include "info.php";	break;
			
		case 'Login' :
			if(!file_exists ("login.php")) die ("File tidak ada !"); 
			include "login.php"; break;
			
		case 'Login-Validasi' :
			if(!file_exists ("login_validasi.php")) die ("File tidak ada !"); 
			include "login_validasi.php"; break;
			
		case 'Logout' :
			if(!file_exists ("login_out.php")) die ("File tidak ada !"); 
			include "login_out.php"; break;		

		# USER
		case 'User-Data' :
			if(!file_exists ("user_data.php")) die ("File tidak ada !"); 
			include "user_data.php";	 break;		
		case 'User-Add' :
			if(!file_exists ("user_add.php")) die ("File tidak ada !"); 
			include "user_add.php";	 break;		
		case 'User-Delete' :
			if(!file_exists ("user_delete.php")) die ("File tidak ada !"); 
			include "user_delete.php"; break;		
		case 'User-Edit' :
			if(!file_exists ("user_edit.php")) die ("File tidak ada !"); 
			include "user_edit.php"; break;	

		# PENERBIT
		case 'Penerbit-Data' :
			if(!file_exists ("penerbit_data.php")) die ("File tidak ada !"); 
			include "penerbit_data.php"; break;		
		case 'Penerbit-Add' :
			if(!file_exists ("penerbit_add.php")) die ("File tidak ada !"); 
			include "penerbit_add.php";	break;		
		case 'Penerbit-Delete' :
			if(!file_exists ("penerbit_delete.php")) die ("File tidak ada !"); 
			include "penerbit_delete.php"; break;		
		case 'Penerbit-Edit' :
			if(!file_exists ("penerbit_edit.php")) die ("File tidak ada !"); 
			include "penerbit_edit.php"; break;	

		# KATEGORI 
		case 'Kategori-Data' :
			if(!file_exists ("kategori_data.php")) die ("File tidak ada !"); 
			include "kategori_data.php"; break;		
		case 'Kategori-Add' :
			if(!file_exists ("kategori_add.php")) die ("File tidak ada !"); 
			include "kategori_add.php";	break;		
		case 'Kategori-Delete' :
			if(!file_exists ("kategori_delete.php")) die ("File tidak ada !"); 
			include "kategori_delete.php"; break;		
		case 'Kategori-Edit' :
			if(!file_exists ("kategori_edit.php")) die ("File tidak ada !"); 
			include "kategori_edit.php"; break;	

			
		# BUKU
		case 'Buku-Data' :
			if(!file_exists ("buku_data.php")) die ("File tidak ada !"); 
			include "buku_data.php"; break;		
		case 'Buku-Add' :
			if(!file_exists ("buku_add.php")) die ("File tidak ada !"); 
			include "buku_add.php"; break;		
		case 'Buku-Delete' :
			if(!file_exists ("buku_delete.php")) die ("File tidak ada !"); 
			include "buku_delete.php"; break;		
		case 'Buku-Edit' :
			if(!file_exists ("buku_edit.php")) die ("File tidak ada !"); 
			include "buku_edit.php"; break;
			
		case 'Pencarian-Buku' :
			if(!file_exists ("pencarian_buku.php")) die ("File tidak ada !"); 
			include "pencarian_buku.php"; break;		

		# BUKU
		case 'Type-Box' :
			if(!file_exists ("type-box.php")) die ("File tidak ada !"); 
			include "type-box.php"; break;		

		# master customer
		case 'Mcust-Data' :
			if(!file_exists ("mcust_data.php")) die ("File tidak ada !"); 
			include "mcust_data.php"; break;		
		case 'Mcust-Add' :
			if(!file_exists ("mcust_add.php")) die ("File tidak ada !"); 
			include "mcust_add.php"; break;
		case 'Mcust-Edit' :
			if(!file_exists ("mcust_edit.php")) die ("File tidak ada !"); 
			include "mcust_edit.php"; break;
		case 'Mcust-Delete' :
			if(!file_exists ("mcust_delete.php")) die ("File tidak ada !"); 
			include "mcust_delete.php"; break;

		# MAHASISWA
		case 'Mahasiswa-Data' :
			if(!file_exists ("mahasiswa_data.php")) die ("File tidak ada !"); 
			include "mahasiswa_data.php"; break;		
		case 'Mahasiswa-Add' :
			if(!file_exists ("mahasiswa_add.php")) die ("File tidak ada !"); 
			include "mahasiswa_add.php"; break;
		case 'Mahasiswa-Delete' :
			if(!file_exists ("mahasiswa_delete.php")) die ("File tidak ada !"); 
			include "mahasiswa_delete.php"; break;
		case 'Mahasiswa-Edit' :
			if(!file_exists ("mahasiswa_edit.php")) die ("File tidak ada !"); 
			include "mahasiswa_edit.php"; break;

		# menu master DATA
		case 'Master' :
				if(!file_exists ("menu_master.php")) die ("File tidak ada !"); 
				include "menu_master.php"; break;


		# REPORT INFORMASI / LAPORAN DATA
		case 'Laporan' :
				if(!file_exists ("menu_laporan.php")) die ("File tidak ada !"); 
				include "menu_laporan.php"; break;

			# LAPORAN MASTER DATA
			case 'Laporan-User' :
				if(!file_exists ("laporan_user.php")) die ("File tidak ada !"); 
				include "laporan_user.php"; break;
	
			case 'Laporan-Penerbit' :
				if(!file_exists ("laporan_penerbit.php")) die ("File tidak ada !"); 
				include "laporan_penerbit.php"; break;
				
			case 'Laporan-Kategori' :
				if(!file_exists ("laporan_kategori.php")) die ("File tidak ada !"); 
				include "laporan_kategori.php"; break;
				
			case 'Laporan-Buku-Penerbit' :	
				if(!file_exists ("laporan_buku_penerbit.php")) die ("File tidak ada !"); 
				include "laporan_buku_penerbit.php"; break;
				
			case 'Laporan-Buku-Kategori' :	
				if(!file_exists ("laporan_buku_kategori.php")) die ("File tidak ada !"); 
				include "laporan_buku_kategori.php"; break;
			
			case 'Laporan-Mahasiswa' :
				if(!file_exists ("laporan_mahasiswa.php")) die ("File tidak ada !"); 
				include "laporan_mahasiswa.php"; break;
			
			case 'Laporan-Pengadaan' :
				if(!file_exists ("laporan_pengadaan.php")) die ("File tidak ada !"); 
				include "laporan_pengadaan.php"; break;

			# LAPORAN PEMINJAMAN
			case 'Laporan-Peminjaman-Bulan' :
				if(!file_exists ("laporan_peminjaman_bulan.php")) die ("File tidak ada !"); 
				include "laporan_peminjaman_bulan.php"; break;
				
			case 'Laporan-Peminjaman-Tahun' :
				if(!file_exists ("laporan_peminjaman_tahun.php")) die ("File tidak ada !"); 
				include "laporan_peminjaman_tahun.php"; break;
				
			case 'Laporan-Peminjaman-Periode' :
				if(!file_exists ("laporan_peminjaman_periode.php")) die ("File tidak ada !"); 
				include "laporan_peminjaman_periode.php"; break;
				
			case 'Laporan-Peminjaman-Mahasiswa' :
				if(!file_exists ("laporan_peminjaman_mahasiswa.php")) die ("File tidak ada !"); 
				include "laporan_peminjaman_mahasiswa.php"; break;
					
		default:
			if(!file_exists ("info.php")) die ("File tidak ada !"); 
			include "info.php";						
		break;
	}
}
else {
	// Jika tidak mendapatkan variabel URL : ?page
	if(!file_exists ("info.php")) die ("File tidak ada !"); 
	include "info.php";	
}
?>