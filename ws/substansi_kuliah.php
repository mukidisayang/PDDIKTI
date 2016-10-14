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
		return 'substansi_kuliah';
	}

	function thisPK() {
		return 'id_subst';
	}

	function globalKeys() {
		$g_keys = array( 'id_subst' );
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
		initRetAdd( $record );

		if (!isValidEntitasAdd( $ret, $record )) {
			return $ret;
		}

		$new_pk = addEntitas( $record );

		if ($new_pk) {
			$ret[$pk] = $new_pk;
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = $ret = dbLastError(  );
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
			$ret[$pk] = $key[$pk];
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function _entitasDelete($record) {
		$pk = thisPK(  );
		$ret = initRet( $record );

		if (!isValidEntitasDelete( $ret, $record )) {
			return $ret;
		}

		$id = escapeLower( $record[$pk] );

		if ($id) {
			$where = '' . $pk . '=\'' . $id . '\' and soft_delete=0';
		}

		$ok = deleteEntitas( $where );

		if ($ok) {
			$ret[$pk] = $record[$pk];
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function _entitasRestore($record) {
		$pk = thisPK(  );
		$ret = initRet( $record );

		if (!isValidEntitasRestore( $ret, $record )) {
			return $ret;
		}

		$id = escapeLower( $record[$pk] );

		if ($id) {
			$where = '' . $pk . '=\'' . $id . '\' and soft_delete=1';
		}

		$ok = restoreEntitas( $where );

		if ($ok) {
			$ret[$pk] = $record[$pk];
			setErrorStatus2( $ret, '0' );
			return $ret;
		}

		$error = dbLastError(  );
		setErrorStatus2( $ret, '103' );
		$ret->error_desc .= $error;
		return $ret;
	}

	function isValidEntitasAdd($ret, $record) {
		$g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		escapeLower( $record['nm_subst'] );
		escapeLower( $record['id_sms'] );
		$id_jns_subst = $id_sms = $nm_subst = escapeLower( $record['id_jns_subst'] );

		if (!$nm_subst) {
			setErrorStatus2( $ret, '901' );
			return false;
		}

		$sql = 'select 1 from ' . $g_tab . ' 
		where lower(nm_subst)=\'' . $nm_subst . '\' 
			and id_sms=\'' . $id_sms . '\' 
			and lower(id_jns_subst)=\'' . $id_jns_subst . '\' 
			and soft_delete=0';

		if (dbGetOne( $sql )) {
			setErrorStatus2( $ret, '900' );
			return false;
		}

		return true;
	}

	function isValidEntitasUpdate($ret, $where) {
		$g_tab = thisTable(  );
		$sql = 'select count(*) from ' . $g_tab . ' where ' . $where;
		$num = dbGetOne( $sql );

		if (!$num) {
			setErrorStatus2( $ret, '903' );
			return false;
		}


		if (1 < $num) {
			setErrorStatus2( $ret, '904' );
			return false;
		}

		return true;
	}

	function isValidEntitasDelete($ret, $record) {
		$pk = $g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		escapeLower( $record[$pk] );
		$id = thisPK(  );

		if ($id) {
			$sql = 'select 1 from ' . $g_tab . ' where ' . $pk . '=\'' . $id . '\' and soft_delete=0';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '906' );
				return false;
			}

			return true;
		}

		setErrorStatus2( $ret, '906' );
		return false;
	}

	function isValidEntitasRestore($ret, $record) {
		$pk = $g_tab = thisTable(  );

		if (!isValidInput( $ret, $record )) {
			return false;
		}

		escapeLower( $record[$pk] );
		$id = thisPK(  );

		if ($id) {
			$sql = 'select 1 from ' . $g_tab . ' where ' . $pk . '=\'' . $id . '\' and soft_delete=1';

			if (!dbGetOne( $sql )) {
				setErrorStatus2( $ret, '903' );
				return false;
			}

			return true;
		}

		setErrorStatus2( $ret, '903' );
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