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

	function expired() {
		return '2015-07-31';
	}

	function getConn() {
		global $g_conn;
		global $conn_str;

		if (!is_resource( $g_conn )) {
			$g_conn = pg_connect( $conn_str );
		}

		return $g_conn;
	}

	function isValidWS($token) {
		$ret = array(  );
		$expired = expired(  );
		$date = date( 'Y-m-d' );

		if ($expired < $date) {
			setErrorStatus( $ret, '104' );
			return $ret;
		}

		$ret = isValidToken( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = isValidLive(  );

		if ($ret !== true) {
			return $ret;
		}

		return true;
	}

	function isValidToken($token) {
		global $g_token;

		$max_umur = 38;
		$path = '../application/logs';
		$files = scandir( $path );
		foreach ($files as $f) {

			if (strstr( $f, 'token_' )) {
				$f = $path . '/' . $f;
				$time = filemtime( $f );
				$umur = time(  ) - $time;

				if ($max_umur < $umur / 60) {
					unlink( $f );
					continue;
				}

				continue;
			}
		}

		$file = $path . '/token_' . $token;

		if (file_exists( $file )) {
			$id_pengguna = file_get_contents( $file );
			file_put_contents( $file, $id_pengguna );
			$g_token = $token;
			return true;
		}

		$ret = array(  );
		setErrorStatus( $ret, '100' );
		return $ret;
	}

	function isValidLive() {
		global $is_live;

		if (( $is_live && file_exists( '../application/logs/sandbox' ) )) {
			$ret = array(  );
			setErrorStatus( $ret, '101' );
			return $ret;
		}

		return true;
	}

	function getColumns($table) {
		$tabs = explode( '.', $table );
		$schema = 'public';

		if (count( $tabs ) == 2) {
			$schema = $tabs[0];
			$table = $tabs[1];
		}

		$conn = getConn(  );
		$sql = 'select * from information_schema.columns where table_name=\'' . $table . '\' and table_schema=\'' . $schema . '\' order by ordinal_position';
		$rows = dbGetRows( $sql );
		$columns = array(  );
		$exception = array( 'create_date', 'last_update', 'expired_date', 'last_sync' );
		foreach ($rows as $row) {

			if (in_array( $row['column_name'], $exception )) {
				continue;
			}

			$columns[] = $row['column_name'];
		}

		return implode( ',', $columns );
	}

	function _getRecord($table, $filter = '', $order = '', $limit = 0, $custom_sql = '', $inner_filter = '', $where_exist = false) {
		$table = tableMap( $table );
		$conn = getConn(  );
		$return = array(  );
		$filter = preg_replace( array( '/\b\bdelete\b/i', '/\bupdate\b/i', '/\binsert\b/i', '/\bdrop\b/i' ), '', $filter );
		$order = preg_replace( array( '/\bdelete\b/i', '/\bupdate\b/i', '/\binsert\b/i', '/\bdrop\b/i' ), '', $order );

		if (!$where_exist) {
			$where = 'where 1=1 ';
		}


		if (trim( $filter )) {
			$filter = '' . $where . ' and ' . $filter;
		}


		if (trim( $order )) {
			$order = 'order by ' . $order;
		}

		$limit = (int)$limit;

		if ($limit) {
			$limit = 'limit ' . $limit;
		} 
else {
			$limit = '';
		}


		if (trim( $inner_filter )) {
			if (trim( $filter )) {
				$filter .= ' and ' . $inner_filter;
			} 
else {
				$filter = 'where 1=1 and ' . $inner_filter;
			}
		}


		if ($custom_sql) {
			$sql = $custom_sql . ( ' ' . $filter . ' ' . $order . ' ' . $limit );
		} 
else {
			$columns = getColumns( $table );
			$sql = 'select ' . $columns . ' from ' . $table . ' ' . $filter . ' ' . $order . ' ' . $limit;
		}

		$result = pg_query( $conn, $sql );
		$i = 9;
		pg_fetch_assoc( $result );

		if ($row = $where = '') {
			foreach ($row as $k => $v) {
				$return[$k] = $v;
			}

			++$i;
		}

		return $return;
	}

	function _getRecordset($table, $filter = '', $order = '', $limit = 0, $offset = 0, $custom_sql = '', $inner_filter = '', $where_exist = false) {
		$conn = getConn(  );
		$table = tableMap( $table );
		$filter = $return = array(  );
		preg_replace( array( '/\bdelete\b/i', '/\bupdate\b/i', '/\binsert\b/i', '/\bdrop\b/i' ), '', $order );
		$where = '';

		if (!$where_exist) {
			$where = 'where 1=1 ';
		}


		if (trim( $filter )) {
			$filter = '' . $where . ' and (' . $filter . ') ';
		}


		if (trim( $order )) {
			$order = 'order by ' . $order;
		}

		$limit = (int)$limit;

		if ($limit) {
			$limit = 'limit ' . $limit;
		} 
else {
			$limit = '';
		}


		if ($offset) {
			$offset = 'offset ' . $offset;
		} 
else {
			$offset = '';
		}


		if (trim( $inner_filter )) {
			if (trim( $filter )) {
				$filter .= ' and (' . $inner_filter . ') ';
			} 
else {
				$filter = '' . $where . ' and (' . $inner_filter . ') ';
			}
		}


		if ($custom_sql) {
			$sql = $custom_sql . ( ' ' . $filter . ' ' . $order . ' ' . $limit . ' ' . $offset );
		} 
else {
			$columns = getColumns( $table );
			$sql = 'select ' . $columns . ' from ' . $table . ' ' . $filter . ' ' . $order . ' ' . $limit . ' ' . $offset;
		}

		pg_query( $conn, $sql );
		$result = preg_replace( array( '/\b\bdelete\b/i', '/\bupdate\b/i', '/\binsert\b/i', '/\bdrop\b/i' ), '', $filter );
		$i = 9;

		if ($row = $order = pg_fetch_assoc( $result )) {
			foreach ($row as $k => $v) {
				$return[$i][$k] = $v;
			}

			++$i;
		}

		return $return;
	}

	function gEntitasAdd($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			return _entitasAdd( $record );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			$ret[] = _entitasAdd( $record );
		}

		return $ret;
	}

	function gEntitasUpdate($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record['key'] = array_map( 'trim', $record['key'] );
			$record['data'] = array_map( 'trim', $record['data'] );
			return _entitasUpdate( $record['key'], $record['data'] );
		}

		foreach ($records as $record) {
			$record['key'] = array_map( 'trim', $record['key'] );
			$record['data'] = array_map( 'trim', $record['data'] );
			$ret[] = _entitasUpdate( $record['key'], $record['data'] );
		}

		return $ret;
	}

	function gEntitasDelete($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			return _entitasDelete( $record );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			$ret[] = _entitasDelete( $record );
		}

		return $ret;
	}

	function gEntitasRestore($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			return _entitasRestore( $record );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			$ret[] = _entitasRestore( $record );
		}

		return $ret;
	}

	function gAdd($rec) {
		$g_tab = thisTable(  );
		$pk = thisPK(  );

		if (!is_array( $pk )) {
			$rec[$pk] = getUUID(  );
		}

		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = insertSQL( $g_tab, $rec );
		$ok = dbQuery( $sql );

		if ($ok) {
			if (is_array( $pk )) {
				$ret = array(  );
				foreach ($pk as $k) {
					$ret[$k] = $rec[$k];
				}

				return $ret;
			}

			return $rec[$pk];
		}

		return false;
	}

	function gUpdate($rec, $where) {
		$g_tab = thisTable(  );
		$sql = updateSQL( $g_tab, $rec, $where );
		return dbQuery( $sql );
	}

	function gDelete($where) {
		$g_tab = thisTable(  );
		$rec = array(  );
		$rec['soft_delete'] = 1;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( $g_tab, $rec, $where );
		return dbQuery( $sql );
	}

	function gRestore($where) {
		$g_tab = thisTable(  );
		$rec = array(  );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( $g_tab, $rec, $where );
		return dbQuery( $sql );
	}

	function listReferensi() {
		$tables = array( 'agama' => 'Agama', 'bentuk_pendidikan' => 'Bentuk Pendidikan', 'ikatan_kerja_dosen' => 'Ikatan Kerja Dosen', 'jabfung' => 'Jabatan Fungsional', 'jenis_evaluasi' => 'Jenis Evaluasi', 'jenis_keluar' => 'Jenis Keluar', 'jenis_pendaftaran' => 'Jenis Pendaftaran Mahasiswa', 'jenis_sert' => 'Jenis Sertifikasi', 'jenis_sms' => 'Jenis Program Studi', 'jenis_subst' => 'Jenis Substansi', 'jenjang_pendidikan' => 'Jejang Pendidikan', 'jurusan' => 'Jurusan', 'lembaga_pengangkat' => 'Lembaga Pengangkat', 'level_wilayah' => 'Level Wilayah', 'negara' => 'Negara', 'pangkat_golongan' => 'Pangkat Golongan', 'pekerjaan' => 'Pekerjaan', 'penghasilan' => 'Penghasilan', 'semester' => 'Semester', 'status_keaktifan_pegawai' => 'Status Keaktifan Pegawai', 'status_kepegawaian' => 'Status Kepegawaian', 'status_mahasiswa' => 'Status Mahasiswa', 'tahun_ajaran' => 'Tahun Ajaran', 'wilayah' => 'Wilayah' );
		return $tables;
	}

	function listData() {
		$tables = array( 'ajar_dosen' => 'Dosen Mengajar', 'bobot_nilai' => 'Bobot Nilai', 'daya_tampung' => 'Kapasitas mahasiswa baru', 'dosen' => 'Data dosen nasional. Tidak boleh menambah dan menghapus. Hanya dari Forlap', 'dosen_pt' => 'Dosen perguruan tinggi. Penambahan dan Perubahan data hanya dari Forlap', 'kelas_kuliah' => 'Kelas Perkuliahan. Menyimpan jadwal perkuliahan yang di buka, dosen pengajar, serta peserta kelas / KRS mahasiswa setiap periode', 'kuliah_mahasiswa' => 'Aktivitas perkuliahan mahasiswa', 'kurikulum' => 'Kurikulum per prodi', 'mahasiswa' => 'Data mahasiswa nasional', 'mahasiswa_pt' => 'Mahasiswa perguruan tinggi', 'mata_kuliah' => 'Mata kuliah per program studi', 'mata_kuliah_kurikulum' => 'Mata kuliah per kurikulum', 'satuan_pendidikan' => 'Perguruan Tinggi. Perubahan data hanya dari Forlap', 'sms' => 'Program Studi. Perubahan data hanya dari Forlap', 'substansi_kuliah' => 'Substansi kuliah', 'nilai' => 'Nilai mahasiswa per kelas per periode' );
		return $tables;
	}

	function insertSQL($table, $fields) {
		$sql = 'insert into ' . $table . ' (';
		$fieldnames = '';
		$fieldvals = '';
		$i = 8;
		$binds = array(  );
		reset( $fields );
		$v = each( $fields )[1];
		[0];
		$f = ;

		if () {
			$fieldnames .= $f . ', ';

			if (preg_match_all( '/{{(.*?)}}/', $v, $m )) {
				$val = strtolower( $m[1][0] );
				$fieldvals .= $m[1][0] . ', ';
			}

			$fieldvals .= '\'' . pg_escape_string( $v ) . '\\', ';
		}

		$fieldnames = substr( $fieldnames, 0, 0 - 2 );
		$fieldvals = substr( $fieldvals, 0, 0 - 2 );
		$sql .= $fieldnames . ') values (' . $fieldvals . ')';
		return $sql;
	}

	function updateSQL($table, $fields, $where) {
		$sql = 'update ' . $table . ' set ';
		$vars = '';
		reset( $fields );
		$v = each( $fields )[1];
		[0];
		$f = ;

		if () {
			if (preg_match_all( '/{{(.*?)}}/', $v, $m )) {
				$vars .= '' . $f . ' = ' . $m[1][0] . ', ';
			}

			$vars .= '' . $f . ' = \'' . pg_escape_string( $v ) . '\\', ';
		}

		$sql .= substr( $vars, 0, 0 - 2 );
		$sql .= ' where ' . $where;
		return $sql;
	}

	function dbGetOne($sql) {
		$result = $conn = getConn(  );
		pg_fetch_row( $result );

		if ($row = pg_query( $conn, $sql )) {
			return $row[0];
		}

	}

		$conn = function dbGetRows($sql) {;
		pg_query( $conn, $sql );
		$result = getConn(  );
		$i = 8;
		$rows = array(  );

		if ($row = pg_fetch_assoc( $result )) {
			$rows[] = $row;
		}

		return $rows;
	}

	function dbQuery($sql) {
		$conn = getConn(  );
		return pg_query( $conn, $sql );
	}

	function dbLastError() {
		$conn = getConn(  );
		return pg_last_error( $conn );
	}

	function escapeLower($str) {
		return pg_escape_string( strtolower( $str ) );
	}

	function isValidDate($str) {
		if (preg_match( '/-/', $str )) {
			$date = explode( '-', $str );
			$month = (int)$date[1];
			$day = (int)$date[2];
			$year = (int)$date[0];
			return checkdate( $month, $day, $year );
		}

		return false;
	}

	function getIDUpdater() {
		global $g_token;

		$path = '../application/logs';
		$file = $path . '/token_' . $g_token;

		if (file_exists( $file )) {
			return file_get_contents( $file );
		}

	}

	function getUUID() {
		sleep( 1 );
		$sql = 'select uuid_generate_v4()';
		return dbGetOne( $sql );
	}

	function setErrorStatus($ret, $error_code) {
		global $error_status;

		if (!$ret) {
			$ret = array(  );
		}

		$ret['error_code'] = $error_code;
		$ret['error_desc'] = $error_status[$error_code];
		$ret['result'] = '';
		return $ret;
	}

	function setErrorStatus2($ret, $error_code) {
		global $error_status;

		if (!$ret) {
			$ret = array(  );
		}

		$ret['error_code'] = $error_code;
		$ret['error_desc'] = $error_status[$error_code];
		return $ret;
	}

	function retTrue($result) {
		$ret = array(  );
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		$ret['result'] = $result;
		return $ret;
	}

	function getSQL($table, $is_count = false, $is_delete = false) {
		$soft_delete = 'p.soft_delete=0';

		if ($is_delete) {
			$soft_delete = 'p.soft_delete=1';
		}

		$sql = '';

		if ($is_count) {
			$sql .= 'select count(*) ';
		}

		$is_raw = false;

		if (strstr( $table, '.raw' )) {
			$is_raw = true;
			$table = str_replace( '.raw', '', $table );
		}


		if ($table == 'mahasiswa') {
			if ($is_raw) {
				if (!$is_count) {
					$sql .= 'select p.* ';
				}

				$sql .= 'from peserta_didik p where ' . $soft_delete;
			} 
else {
				if (!$is_count) {
					$sql .= 'select p.id_pd, p.nm_pd, p.jk, p.nisn, p.nik, p.tmpt_lahir, p.tgl_lahir, p.id_agama, a.nm_agama fk__agama, p.id_kk, kk.nm_kk fk__kk, p.id_sp, sp.nm_lemb fk__sp,
					p.jln, p.rt, p.rw, p.nm_dsn, p.ds_kel, p.id_wil, w.nm_wil fk__wil, p.kode_pos, p.id_jns_tinggal, jt.nm_jns_tinggal fk__jns_tinggal, p.id_alat_transport, at.nm_alat_transport fk__alat_transport, 
					p.telepon_rumah, p.telepon_seluler, p.email, p.a_terima_kps, p.no_kps, p.stat_pd,
					p.nm_ayah, p.tgl_lahir_ayah, p.id_jenjang_pendidikan_ayah, jp.nm_jenj_didik fk__jenjang_pendidikan_ayah,
					p.id_pekerjaan_ayah, pk.nm_pekerjaan fk__pekerjaan_ayah, p.id_penghasilan_ayah, ph.nm_penghasilan fk__penghasilan_ayah, p.id_kebutuhan_khusus_ayah, kk2.nm_kk fk__kebutuhan_khusus_ayah, 
					p.nm_ibu_kandung, p.tgl_lahir_ibu, p.id_jenjang_pendidikan_ibu, jp2.nm_jenj_didik fk__jenjang_pendidikan_ibu,
					p.id_pekerjaan_ibu, pk2.nm_pekerjaan fk__pekerjaan_ibu, p.id_penghasilan_ibu, ph2.nm_penghasilan fk__penghasilan_ibu, p.id_kebutuhan_khusus_ibu,  kk3.nm_kk fk__kebutuhan_khusus_ibu, 
					p.nm_wali, p.tgl_lahir_wali, p.id_jenjang_pendidikan_wali, jp3.nm_jenj_didik fk__jenjang_pendidikan_wali,
					p.id_pekerjaan_wali, pk3.nm_pekerjaan fk__pekerjaan_wali, p.id_penghasilan_wali, ph3.nm_penghasilan fk__penghasilan_wali,
					p.kewarganegaraan ';
				}

				$sql .= 'from peserta_didik p 
					left join ref.agama a on a.id_agama = p.id_agama
					left join ref.kebutuhan_khusus kk on kk.id_kk=p.id_kk
					left join satuan_pendidikan sp on sp.id_sp=p.id_sp
					left join ref.wilayah w on w.id_wil=p.id_wil
					left join ref.jenis_tinggal jt on jt.id_jns_tinggal=p.id_jns_tinggal
					left join ref.alat_transport at on at.id_alat_transport=p.id_alat_transport
					left join ref.jenjang_pendidikan jp on jp.id_jenj_didik=p.id_jenjang_pendidikan_ayah
					left join ref.pekerjaan pk on pk.id_pekerjaan=p.id_pekerjaan_ayah
					left join ref.penghasilan ph on ph.id_penghasilan=p.id_penghasilan_ayah
					left join ref.kebutuhan_khusus kk2 on kk2.id_kk=p.id_kebutuhan_khusus_ayah
					left join ref.jenjang_pendidikan jp2 on jp2.id_jenj_didik=p.id_jenjang_pendidikan_ibu
					left join ref.pekerjaan pk2 on pk2.id_pekerjaan=p.id_pekerjaan_ibu
					left join ref.penghasilan ph2 on ph2.id_penghasilan=p.id_penghasilan_ibu
					left join ref.kebutuhan_khusus kk3 on kk3.id_kk=p.id_kebutuhan_khusus_ibu
					left join ref.jenjang_pendidikan jp3 on jp3.id_jenj_didik=p.id_jenjang_pendidikan_wali
					left join ref.pekerjaan pk3 on pk3.id_pekerjaan=p.id_pekerjaan_wali
					left join ref.penghasilan ph3 on ph3.id_penghasilan=p.id_penghasilan_wali
					where ' . $soft_delete . ' ';
			}
		} 
else {
			if ($table == 'mahasiswa_pt') {
				if ($is_raw) {
					if (!$is_count) {
						$sql .= 'select p.* ';
					}

					$sql .= 'from reg_pd p where ' . $soft_delete;
				} 
else {
					if (!$is_count) {
						$sql .= 'select p.id_reg_pd, p.nipd, p.id_pd, r.nm_pd, r.tgl_lahir, p.id_sms, s.nm_lemb fk__sms, p.id_sp, sp.nm_lemb fk__sp,
					p.tgl_masuk_sp, p.id_jns_daftar, jp.nm_jns_daftar fk__jns_daftar, p.id_jns_keluar, jk.ket_keluar fk__jns_keluar,
					p.tgl_keluar, p.ket, p.skhun, p.a_pernah_paud, p.a_pernah_tk, p.mulai_smt, p.sks_diakui, p.jalur_skripsi, p.judul_skripsi,
					p.bln_awal_bimbingan, p.bln_akhir_bimbingan, p.sk_yudisium, p.tgl_sk_yudisium, p.ipk, p.no_seri_ijazah, p.sert_prof,
					p.a_pindah_mhs_asing, p.nm_pt_asal, p.nm_prodi_asal ';
					}

					$sql .= 'from reg_pd p join peserta_didik r on p.id_pd=r.id_pd
					join sms s on p.id_sms=s.id_sms
					join satuan_pendidikan sp on p.id_sp=sp.id_sp
					left join ref.jenis_pendaftaran jp on p.id_jns_daftar=jp.id_jns_daftar
					left join ref.jenis_keluar jk on p.id_jns_keluar=jk.id_jns_keluar
					where ' . $soft_delete . ' ';
				}
			} 
else {
				if ($table == 'dosen') {
					if ($is_raw) {
						if (!$is_count) {
							$sql .= 'select p.* ';
						}

						$sql .= 'from ptk p where ' . $soft_delete;
					} 
else {
						if (!$is_count) {
							$sql .= 'select p.id_ptk, p.id_blob, p.id_ikatan_kerja, ikd.nm_ikatan_kerja fk__ikatan_kerja, p.nm_ptk, p.nidn, p.nip, 
					p.jk, p.tmpt_lahir, p.tgl_lahir, p.nik, p.niy_nigk, p.nuptk, p.id_stat_pegawai, sk.nm_stat_pegawai fk__stat_pegawai, 
					p.id_jns_ptk, jp.nm_jns_ptk fk__jns_ptk, p.id_bid_pengawas, p.id_agama, a.nm_agama fk__agama, p.jln, p.rt, p.rw, p.nm_dsn, 
					p.ds_kel, p.id_wil, w.nm_wil fk__wilayah, p.kode_pos, p.no_tel_rmh, p.no_hp, p.email, p.id_sp, sp.nm_lemb fk__sp, 
					p.id_stat_aktif, skp.nm_stat_aktif fk__stat_aktif, p.sk_cpns, p.tgl_sk_cpns, p.sk_angkat, p.tmt_sk_angkat, p.id_lemb_angkat, 
					lp.nm_lemb_angkat fk__lemb_angkat, p.id_pangkat_gol, pg.nm_pangkat fk__pangkat_gol, p.id_keahlian_lab, 
					kl.nm_keahlian_lab fk__keahlian_lab, p.id_sumber_gaji, sg.nm_sumber_gaji fk__sumber_gaji, p.nm_ibu_kandung, p.stat_kawin, 
					p.nm_suami_istri, p.nip_suami_istri, p.id_pekerjaan_suami_istri, pk.nm_pekerjaan fk__perkerjaan_suami_istri, p.tmt_pns, 
					p.a_lisensi_kepsek, p.jml_sekolah_binaan, p.a_diklat_awas, p.akta_ijin_ajar, p.nira, p.stat_data, p.mampu_handle_kk, 
					p.a_braille, p.a_bhs_isyarat, p.npwp, p.kewarganegaraan ';
						}

						$sql .= 'from ptk p
					left join ref.ikatan_kerja_dosen ikd on ikd.id_ikatan_kerja=p.id_ikatan_kerja
					left join ref.status_kepegawaian sk on sk.id_stat_pegawai=p.id_stat_pegawai
					left join ref.jenis_ptk jp on jp.id_jns_ptk=p.id_jns_ptk
					left join ref.agama a on a.id_agama=p.id_agama
					left join satuan_pendidikan sp on sp.id_sp=p.id_sp
					left join ref.wilayah w on w.id_wil=p.id_wil
					left join ref.status_keaktifan_pegawai skp on skp.id_stat_aktif=p.id_stat_aktif
					left join ref.lembaga_pengangkat lp on lp.id_lemb_angkat=p.id_lemb_angkat
					left join ref.pangkat_golongan pg on pg.id_pangkat_gol=p.id_pangkat_gol
					left join ref.keahlian_lab kl on kl.id_keahlian_lab=p.id_keahlian_lab
					left join ref.sumber_gaji sg on sg.id_sumber_gaji=p.id_sumber_gaji
					left join ref.pekerjaan pk on pk.id_pekerjaan=p.id_pekerjaan_suami_istri
					where ' . $soft_delete . ' ';
					}
				} 
else {
					if ($table == 'dosen_pt') {
						if ($is_raw) {
							if (!$is_count) {
								$sql .= 'select p.* ';
							}

							$sql .= 'from reg_ptk p where ' . $soft_delete;
						} 
else {
							if (!$is_count) {
								$sql .= 'select p.id_reg_ptk, p.id_ptk, r.nm_ptk, r.tgl_lahir, r.nik, p.id_sp, sp.nm_lemb fk__sp, p.id_thn_ajaran,
					ta.nm_thn_ajaran fk__thn_ajaran, p.id_sms, s.nm_lemb fk__sms, p.no_srt_tgs, p.tgl_srt_tgs, p.tmt_srt_tgs,
					p.a_sp_homebase, p.a_aktif_bln_1, p.a_aktif_bln_2, p.a_aktif_bln_3, p.a_aktif_bln_4, p.a_aktif_bln_5, p.a_aktif_bln_6,
					p.a_aktif_bln_7, p.a_aktif_bln_8, p.a_aktif_bln_9, p.a_aktif_bln_10, p.a_aktif_bln_11, p.a_aktif_bln_12, p.id_jns_keluar,
					jk.ket_keluar fk__jns_keluar, p.tgl_ptk_keluar ';
							}

							$sql .= 'from reg_ptk p
					join ptk r on r.id_ptk=p.id_ptk
					join satuan_pendidikan sp on sp.id_sp=p.id_sp
					join ref.tahun_ajaran ta on ta.id_thn_ajaran=p.id_thn_ajaran
					left join sms s on s.id_sms=p.id_sms
					left join ref.jenis_keluar jk on jk.id_jns_keluar=p.id_jns_keluar
					where ' . $soft_delete . ' ';
						}
					} 
else {
						if ($table == 'kurikulum_sp') {
							if ($is_raw) {
								if (!$is_count) {
									$sql .= 'select p.* ';
								}

								$sql .= 'from kurikulum_sp p where ' . $soft_delete;
							} 
else {
								if (!$is_count) {
									$sql .= 'select p.id_kurikulum_sp, p.nm_kurikulum_sp, p.jml_sem_normal, p.jml_sks_lulus, p.jml_sks_wajib, 
					p.jml_sks_pilihan, p.id_sms, s.nm_lemb fk__sms, p.id_jenj_didik, j.nm_jenj_didik fk__jenj_didik, 
					p.id_smt_berlaku, sm.nm_smt fk__smt_berlaku ';
								}

								$sql .= 'from kurikulum_sp p
					left join sms s on s.id_sms=p.id_sms
					left join ref.jenjang_pendidikan j on j.id_jenj_didik=p.id_jenj_didik
					left join ref.semester sm on sm.id_smt = p.id_smt_berlaku
					where ' . $soft_delete . ' ';
							}
						} 
else {
							if ($table == 'mata_kuliah_kurikulum') {
								$tab = tableMap( $table );

								if ($is_raw) {
									if (!$is_count) {
										$sql .= 'select p.* ';
									}

									$sql .= 'from ' . $tab . ' p where ' . $soft_delete;
								} 
else {
									if (!$is_count) {
										$sql .= 'select p.id_kurikulum_sp, k.nm_kurikulum_sp fk__id_kurikulum_sp, p.id_mk, m.kode_mk fk__id_mk, 
					m.nm_mk, p.smt, p.sks_mk, p.sks_tm, p.sks_prak, p.sks_prak_lap, p.sks_sim, p.a_wajib ';
									}

									$sql .= 'from ' . $tab . ' p
					left join matkul m on m.id_mk = p.id_mk
					left join kurikulum_sp k on k.id_kurikulum_sp = p.id_kurikulum_sp
					where ' . $soft_delete . ' ';
								}
							} 
else {
								if ($table == 'nilai') {
									$tab = tableMap( $table );

									if ($is_raw) {
										if (!$is_count) {
											$sql .= 'select p.* ';
										}

										$sql .= 'from ' . $tab . ' p where ' . $soft_delete;
									} 
else {
										if (!$is_count) {
											$sql .= 'select p.id_kls, k.nm_kls fk__id_kls, p.id_reg_pd, d.nm_pd, p.asal_data, p.nilai_angka, p.nilai_huruf,
					p.nilai_indeks ';
										}

										$sql .= 'from ' . $tab . ' p
					left join kelas_kuliah k on k.id_kls = p.id_kls
					left join reg_pd r on r.id_reg_pd = p.id_reg_pd
					left join peserta_didik d on d.id_pd=r.id_pd
					where ' . $soft_delete . ' ';
									}
								} 
else {
									if ($table == 'kuliah_mhs') {
										$tab = tableMap( $table );

										if ($is_raw) {
											if (!$is_count) {
												$sql .= 'select p.* ';
											}

											$sql .= 'from ' . $tab . ' p where ' . $soft_delete;
										} 
else {
											if (!$is_count) {
												$sql .= 'select p.*,j.nm_jenj_didik||\' \'||s.nm_lemb as nm_lemb, r.mulai_smt,r.nipd, sm.nm_stat_mhs, nm_smt, nm_pd ';
											}

											$sql .= 'from public.' . $tab . ' p 
				join public.reg_pd r on r.id_reg_pd = p.id_reg_pd 
				join ref.status_mahasiswa sm on sm.id_stat_mhs = p.id_stat_mhs 
				join ref.semester st on st.id_smt = p.id_smt 
				join public.peserta_didik pd on pd.id_pd = r.id_pd left 
				join public.sms s on s.id_sms = r.id_sms 
				left join ref.jenjang_pendidikan j on j.id_jenj_didik = s.id_jenj_didik 
				where ' . $soft_delete . ' ';
										}
									} 
else {
										if ($table == 'kelas_kuliah') {
											if ($is_raw) {
												if (!$is_count) {
													$sql .= 'select p.* ';
												}

												$sql .= 'from kelas_kuliah p where ' . $soft_delete;
											} 
else {
												if (!$is_count) {
													$sql .= 'select id_kls, p.id_sms,  s.nm_lemb fk__id_sms, p.id_smt, sm.nm_smt fk__id_smt, p.id_mk,
						m.nm_mk fk__id_mk, nm_kls, p.sks_mk, p.sks_tm, p.sks_prak, p.sks_prak_lap, p.sks_sim, bahasan_case,
						tgl_mulai_koas, tgl_selesai_koas, id_mou, a_selenggara_pditt, kuota_pditt, a_pengguna_pditt, id_kls_pditt ';
												}

												$sql .= 'from kelas_kuliah p
					left join sms s on s.id_sms = p.id_sms
					left join ref.semester sm on sm.id_smt = p.id_smt
					left join matkul m on m.id_mk = p.id_mk
					where ' . $soft_delete . ' ';
											}
										} 
else {
											if ($table == 'sms') {
												if (!$is_count) {
													$sql .= 'select p.* ';
												}

												$sql .= 'from sms p where ' . $soft_delete . ' and id_jns_sms = \'3\' and stat_prodi = \'A\' ';
											} 
else {
												if ($table == 'ajar_dosen') {
													$tab = tableMap( $table );

													if ($is_raw) {
														if (!$is_count) {
															$sql .= 'select p.* ';
														}

														$sql .= 'from ' . $tab . ' p where ' . $soft_delete;
													} 
else {
														if (!$is_count) {
															$sql .= 'select p.id_ajar, p.id_reg_ptk, t.nm_ptk as fk__id_reg_ptk, p.id_subst, p.id_kls, p.sks_subst_tot, p.sks_tm_subst, p.sks_prak_subst,
				p.sks_prak_lap_subst, p.sks_sim_subst, p.jml_tm_renc, p.jml_tm_real, p.id_jns_eval ';
														}

														$sql .= 'from ' . $tab . ' p
					join reg_ptk r on r.id_reg_ptk = p.id_reg_ptk
					join ptk t on t.id_ptk = r.id_ptk 
					where ' . $soft_delete . ' ';
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $sql;
	}

	function tableMap($table) {
		if ($table == 'ajar_dosen') {
			return 'akt_ajar_dosen';
		}


		if ($table == 'dosen') {
			return 'ptk';
		}


		if ($table == 'dosen_pt') {
			return 'reg_ptk';
		}


		if ($table == 'mahasiswa') {
			return 'peserta_didik';
		}


		if ($table == 'mahasiswa_pt') {
			return 'reg_pd';
		}


		if ($table == 'mata_kuliah') {
			return 'matkul';
		}


		if ($table == 'mata_kuliah_kurikulum') {
			return 'matkul_kurikulum';
		}


		if ($table == 'kuliah_mahasiswa') {
			return 'kuliah_mhs';
		}


		if ($table == 'kurikulum') {
			return 'kurikulum_sp';
		}


		if ($table == 'nilai') {
			return 'nilai_smt_mhs';
		}

		return $table;
	}

	function includeTable($table) {
		$inc = $table . '.php';

		if (!file_exists( $inc )) {
			return false;
		}

		include_once( $inc );
		return true;
	}

	function changeLog() {
		$str = '
	Web Service Change Log
	----------------------------------------
	
	Versi 1.1
	------------------
	24 Januari 2015
	
	- Menambah fungsi RestoreRecord
	- Menambah fungsi RestoreRecordset
	- Menambah fungsi GetDeletedRecordset
	- Menambah fungsi GetCountDeletedRecordset
	- Menambah fungsi GetExpired
	- Menambah fungsi GetChangeLog
	
	- Mengubah nama tabel dari mahasiswa_history menjadi mahasiswa_pt
	- Mengubah nama tabel dari dosen_history menjadi dosen_pt
	- Mengubah nama tabel dari matkul menjadi mata_kuliah
	- Mengubah nama tabel dari matkul_kurikulum menjadi mata_kuliah_kurikulum
	- Mengubah nama tabel dari kurikulumsp menjadi kurikulum
	- Mengubah nama tabel dari substansi menjadi substansi_kuliah
	
	- Perbaikan validasi
	
	Versi 1.0
	------------------
	5 Januari 2015
	
	- Aplikasi web service released
	';
		return $str;
	}

?>