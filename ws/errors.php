<?php
/**
*
--- PDDIKTIFeeder By Mukidi
--- PHP 5.3
--- Version : 1.0.0.0
--- Author     : Mukidi   
--- Release on : 16.10.2016
--- Website    : http://mukidisayang.blogspot.com  
*
**/

	$error_status = array(  );
	$error_status['0'] = '';
	$error_status['100'] = 'Invalid Token. Token tidak ada atau token sudah expired.';
	$error_status['101'] = 'Web Service dalam posisi Developer Mode. Jika ingin mengarahkan ke Live silakan diubah melalui Aplikasi Feeder';
	$error_status['102'] = 'Tabel tidak tersedia';
	$error_status['103'] = 'ERROR SQL ';
	$error_status['104'] = 'Web Service sudah expired. Silakan lakukan update aplikasi.';
	$error_status['105'] = 'Tidak ada data yang berubah. Tidak semua field boleh diubah (lihat di feeder)';
	$error_status['200'] = 'Mahasiswa dengan nama dan tanggal lahir ini sudah ada';
	$error_status['201'] = 'Nama tidak boleh kosong';
	$error_status['202'] = 'Tanggal lahir tidak boleh kosong atau format tanggal tidak sesuai (YYYY-MM-DD)';
	$error_status['203'] = 'Tidak ada data mahasiswa yang bisa diubah';
	$error_status['204'] = 'Data mahasiswa yang diubah lebih dari satu';
	$error_status['205'] = 'Tidak ada data mahasiswa yang bisa dihapus';
	$error_status['210'] = 'Mahasiswa dengan nama dan tanggal lahir ini tidak ada';
	$error_status['211'] = 'Mahasiswa ini sudah terdaftar';
	$error_status['212'] = 'id_pd tidak boleh kosong';
	$error_status['213'] = 'nipd (NIM/NRP) tidak boleh kosong';
	$error_status['214'] = 'Mahasiswa dengan id_reg_pd atau nipd ini tidak ada';
	$error_status['215'] = 'Mahasiswa tidak bisa dihapus karena sudah terdaftar di Program Studi';
	$error_status['300'] = 'Penambahan dosen tidak diizinkan';
	$error_status['301'] = 'Penghapusan dosen tidak diizinkan';
	$error_status['302'] = 'Mengubah data dosen tidak diizinkan';
	$error_status['303'] = 'Tidak ada data dosen yang bisa diubah';
	$error_status['304'] = 'Data dosen yang diubah lebih dari satu';
	$error_status['305'] = 'Tidak ada data dosen yang bisa dihapus';
	$error_status['310'] = 'Dosen dengan nama dan tanggal lahir ini tidak ada';
	$error_status['312'] = 'id_ptk tidak boleh kosong';
	$error_status['400'] = 'Mata kuliah dengan nama dan kode_mk ini sudah ada';
	$error_status['401'] = 'Nama Mata kuliah tidak boleh kosong';
	$error_status['402'] = 'Kode Mata kuliah tidak boleh kosong';
	$error_status['403'] = 'Tidak ada data mata kuliah yang bisa diubah';
	$error_status['404'] = 'Data mata kuliah yang diubah lebih dari satu';
	$error_status['405'] = 'Kode Mata Kuliah, Prodi dan Jenjang tidak boleh kosong';
	$error_status['406'] = 'Tidak ada data mata kuliah yang akan dihapus';
	$error_status['410'] = 'Mata kuliah dengan kode dan nama ini tidak ada';
	$error_status['500'] = 'Kurikulum dengan nama, id_sms dan id_jenj_didik ini sudah ada';
	$error_status['501'] = 'Nama kurikulum, id_sms dan id_jenj_didik tidak boleh kosong';
	$error_status['502'] = 'id_sms kurikulum tidak boleh kosong';
	$error_status['503'] = 'id_jenj_didik kurikulum tidak boleh kosong';
	$error_status['504'] = 'Tidak ada data kurikulum yang bisa diubah';
	$error_status['505'] = 'Data kurikulum yang diubah lebih dari satu';
	$error_status['506'] = 'Tidak ada kurikulum yang akan dihapus';
	$error_status['510'] = 'Kurikulum sp dengan nama, id_sms dan id_jenj_didik ini tidak ada';
	$error_status['600'] = 'Kurikulum dengan nama, id_sms dan id_smt_berlaku ini sudah ada';
	$error_status['601'] = 'Nama Mata kuliah tidak boleh kosong';
	$error_status['602'] = 'Kode Mata kuliah tidak boleh kosong';
	$error_status['603'] = 'Semeter matakuliah kurikulum tidak boleh kosong';
	$error_status['604'] = 'SKS matakuliah kurikulum tidak boleh kosong';
	$error_status['605'] = 'Wajib/Tidak matakuliah kurikulum tidak boleh kosong';
	$error_status['606'] = 'Nama dan Kode matakuliah ini tidak ada';
	$error_status['607'] = 'Tidak ada data mata kuliah kurikulum yang bisa diubah';
	$error_status['608'] = 'Mata kuliah kurikulum yang diubah lebih dari satu';
	$error_status['609'] = 'Data matakuliah kurikulum tidak ada';
	$error_status['610'] = 'Kurikulum dengan nama, id_sms dan id_smt_berlaku ini tidak ada';
	$error_status['630'] = 'Data mata kuliah kurikulum ini sudah ada';
	$error_status['631'] = 'Data mata kuliah kurikulum ini tidak ada';
	$error_status['632'] = 'Tidak ada data yang bisa diubah';
	$error_status['633'] = 'Data yang diubah lebih dari satu';
	$error_status['634'] = 'id_kurikulum_sp, dan id_mk tidak boleh kosong';
	$error_status['635'] = 'Edit tidak di izinkan melalui webservice';
	$error_status['636'] = 'Tidak ada data yang akan dihapus';
	$error_status['700'] = 'Data kelas ini sudah ada';
	$error_status['701'] = 'Nama kelas, id_mk, id_sms dan id_smt tidak boleh kosong';
	$error_status['702'] = 'Tidak ada data kelas kuliah yang bisa diubah';
	$error_status['703'] = 'Data kelas kuliah yang diubah lebih dari satu';
	$error_status['704'] = 'id_kls tidak boleh kosong';
	$error_status['705'] = 'Data kelas ini tidak ada';
	$error_status['706'] = 'Tidak ada data yang akan dihapus';
	$error_status['730'] = 'Data aktivitas perkuliahan ini sudah ada';
	$error_status['731'] = 'Data aktivitas perkuliahan ini tidak ada';
	$error_status['732'] = 'Tidak ada data yang bisa diubah';
	$error_status['733'] = 'Data yang diubah lebih dari satu';
	$error_status['734'] = 'id_smt, id_reg_pd, id_stat_mhs tidak boleh kosong';
	$error_status['735'] = 'Edit tidak di izinkan melalui webservice';
	$error_status['736'] = 'Tidak ada data yang akan dihapus';
	$error_status['800'] = 'Data nilai dari id_kls dan id_reg_pd ini sudah ada';
	$error_status['801'] = 'id_kls dan id_reg_pd tidak boleh kosong';
	$error_status['802'] = 'id_reg_pd tidak boleh kosong';
	$error_status['803'] = 'Tidak ada data nilai yang bisa diubah';
	$error_status['804'] = 'Data nilai yang akan diubah lebih dari satu';
	$error_status['805'] = 'Delete nilai tidak diizinkan';
	$error_status['900'] = 'Data substansi dari nama substansi dan id_sms ini sudah ada';
	$error_status['901'] = 'Nama substansi tidak boleh kosong';
	$error_status['902'] = 'id_sms tidak boleh kosong';
	$error_status['903'] = 'Tidak ada data substansi yang bisa diubah';
	$error_status['904'] = 'Data substansi yang diubah lebih dari satu';
	$error_status['906'] = 'Tidak ada data substansi yang akan dihapus';
	$error_status['910'] = 'Data substansi ini tidak ada';
	$error_status['920'] = 'Dosen mengajar dari id_reg_ptk dan id_kls ini sudah ada';
	$error_status['921'] = 'id_reg_ptk tidak boleh kosong';
	$error_status['922'] = 'id_kls tidak boleh kosong';
	$error_status['923'] = 'Data dosen yang diubah lebih dari satu';
	$error_status['930'] = 'Data dosen mengajar ini tidak ada';
	$error_status['940'] = 'Skala nilai dari id_sms dan nilai huruf ini sudah ada';
	$error_status['941'] = 'id_sms tidak boleh kosong';
	$error_status['942'] = 'Nilai huruf tidak boleh kosong';
	$error_status['943'] = 'Data skala nilai yang diubah lebih dari satu';
	$error_status['950'] = 'Data skala nilai ini tidak ada';
	$error_status['960'] = 'Kapasitas mahasiswa dari id_sms dan id_smt ini sudah ada';
	$error_status['961'] = 'id_sms tidak boleh kosong';
	$error_status['962'] = 'id_smt tidak boleh kosong';
?>