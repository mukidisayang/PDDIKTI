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

	function globalKeys() {
		$g_keys = array( 'id_pd', 'id_reg_pd', 'nm_pd', 'tgl_lahir', 'nipd', 'nisn', 'regpd_nipd' );
		return $g_keys;
	}

	function entitasAdd($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			return _entitasAdd( $rec, $rec2 );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			$ret[] = _entitasAdd( $rec, $rec2 );
		}

		return $ret;
	}

	function _entitasAdd($rec, $rec2) {
		$ret = initRetAdd( $rec, $rec2 );

		if (!isValidEntitasAdd( $ret, $rec, $rec2 )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_pd = addPesertaDidik( $rec );

		if ($id_pd) {
			if (count( $rec2 )) {
				$id_reg_pd = addRegPD( $rec2, $id_pd );

				if ($id_reg_pd) {
					dbQuery( 'COMMIT' );
					$ret['id_pd'] = $id_pd;
					$ret['id_reg_pd'] = $id_reg_pd;
					setErrorStatus2( $ret, '0' );
					return $ret;
				}

				$error = dbLastError(  );
				dbQuery( 'ROLLBACK' );
				setErrorStatus2( $ret, '103' );
				$ret->error_desc .= $error;
				return $ret;
			}

			dbQuery( 'COMMIT' );
			$ret['id_pd'] = $id_pd;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasAdd($ret, $rec, $rec2) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}


		if (!isValidInput( $ret, $rec2 )) {
			return false;
		}

		$nm_pd = pg_escape_string( strtolower( $rec['nm_pd'] ) );

		if (!$nm_pd) {
			setErrorStatus2( $ret, '201' );
			return false;
		}


		if (!isValidDate( $rec['tgl_lahir'] )) {
			setErrorStatus2( $ret, '202' );
			return false;
		}

		$tgl_lahir = $rec['tgl_lahir'];
		$sql = 'select 1 from peserta_didik where lower(nm_pd)=\'' . $nm_pd . '\' and tgl_lahir=\'' . $tgl_lahir . '\' and soft_delete=0';

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

	function _entitasUpdate($key, $data) {
		$id_updater = getIDUpdater(  );
		cleanReg( $data, $rec, $rec2 );
		$ret = initRetUpdate( $key );

		if (!isValidInput( $ret, $key )) {
			return $ret;
		}


		if (!isValidInput( $ret, $rec )) {
			return $ret;
		}


		if (!isValidInput( $ret, $rec2 )) {
			return $ret;
		}

		$rec['soft_delete'] = 0;
		$rec['id_updater'] = $id_updater;
		$rec['last_update'] = '{{now()}}';
		$where = 'soft_delete=0 ';
		foreach ($key as $k => $v) {
			$v = pg_escape_string( $v );
			$where .= ' and ' . $k . '=\'' . $v . '\' ';
		}


		if (!isValidEntitasUpdate( $ret, $where )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_pd = updatePesertaDidik( $rec, $where );

		if ($id_pd) {
			if (count( $rec2 )) {
				$id_reg_pd = updateRegPD( $rec2, $id_pd );

				if ($id_reg_pd) {
					dbQuery( 'COMMIT' );
					$ret['id_pd'] = $id_pd;
					setErrorStatus2( $ret, '0' );
					return $ret;
				}

				$error = dbLastError(  );
				dbQuery( 'ROLLBACK' );
				setErrorStatus2( $ret, '103' );
				$ret->error_desc .= $error;
				return $ret;
			}

			dbQuery( 'COMMIT' );
			$ret['id_pd'] = $id_pd;
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
		$sql = 'select count(*) from peserta_didik where ' . $where;
		$num = dbGetOne( $sql );

		if (!$num) {
			setErrorStatus2( $ret, '203' );
			return false;
		}


		if (1 < $num) {
			setErrorStatus2( $ret, '204' );
			return false;
		}

		return true;
	}

	function entitasDelete($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			return _entitasDelete( $rec, $rec2 );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			$ret[] = _entitasDelete( $rec, $rec2 );
		}

		return $ret;
	}

	function _entitasDelete($rec, $rec2) {
		$ret = initRet( $rec, $rec2 );

		if (!isValidEntitasDelete( $ret, $rec, $rec2 )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_pd = pg_escape_string( $rec['id_pd'] );
		$nm_pd = pg_escape_string( $rec['nm_pd'] );
		$tgl_lahir = pg_escape_string( $rec['tgl_lahir'] );

		if ($id_pd) {
			$where = 'id_pd=\'' . $id_pd . '\' and soft_delete=0';
		} 
else {
			if (( $nm_pd && $tgl_lahir )) {
				$where = 'nm_pd=\'' . $nm_pd . '\' and tgl_lahir=\'' . $tgl_lahir . '\' and soft_delete=0';
			}
		}

		$id_pd = deletePesertaDidik( $where );

		if ($id_pd) {
			if (count( $rec2 )) {
				$id_reg_pd = deleteRegPD( $id_pd );

				if ($id_reg_pd) {
					dbQuery( 'COMMIT' );
					$ret['id_pd'] = $id_pd;
					$ret['id_reg_pd'] = $id_reg_pd;
					setErrorStatus2( $ret, '0' );
					return $ret;
				}

				$error = dbLastError(  );
				dbQuery( 'ROLLBACK' );
				setErrorStatus2( $ret, '103' );
				$ret->error_desc .= $error;
				return $ret;
			}

			dbQuery( 'COMMIT' );
			$ret['id_pd'] = $id_pd;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasDelete($ret, $rec, $rec2) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}


		if (!isValidInput( $ret, $rec2 )) {
			return false;
		}

		$id_pd = strtolower( pg_escape_string( $rec['id_pd'] ) );
		$nm_pd = strtolower( pg_escape_string( $rec['nm_pd'] ) );

		if ($id_pd) {
			$sql = 'select 1 from peserta_didik where id_pd=\'' . $id_pd . '\' and soft_delete=0';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '203' );
				return false;
			}

			$sql = 'select 1 from reg_pd where id_pd=\'' . $id_pd . '\' and soft_delete=0';

			if (dbGetOne( $sql )) {
				setErrorStatus2( $ret, '215' );
				return false;
			}
		} 
else {
			if (!$nm_pd) {
				setErrorStatus2( $ret, '201' );
				return false;
			}


			if (!isValidDate( $rec['tgl_lahir'] )) {
				setErrorStatus2( $ret, '202' );
				return false;
			}

			$tgl_lahir = pg_escape_string( $rec['tgl_lahir'] );
			dbGetOne( $sql );
			$id_pd = $sql = 'select id_pd from peserta_didik where lower(nm_pd)=\'' . $nm_pd . '\' and tgl_lahir=\'' . $tgl_lahir . '\' and soft_delete=0';

			if (!$id_pd) {
				setErrorStatus2( $ret, '210' );
				return false;
			}

			$sql = 'select 1 from reg_pd where id_pd=\'' . $id_pd . '\' and soft_delete=0';

			if (dbGetOne( $sql )) {
				setErrorStatus2( $ret, '215' );
				return false;
			}
		}

		return true;
	}

	function entitasRestore($data, $is_get_record = false) {
		$ret = array(  );
		$records = json_decode( $data, true );

		if ($is_get_record) {
			$record = $records;
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			return _entitasRestore( $rec, $rec2 );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			cleanReg( $record, $rec, $rec2 );
			$ret[] = _entitasRestore( $rec, $rec2 );
		}

		return $ret;
	}

	function _entitasRestore($rec, $rec2) {
		$ret = initRet( $rec, $rec2 );

		if (!isValidEntitasRestore( $ret, $rec )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_pd = pg_escape_string( strtolower( $rec['id_pd'] ) );

		if ($id_pd) {
			$where = 'id_pd=\'' . $id_pd . '\' and soft_delete=1';
		}

		$id_pd = restorePesertaDidik( $where );

		if ($id_pd) {
			dbQuery( 'COMMIT' );
			$ret['id_pd'] = $id_pd;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasRestore($ret, $rec) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}

		$id_pd = strtolower( pg_escape_string( $rec['id_pd'] ) );

		if ($id_pd) {
			$sql = 'select 1 from peserta_didik where id_pd=\'' . $id_pd . '\' and soft_delete=1';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '203' );
				return false;
			}
		}

		return true;
	}

	function cleanReg(&$record, &$rec, $rec2) {
		$rec = array(  );
		$rec2 = array(  );
		foreach ($record as $k => $v) {

			if (substr( $k, 0, 6 ) == 'regpd_') {
				$k = str_replace( 'regpd_', '', $k );
				$rec2[$k] = $v;
				continue;
			}

			$rec[$k] = $v;
		}

	}

	function initRet($rec, $rec2) {
		$ret = array(  );
		$ret['nm_pd'] = $rec['nm_pd'];
		$ret['tgl_lahir'] = $rec['tgl_lahir'];
		$ret['nisn'] = $rec['nisn'];
		$ret['regpd_nipd'] = $rec2['regpd_nipd'];
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function initRetAdd($rec, $rec2) {
		$ret = array(  );
		$ret['nm_pd'] = $rec['nm_pd'];
		$ret['tgl_lahir'] = $rec['tgl_lahir'];
		$ret['nisn'] = $rec['nisn'];
		$ret['regpd_nipd'] = $rec2['regpd_nipd'];
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

	function addPesertaDidik($rec) {
		$rec['id_pd'] = getUUID(  );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = insertSQL( 'peserta_didik', $rec );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $rec['id_pd'];
		}

		return false;
	}

	function updatePesertaDidik($rec, $where) {
		$sql = 'select id_pd from peserta_didik where ' . $where;
		$id_pd = dbGetOne( $sql );
		$sql = updateSQL( 'peserta_didik', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_pd;
		}

		return false;
	}

	function deletePesertaDidik($where) {
		$rec = array(  );
		$sql = 'select id_pd from peserta_didik where ' . $where;
		$id_pd = dbGetOne( $sql );
		$rec['soft_delete'] = 1;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( 'peserta_didik', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_pd;
		}

		return false;
	}

	function addRegPD($rec2, $id_pd) {
		$rec2['id_reg_pd'] = getUUID(  );
		$rec2['id_pd'] = $id_pd;
		$rec2['soft_delete'] = 0;
		$rec2['id_updater'] = getIDUpdater(  );
		$sql = $rec2['last_update'] = '{{now()}}';
		dbQuery( $sql );
		$ok = insertSQL( 'reg_pd', $rec2 );

		if ($ok) {
			return $rec2['id_reg_pd'];
		}

		return false;
	}

	function updateRegPD($rec2, $id_pd) {
		$rec2['soft_delete'] = 0;
		$rec2['id_updater'] = getIDUpdater(  );
		$rec2['last_update'] = '{{now()}}';
		$where = 'id_pd=\'' . $id_pd . '\'';
		updateSQL( 'reg_pd', $rec2, $where );
		$ok = $sql = dbQuery( $sql );

		if ($ok) {
			$sql = 'select id_reg_pd from reg_pd where ' . $where;
			return dbGetOne( $sql );
		}

		return false;
	}

	function deleteRegPD($id_pd) {
		$rec = array(  );
		$rec['soft_delete'] = 1;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$where = 'id_pd=\'' . $id_pd . '\'';
		$sql = updateSQL( 'reg_pd', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			$sql = 'select id_reg_pd from reg_pd where ' . $where;
			return dbGetOne( $sql );
		}

		return false;
	}

	function restorePesertaDidik($where) {
		$rec = array(  );
		$sql = 'select id_pd from peserta_didik where ' . $where;
		$id_pd = dbGetOne( $sql );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( 'peserta_didik', $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_pd;
		}

		return false;
	}

?>