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
		return 'reg_pd';
	}

	function globalKeys() {
		$g_keys = array( 'id_reg_pd', 'nipd' );
		return $g_keys;
	}

	function entitasAdd($data, $is_get_record = false) {
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

		$ret = function _entitasAdd($record) {;

		if (!isValidEntitasAdd( $ret, $record )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$sql = '';
		$id_reg_pd = addEntitas( $record, $sql );

		if ($id_reg_pd) {
			$ret['id_reg_pd'] = $id_reg_pd;
			dbQuery( 'COMMIT' );
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		dbLastError(  );
		$error = initRetAdd( $record );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasAdd($ret, $record) {
		$g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		$id_pd = pg_escape_string( strtolower( $record['id_pd'] ) );
		$nipd = pg_escape_string( strtolower( $record['nipd'] ) );

		if (!$id_pd) {
			setErrorStatus2( $ret, '212' );
			return false;
		}


		if (!$nipd) {
			setErrorStatus2( $ret, '213' );
			return false;
		}

		$sql = 'select 1 from ' . $g_tab . ' where lower(nipd)=\'' . $nipd . '\' and soft_delete=0';

		if (dbGetOne( $sql )) {
			setErrorStatus2( $ret, '211' );
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
		$ret = initRetUpdate( $key );

		if (!isValidInput( $ret, $key )) {
			return $ret;
		}


		if (!isValidInput( $ret, $data )) {
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
		$id_reg_pd = updateEntitas( $data, $where );

		if ($id_reg_pd) {
			dbQuery( 'COMMIT' );
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

	function isValidEntitasUpdate($ret, $where) {
		$g_tab = thisTable(  );
		$sql = 'select count(*) from ' . $g_tab . ' where ' . $where;
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
			return _entitasDelete( $record );
		}

		foreach ($records as $record) {
			$record = array_map( 'trim', $record );
			$ret[] = _entitasDelete( $record );
		}

		return $ret;
	}

	function _entitasDelete($record) {
		$ret = initRet( $record );

		if (!isValidEntitasDelete( $ret, $record )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		$id_reg_pd = pg_escape_string( strtolower( $record['id_reg_pd'] ) );
		$nipd = pg_escape_string( strtolower( $record['nipd'] ) );

		if ($id_reg_pd) {
			$where = 'id_reg_pd=\'' . $id_reg_pd . '\' and soft_delete=0';
		} 
else {
			if ($nipd) {
				$where = 'lower(nipd)=\'' . $nipd . '\' and soft_delete=0';
			}
		}

		$id_reg_pd = deleteEntitas( $where );

		if ($id_reg_pd) {
			dbQuery( 'COMMIT' );
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

	function isValidEntitasDelete($ret, $record) {
		$g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		$id_reg_pd = pg_escape_string( strtolower( $record['id_reg_pd'] ) );
		$nipd = pg_escape_string( strtolower( $record['nipd'] ) );

		if ($id_reg_pd) {
			$sql = 'select 1 from ' . $g_tab . ' where id_reg_pd=\'' . $id_reg_pd . '\' and soft_delete=0';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, $sql );
				return false;
			}

			return true;
		}


		if ($nipd) {
			$sql = 'select 1 from ' . $g_tab . ' where lower(nipd)=\'' . $nipd . '\' and soft_delete=0';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '214' );
				return false;
			}

			return true;
		}

		setErrorStatus2( $ret, '205' );
		return false;
	}

	function entitasRestore($data, $is_get_record = false) {
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

	function _entitasRestore($rec) {
		initRet( $rec );

		if (!isValidEntitasRestore( $ret, $rec )) {
			return $ret;
		}

		dbQuery( 'BEGIN' );
		pg_escape_string( strtolower( $rec['id_reg_pd'] ) );

		if ($id_reg_pd) {
			$where = 'id_reg_pd=\'' . $id_reg_pd . '\' and soft_delete=1';
		}

		$id_reg_pd = $ret = restoreEntitas( $where );

		if ($id_reg_pd) {
			dbQuery( 'COMMIT' );
			$ret['id_reg_pd'] = $id_reg_pd;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = $id_reg_pd = dbLastError(  );
		dbQuery( 'ROLLBACK' );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasRestore($ret, $rec) {
		if (!isValidInput( $ret, $rec )) {
			return false;
		}

		$id_reg_pd = strtolower( pg_escape_string( $rec['id_reg_pd'] ) );

		if ($id_reg_pd) {
			$sql = 'select 1 from reg_pd where id_reg_pd=\'' . $id_reg_pd . '\' and soft_delete=1';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '203' );
				return false;
			}

			return true;
		}

		return false;
	}

	function initRet($record) {
		$ret = array(  );
		$ret['id_reg_pd'] = $record['id_reg_pd'];
		$ret['nipd'] = $record['nipd'];
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function initRetAdd($record) {
		$ret = array(  );
		$ret['id_reg_pd'] = $record['id_reg_pd'];
		$ret['nipd'] = $record['nipd'];
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

	function addEntitas(&$rec, $sql) {
		$rec['id_reg_pd'] = getUUID(  );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = insertSQL( 'reg_pd', $rec );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $rec['id_reg_pd'];
		}

		return false;
	}

	function updateEntitas($data, $where) {
		$g_tab = thisTable(  );
		$sql = 'select id_reg_pd from ' . $g_tab . ' where ' . $where;
		$id_reg_pd = dbGetOne( $sql );
		$sql = updateSQL( $g_tab, $data, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_reg_pd;
		}

		return false;
	}

	function deleteEntitas($where) {
		$g_tab = thisTable(  );
		$rec = array(  );
		$sql = 'select id_reg_pd from ' . $g_tab . ' where ' . $where;
		$id_reg_pd = dbGetOne( $sql );
		$rec['soft_delete'] = 1;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( $g_tab, $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_reg_pd;
		}

		return false;
	}

	function restoreEntitas($where) {
		$g_tab = thisTable(  );
		$rec = array(  );
		$sql = 'select id_reg_pd from ' . $g_tab . ' where ' . $where;
		$id_reg_pd = dbGetOne( $sql );
		$rec['soft_delete'] = 0;
		$rec['id_updater'] = getIDUpdater(  );
		$rec['last_update'] = '{{now()}}';
		$sql = updateSQL( $g_tab, $rec, $where );
		$ok = dbQuery( $sql );

		if ($ok) {
			return $id_reg_pd;
		}

		return false;
	}

?>