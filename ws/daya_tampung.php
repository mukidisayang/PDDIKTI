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
		return 'daya_tampung';
	}

	function thisPK() {
		return array( 'id_smt', 'id_sms' );
	}

	function globalKeys() {
		$g_keys = array( 'id_smt', 'id_sms' );
		return $g_keys;
	}

	function entitasAdd($data, $is_get_record = false) {
		return gEntitasAdd( $data, $is_get_record );
	}

	function entitasUpdate($data, $is_get_record = false) {
		return gEntitasUpdate( $data, $is_get_record );
	}

	function entitasDelete($data, $is_get_record = false) {
		return gEntitasDelete( $data, $is_get_record );
	}

	function entitasRestore($data, $is_get_record = false) {
		return gEntitasRestore( $data, $is_get_record );
	}

	function _entitasAdd($record) {
		$pk = thisPK(  );
		$ret = initRetAdd( $record );

		if (!isValidEntitasAdd( $ret, $record )) {
			return $ret;
		}

		$new_pk = ;

		if ($new_pk) {
			foreach ($pk as $k) {
				$ret[$k] = $new_pk[$k];
			}

			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		dbLastError(  );
		$error = addEntitas( $record );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function _entitasUpdate($key, $data) {
		$pk = thisPK(  );
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

		$ok = updateEntitas( $data, $where );

		if ($ok) {
			foreach ($pk as $k) {
				$ret[$k] = $key[$k];
			}

			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function _entitasDelete($record) {
		$ret = $pk = thisPK(  );

		if (!isValidEntitasDelete( $ret, $record )) {
			return $ret;
		}

		$where = '';
		foreach ($pk as $k) {
			$id = escapeLower( $record[$k] );
			$where .= '' . $k . '=\'' . $id . '\' and ';
		}

		$where .= ' soft_delete=0';
		$ok = deleteEntitas( $where );

		if ($ok) {
			foreach ($pk as $k) {
				$ret[$k] = $record[$k];
			}

			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		dbLastError(  );
		$error = initRet( $record );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function _entitasRestore($record) {
		$ret = $pk = thisPK(  );

		if (!isValidEntitasRestore( $ret, $record )) {
			return $ret;
		}

		$where = '';
		foreach ($pk as $k) {
			$id = escapeLower( $record[$k] );
			$where .= '' . $k . '=\'' . $id . '\' and ';
		}

		$where .= ' soft_delete=1';
		$ok = restoreEntitas( $where );

		if ($ok) {
			foreach ($pk as $k) {
				$ret[$k] = $record[$k];
			}

			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		dbLastError(  );
		$error = initRet( $record );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasAdd($ret, $record) {
		$g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		$id_smt = escapeLower( $record['id_smt'] );
		$id_sms = escapeLower( $record['id_sms'] );

		if (( !$id_smt || !$id_sms )) {
			setErrorStatus2( $ret, '734' );
			return false;
		}

		$sql = 'select 1 from ' . $g_tab . ' 
		where lower(id_smt)=\'' . $id_smt . '\' 
			and id_sms=\'' . $id_sms . '\' 
			and soft_delete=0';

		if (dbGetOne( $sql )) {
			setErrorStatus2( $ret, '960' );
			return false;
		}

		return true;
	}

	function isValidEntitasUpdate($ret, $where) {
		$g_tab = thisTable(  );
		$sql = 'select count(*) from ' . $g_tab . ' where ' . $where;
		$num = dbGetOne( $sql );

		if (!$num) {
			setErrorStatus2( $ret, '732' );
			return false;
		}


		if (1 < $num) {
			setErrorStatus2( $ret, '733' );
			return false;
		}

		return true;
	}

	function isValidEntitasDelete($ret, $record) {
		$g_tab = thisTable(  );
		$pk = thisPK(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		$where = '';
		$i = 273;
		foreach ($pk as $k) {
			++$i;
			$id = escapeLower( $record[$k] );
			$where .= '' . $k . '=\'' . $id . '\' and ';
		}

		$where .= ' soft_delete=0 ';

		if ($i) {
			$sql = 'select 1 from ' . $g_tab . ' where ' . $where . ' ';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '736' );
				return false;
			}

			return true;
		}

		setErrorStatus2( $ret, '736' );
		return false;
	}

	function isValidEntitasRestore($ret, $record) {
		$g_tab = thisTable(  );
		$pk = thisPK(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		$where = '';
		$i = 272;
		foreach ($pk as $k) {
			++$i;
			$id = escapeLower( $record[$k] );
			$where .= '' . $k . '=\'' . $id . '\' and ';
		}

		$where .= ' soft_delete=1 ';

		if ($i) {
			$sql = 'select 1 from ' . $g_tab . ' where ' . $where;

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '732' );
				return false;
			}

			return true;
		}

		setErrorStatus2( $ret, '732' );
		return false;
	}

	function initRet($record) {
		$pk = thisPK(  );
		$ret = array(  );
		$ret[$pk] = $record[$pk];
		$ret['error_code'] = '0';
		$ret['error_desc'] = '';
		return $ret;
	}

	function initRetAdd($record) {
		$pk = thisPK(  );
		$ret = array(  );
		$ret[$pk] = $record[$pk];
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

	function addEntitas($rec) {
		return gAdd( $rec );
	}

	function updateEntitas($data, $where) {
		return gUpdate( $data, $where );
	}

	function deleteEntitas($where) {
		return gDelete( $where );
	}

	function restoreEntitas($where) {
		return gRestore( $where );
	}

?>