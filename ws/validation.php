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

	function getPattern() {
		$patterns = array(  );
		$patterns['tgl_lahir'] = array( 'regex' => 'date' );
		$patterns['tgl_lahir_ayah'] = array( 'regex' => 'date' );
		$patterns['tgl_lahir_ibu'] = array( 'regex' => 'date' );
		$patterns['tgl_lahir_wali'] = array( 'regex' => 'date' );
		$patterns['email'] = array( 'regex' => 'email' );
		$patterns['nisn'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 10, 'msg' => 'angka' );
		$patterns['nik'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 16, 'msg' => 'angka' );
		$patterns['jk'] = array( 'regex' => '/^(L|P)$/', 'msg' => 'L: Laki-laki, P: Perempuan' );
		$patterns['rt'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['rw'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['kode_pos'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'len' => 5, 'msg' => 'Harus angka dan panjang 5 digit' );
		$patterns['telepon_rumah'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'min' => 9, 'max' => 20, 'msg' => 'angka' );
		$patterns['telepon_seluler'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'min' => 10, 'max' => 20, 'msg' => 'angka' );
		$patterns['kewarganegaraan'] = array( 'regex' => '/(^[A-Z]*$)|^$/', 'len' => 2, 'msg' => 'Hanya huruf kapital dan panjang 2 digit' );
		$patterns['stat_pd'] = array( 'regex' => '/^(A|C|D|L|P|K|N|G|X)$/' );
		$patterns['tgl_masuk_sp'] = array( 'regex' => 'date' );
		$patterns['tgl_keluar'] = array( 'regex' => 'date' );
		$patterns['bln_awal_bimbingan'] = array( 'regex' => 'date' );
		$patterns['bln_akhir_bimbingan'] = array( 'regex' => 'date' );
		$patterns['tgl_sk_yudisium'] = array( 'regex' => 'date' );
		$patterns['nipd'] = array( 'max' => 18, 'msg' => 'angka' );
		$patterns['a_terima_kps'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_pernah_paud'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_pernah_tk'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_pindah_mhs_asing'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['jalur_skripsi'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['tgl_sk_cpns'] = array( 'regex' => 'date' );
		$patterns['tmt_sk_angkat'] = array( 'regex' => 'date' );
		$patterns['tmt_pns'] = array( 'regex' => 'date' );
		$patterns['nidn'] = array( 'max' => 10, 'msg' => 'angka' );
		$patterns['nip'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 18, 'msg' => 'angka' );
		$patterns['no_tel_rmh'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'min' => 9, 'max' => 20, 'msg' => 'angka' );
		$patterns['no_hp'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'min' => 10, 'max' => 20, 'msg' => 'angka' );
		$patterns['stat_kawin'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_braille'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_bhs_isyarat'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['npwp'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 15, 'msg' => 'angka' );
		$patterns['tgl_srt_tgs'] = array( 'regex' => 'date' );
		$patterns['tmt_srt_tgs'] = array( 'regex' => 'date' );
		$patterns['tgl_ptk_keluar'] = array( 'regex' => 'date' );
		$patterns['regptk_a_sp_homebase'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_1'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_2'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_3'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_4'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_5'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_6'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_7'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_8'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_9'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_10'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_11'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['regptk_a_aktif_bln_12'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['jns_mk'] = array( 'regex' => '/(^[A-Z]*$)|^$/', 'len' => 1, 'msg' => 'Hanya 1 huruf kapital' );
		$patterns['kel_mk'] = array( 'regex' => '/(^[A-Z]*$)|^$/', 'len' => 1, 'msg' => 'Hanya 1 huruf kapital' );
		$patterns['sks_mk'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['sks_tm'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['sks_prak'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['sks_prak_lap'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['sks_sim'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['a_sap'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_silabus'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_bahan_ajar'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['acara_prak'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['a_diktat'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['tgl_mulai_efektif'] = array( 'regex' => 'date' );
		$patterns['tgl_akhir_efektif'] = array( 'regex' => 'date' );
		$patterns['jml_sem_normal'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['jml_sks_lulus'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 3, 'msg' => 'angka' );
		$patterns['jml_sks_wajib'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 3, 'msg' => 'angka' );
		$patterns['jml_sks_pilihan'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 3, 'msg' => 'angka' );
		$patterns['tgl_mulai_koas'] = array( 'regex' => 'date' );
		$patterns['tgl_selesai_koas'] = array( 'regex' => 'date' );
		$patterns['a_selenggara_pditt'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 1, 'msg' => 'angka' );
		$patterns['kuota_pditt'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 4, 'msg' => 'angka' );
		$patterns['a_pengguna_pditt'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 1, 'msg' => 'angka' );
		$patterns['smt'] = array( 'regex' => '/^(1|2)$/', 'msg' => '1: Ganjil, 2: Genap' );
		$patterns['a_wajib'] = array( 'regex' => '/^(0|1)$/' );
		$patterns['nilai_angka'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['nilai_huruf'] = array( 'regex' => '/(^[A-Z]*$)|^$/', 'max' => 3, 'msg' => 'Input harus huruf kapital' );
		$patterns['nilai_indeks'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_subst_tot'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_tm_subst'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_prak_subst'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_prak_lap_subst'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_sim_subst'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['jml_tm_renc'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['jml_tm_real'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['bobot_nilai_min'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['bobot_nilai_maks'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['target_mhs_baru'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 6, 'msg' => 'angka' );
		$patterns['calon_ikut_seleksi'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 6, 'msg' => 'angka' );
		$patterns['calon_lulus_seleksi'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 6, 'msg' => 'angka' );
		$patterns['daftar_sbg_mhs'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 6, 'msg' => 'angka' );
		$patterns['pst_undur_diri'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 5, 'msg' => 'angka' );
		$patterns['tgl_awal_kul'] = array( 'regex' => 'date' );
		$patterns['tgl_akhir_kul'] = array( 'regex' => 'date' );
		$patterns['jml_mgu_kul'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 2, 'msg' => 'angka' );
		$patterns['metode_kul'] = array( 'len' => 1, 'msg' => 'Hanya 1 digit' );
		$patterns['metode_kul_eks'] = array( 'len' => 1, 'msg' => 'Input hanya 1 digit' );
		$patterns['ips'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_smt'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 3, 'msg' => 'angka' );
		$patterns['ipk'] = array( 'regex' => '/^[0-9]*(\.[0-9]+)?$/', 'msg' => 'Input harus bilangan desimal' );
		$patterns['sks_total'] = array( 'regex' => '/(^[0-9]*$)|^$/', 'max' => 3, 'msg' => 'angka' );
		$patterns['id_stat_mhs'] = array( 'len' => 1, 'msg' => 'Input hanya 1 digit' );
		return $patterns;
	}

	function isValidInput($ret, $data) {
		$patterns = getPattern(  );
		foreach ($data as $key => $value) {

			if (array_key_exists( $key, $patterns )) {
				$pattern = $patterns[$key];
				$pattern['key'] = $key;

				if (( $pattern['regex'] == 'date' && $value != '' )) {
					if (!isValidDate( $value )) {
						setErrorValidasi( $ret, $pattern, 'date' );
						return false;
					}

					continue;
				}


				if ($pattern['regex'] == 'email') {
					if (( !filter_var( $value, FILTER_VALIDATE_EMAIL ) && $value != '' )) {
						setErrorValidasi( $ret, $pattern, 'email' );
						return false;
						continue;
					}

					continue;
				}


				if (array_key_exists( 'regex', $pattern )) {
					$valid = preg_match( $pattern['regex'], $value );

					if (!$valid) {
						setErrorValidasi( $ret, $pattern );
						return false;
					}
				}


				if (array_key_exists( 'min', $pattern )) {
					if (( strlen( $value ) < $pattern['min'] && $value != '' )) {
						setErrorValidasi( $ret, $pattern, 'min' );
						return false;
					}
				}


				if (array_key_exists( 'max', $pattern )) {
					if ($pattern['max'] < strlen( $value )) {
						setErrorValidasi( $ret, $pattern, 'max' );
						return false;
					}
				}


				if (array_key_exists( 'len', $pattern )) {
					if (strlen( $value ) != $pattern['len']) {
						setErrorValidasi( $ret, $pattern, 'len' );
						return false;
					}

					continue;
				}

				continue;
			}
		}

		return true;
	}

	function setErrorValidasi($ret, $pattern, $type = 'regex') {
		if ($pattern['msg'] == 'angka') {
			$msg = 'Input harus angka';
		} 
else {
			$msg = $pattern['msg'];
		}


		if ($type == 'regex') {
			$ret['error_code'] = '999';
			$ret['error_desc'] = 'Format input \'' . $pattern['key'] . '\' salah. ' . $msg;
			return null;
		}


		if ($type == 'min') {
			$ret['error_code'] = '998';
			$ret['error_desc'] = 'Panjang input \'' . $pattern['key'] . '\' minimal ' . $pattern['min'] . ' digit';
			return null;
		}


		if ($type == 'max') {
			$ret['error_code'] = '997';
			$ret['error_desc'] = 'Panjang input \'' . $pattern['key'] . '\' maksimal ' . $pattern['max'] . ' digit';
			return null;
		}


		if ($type == 'len') {
			$ret['error_code'] = '996';
			$ret['error_desc'] = 'Panjang input \'' . $pattern['key'] . '\' harus ' . $pattern['len'] . ' digit';
			return null;
		}


		if ($type == 'date') {
			$ret['error_code'] = '995';
			$ret['error_desc'] = 'Format tanggal (' . $pattern['key'] . ') tidak valid. yyyy-mm-dd';
			return null;
		}


		if ($type == 'email') {
			$ret['error_code'] = '994';
			$ret['error_desc'] = 'Format email (' . $pattern['key'] . ') tidak valid.';
		}

	}

?>