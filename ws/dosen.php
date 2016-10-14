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

	function thisTable() {
		return 'ptk';
	}

	function updateFields() {
		$fields = array( 'nm_ibu_kandung', 'npwp', 'id_agama', 'id_sumber_gaji', 'jln', 'rt', 'rw', 'nm_dsn', 'ds_kel', 'id_wil', 'kode_pos', 'no_tel_rmh', 'no_hp', 'email', 'stat_kawin', 'nm_suami_istri', 'nip_suami_istri', 'id_pekerjaan_suami_istri', 'tmt_pns', 'mampu_handle_kk', 'a_braille', 'a_bhs_isyarat' );
		return $fields;
	}

	function globalKeys() {
		$g_keys = array( 'id_ptk', 'nm_ptk', 'tgl_lahir' );
		return $g_keys;
	}

	function entitasAdd($data, $is_get_record = false) {
		$ret = array(  );
		setErrorStatus2( $ret, '300' );
		return $ret;
	}

	function _entitasAdd($rec) {
		$ret = initRetAdd( $rec );

		if (!isValidEntitasAdd( $ret, $rec )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$sql = '';
		$id_ptk = addPTK( $rec, $sql );

		if ($id_ptk) {
			dbQuery( 'COMMIT' );
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasAdd($ret, $rec) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}


		if (!isValidInput( $ret )) {
			return false;
		}

		$nm_ptk = pg_escape_string( strtolower( $rec['nm_ptk'] ) );

		if (!$nm_ptk) {
			setErrorStatus2( $ret, '201' );
			return false;
		}


		if (!isValidDate( $rec['tgl_lahir'] )) {
			setErrorStatus2( $ret, '202' );
			return false;
		}

		$tgl_lahir = $rec['tgl_lahir'];
		$sql = 'select 1 from ptk where lower(nm_ptk)=\'' . $nm_ptk . '\' and tgl_lahir=\'' . $tgl_lahir . '\' and soft_delete=0';

		if (dbGetOne( $sql )) {
			setErrorStatus2( $ret, '200' );
			return false;
		}

		return true;
	}

	function entitasUpdate($data, $is_get_record = false) {
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

	function _entitasUpdate($key, $rec) {
		$id_updater = getIDUpdater(  );
		$ret = initRetUpdate( $key );

		if (!isValidInput( $ret, $key )) {
			return $ret;
		}


		if (!isValidInput( $ret, $rec )) {
			return $ret;
		}

		$data = array(  );
		$allow_fields = updateFields(  );
		foreach ($allow_fields as $field) {

			if (isset( $rec[$field] )) {
				$data[$field] = $rec[$field];
				continue;
			}
		}


		if (!count( $data )) {
			setErrorStatus2( $ret, '105' );
			return $ret;
		}

		$data['soft_delete'] = 0;
		$data['id_updater'] = $id_updater;
		$data['last_update'] = '{{now()}}';
		$where = 'soft_delete=0 ';
		foreach ($key as $k => $v) {
			$v = pg_escape_string( $v );
			$where .= ' and ' . $k . '=\'' . $v . '\' ';
		}


		if (!isValidEntitasUpdate( $ret, $where )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_ptk = updateEntitas( $data, $where );

		if ($id_ptk) {
			dbQuery( 'COMMIT' );
			$ret['id_ptk'] = $id_ptk;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasUpdate($ret, $where) {
		$g_tab = thisTable(  );
		$sql = 'select count(*) from ' . $g_tab . ' where ' . $where;
		$num = dbGetOne( $sql );

		if (!$num) {
			setErrorStatus2( $ret, '303' );
			return false;
		}


		if (1 < $num) {
			setErrorStatus2( $ret, '304' );
			return false;
		}

		return true;
	}

	function entitasDelete($data, $is_get_record = false) {
		$ret = array(  );
		setErrorStatus2( $ret, '301' );
		return $ret;
	}

	function _entitasDelete($rec) {
		$ret = initRet( $rec );

		if (!isValidEntitasDelete( $ret, $rec )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_ptk = pg_escape_string( $rec['id_ptk'] );
		$nm_ptk = pg_escape_string( $rec['nm_ptk'] );
		$tgl_lahir = pg_escape_string( $rec['tgl_lahir'] );

		if ($id_ptk) {
			$where = 'id_ptk=\'' . $id_ptk . '\' and soft_delete=0';
		} 
else {
			if (( $nm_ptk && $tgl_lahir )) {
				$where = 'nm_ptk=\'' . $nm_ptk . '\' and tgl_lahir=\'' . $tgl_lahir . '\' and soft_delete=0';
			}
		}

		$id_ptk = deleteEntitas( $where );

		if ($id_ptk) {
			dbQuery( 'COMMIT' );
			$ret['id_ptk'] = $id_ptk;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasDelete($ret, $rec) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}

		$id_ptk = strtolower( pg_escape_string( $rec['nm_ptk'] ) );
		$sql = 'select 1 from ptk where id_ptk=\'' . $id_ptk . '\' and soft_delete=0';

		if (!dbGetOne( $sql )) {
			setErrorStatus2( $ret, '310' );
			return false;
		}

		return true;
	}

	function initRet($rec) {
		$ret = array(  );
		$ret['nm_ptk'] = $rec['nm_ptk'];
		$ret['tgl_lahir'] = $rec['tgl_lahir'];
		$ret['nip'] = $rec['nip'];
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function initRetAdd($rec) {
		$ret = array(  );
		$ret['nm_ptk'] = $rec['nm_ptk'];
		$ret['tgl_lahir'] = $rec['tgl_lahir'];
		$ret['nip'] = $rec['nip'];
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function initRetUpdate($key) {
		$g_keys = globalKeys(  );
		$ret = array(  );
		foreach ($g_keys as $v) {

			if (array_key_exists( $v, $key )) {
				$ret[$v] = $key[$v];
				continue;
			}
		}

		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function addPTK(&$rec, $sql) {
		$rec['id_ptk'] = getUUID(  );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = insertSQL( 'ptk', $rec );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $rec['id_ptk'];
		}

		return false;
	}

	function updateEntitas($rec, $where) {
		$sql = 'select id_ptk from ptk where ' . $where;
		$id_ptk = dbGetOne( $sql );
		$sql = updateSQL( 'ptk', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_ptk;
		}

		return false;
	}

	function deleteEntitas($where) {
		$rec = array(  );
		$sql = 'select id_ptk from ptk where ' . $where;
		$id_ptk = dbGetOne( $sql );
		$rec['soft_delete'] = 1;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( 'ptk', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_ptk;
		}

		return false;
	}

?>