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

	function GetToken($username, $password) {
		global $conn_str_live;

		$username = pg_escape_string( $username );
		sha1( $password );
		$password = ;
		$conn = pg_connect( $conn_str_live );
		$sql = 'select id_pengguna from man_akses.pengguna where username=\'' . $username . '\' and password=\'' . $password . '\'';
		$result = pg_query( $conn, $sql );

		if ($row = pg_fetch_row( $result )) {
			$id_pengguna = $row[0];
		}


		if ($id_pengguna) {
			$name = md5( time(  ) . rand( 10 ) );
			$file = 'token_' . $name;
			file_put_contents( '../application/logs/' . $file, $id_pengguna );
			return $name;
		}

		return 'ERROR: username/password salah';
	}

	function GetRecord($token, $table, $filter = '') {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$list_ref = listReferensi(  );

		if (array_key_exists( $table, $list_ref )) {
			$table = 'ref.' . $table;
			$result = _getRecord( $table, $filter, '', 1 );
			return retTrue( $result );
		}

		$sql = getSQL( $table );

		if ($sql) {
			$result = _getRecord( '', $filter, '', 1, $sql, '', true );
			return retTrue( $result );
		}

		$list_data = listData(  );

		if (array_key_exists( $table, $list_data )) {
			$result = _getRecord( $table, $filter, '', 1 );
			return retTrue( $result );
		}

		$ret = array(  );
		setErrorStatus( $ret, '102' );
		return $ret;
	}

		$ret = function GetRecordset($token, $table, $filter = '', $order = '', $limit = 0, $offset = 0) {;

		if ($ret !== true) {
			return $ret;
		}

		$inner_filter = '';
		listReferensi(  );
		$list_ref = isValidWS( $token );

		if (array_key_exists( $table, $list_ref )) {
			$table = 'ref.' . $table;
			$result = _getRecordset( $table, $filter, $order, $limit, $offset );
			return retTrue( $result );
		}

		$sql = getSQL( $table );

		if ($sql) {
			$result = _getRecordset( '', $filter, $order, $limit, $offset, $sql, $inner_filter, true );
			return retTrue( $result );
		}

		$list_data = listData(  );

		if (array_key_exists( $table, $list_data )) {
			$table = $inner_filter .= ' soft_delete=0 ';
			_getRecordset( $table, $filter, $order, $limit, $offset, '', $inner_filter );
			$result = tableMap( $table );
			return retTrue( $result );
		}

		$ret = array(  );
		setErrorStatus( $ret, '102' );
		return $ret;
	}

	function GetDeletedRecordset($token, $table, $filter = '', $order = '', $limit = 0, $offset = 0) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$inner_filter = '';
		$list_ref = listReferensi(  );

		if (array_key_exists( $table, $list_ref )) {
			$table = 'ref.' . $table;
			$result = _getRecordset( $table, $filter, $order, $limit, $offset );
			return retTrue( $result );
		}

		$sql = getSQL( $table, false, true );

		if ($sql) {
			$result = _getRecordset( '', $filter, $order, $limit, $offset, $sql, $inner_filter, true );
			return retTrue( $result );
		}

		$list_data = listData(  );

		if (array_key_exists( $table, $list_data )) {
			$inner_filter .= ' soft_delete=1 ';
			$table = tableMap( $table );
			$result = _getRecordset( $table, $filter, $order, $limit, $offset, '', $inner_filter );
			return retTrue( $result );
		}

		$ret = array(  );
		setErrorStatus( $ret, '102' );
		return $ret;
	}

	function GetCountRecordset($token, $table) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$list_ref = listReferensi(  );

		if (array_key_exists( $table, $list_ref )) {
			$table = 'ref.' . $table;
			$sql = 'select count(*) from ' . $table;
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$sql = getSQL( $table, true );

		if (( $sql && strpos( $sql, 'from' ) )) {
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$list_data = listData(  );

		if (array_key_exists( $table, $list_data )) {
			$table = tableMap( $table );
			$sql = 'select count(*) from ' . $table;
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$ret = array(  );
		setErrorStatus( $ret, '102' );
		return $ret;
	}

	function GetCountDeletedRecordset($token, $table) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$list_ref = listReferensi(  );

		if (array_key_exists( $table, $list_ref )) {
			$table = 'ref.' . $table;
			$sql = 'select count(*) from ' . $table . ' where soft_delete=1';
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$sql = getSQL( $table, true, true );

		if (( $sql && strpos( $sql, 'from' ) )) {
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$list_data = listData(  );

		if (array_key_exists( $table, $list_data )) {
			$table = tableMap( $table );
			$sql = 'select count(*) from ' . $table . ' where soft_delete=1';
			$result = dbGetOne( $sql );
			return retTrue( $result );
		}

		$ret = array(  );
		setErrorStatus( $ret, '102' );
		return $ret;
	}

	function InsertRecord($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (( $table == 'dosen' || $table == 'dosen_pt' )) {
			setErrorStatus( $ret, '300' );
			return $ret;
		}


		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}

		$result = entitasAdd( $data, true );
		return retTrue( $result );
	}

	function InsertRecordset($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (( $table == 'dosen' || $table == 'dosen_pt' )) {
			setErrorStatus( $ret, '300' );
			return $ret;
		}


		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}

		$result = entitasAdd( $data );
		return retTrue( $result );
	}

	function UpdateRecord($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			$ret = array(  );
			setErrorStatus( $ret, '102' );
			return $ret;
		}


		if ($table == 'dosen_pt') {
			setErrorStatus( $ret, '302' );
			return $ret;
		}

		$result = entitasUpdate( $data, true );
		return retTrue( $result );
	}

	function UpdateRecordset($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}


		if ($table == 'dosen_pt') {
			setErrorStatus( $ret, '302' );
			return $ret;
		}

		$result = entitasUpdate( $data );
		return retTrue( $result );
	}

	function DeleteRecord($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}


		if (( $table == 'dosen' || $table == 'dosen_pt' )) {
			setErrorStatus( $ret, '301' );
			return $ret;
		}

		$result = entitasDelete( $data, true );
		return retTrue( $result );
	}

	function DeleteRecordset($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}


		if (( $table == 'dosen' || $table == 'dosen_pt' )) {
			setErrorStatus( $ret, '301' );
			return $ret;
		}

		$result = entitasDelete( $data );
		return retTrue( $result );
	}

	function RestoreRecord($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}

		$result = entitasRestore( $data, true );
		return retTrue( $result );
	}

	function RestoreRecordset($token, $table, $data) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$ret = array(  );

		if (!includeTable( $table )) {
			setErrorStatus( $ret, '102' );
			return $ret;
		}

		$result = entitasRestore( $data );
		return retTrue( $result );
	}

	function GetDictionary($token, $table) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$result = dictionary( $table );

		if (( !count( $result ) || !$result )) {
			$ret = array(  );
			setErrorStatus( $ret, '102' );
			return $ret;
		}

		return retTrue( $result );
	}

	function ListTable($token) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		listReferensi(  );
		$list_ref = $tables = array(  );
		foreach ($list_ref as ) {
			[0];
			[1];
			 = $desc = $table;
			$tables[] = array( 'table' => $table, 'jenis' => 'Ref', 'keterangan' => $desc );
		}

		$list_data = listData(  );
		foreach ($list_data as ) {
			[0];
			[1];
			 = $desc = $table;
			$tables[] = array( 'table' => $table, 'jenis' => 'Data', 'keterangan' => $desc );
		}

		return retTrue( $tables );
	}

	function CheckDeveloperMode($token) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}


		if (file_exists( '../application/logs/sandbox' )) {
			return retTrue( 1 );
		}

		return retTrue( 0 );
	}

	function GetVersion($token) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		return retTrue( '1.1' );
	}

	function GetExpired($token) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$expired = expired(  );
		return retTrue( $expired );
	}

	function GetChangeLog($token) {
		$ret = isValidWS( $token );

		if ($ret !== true) {
			return $ret;
		}

		$log = changeLog(  );
		return retTrue( $log );
	}

	$g_token = '';
	$server = new soap_server(  );
	$server->configureWSDL( 'WSPDDIKTI' );
	$server->wsdl->addComplexType( 'ArrayOfRecord', 'complexType', 'array', 'sequence' );
	$server->wsdl->addComplexType( 'ReturnSet', 'complexType', 'struct', 'all', '', array( 'error_code' => 'xsd:int', 'error_desc' => 'xsd:string', 'result' => 'tns:ArrayOfRecord' ) );
	$server->register( 'GetToken', array( 'username' => 'xsd:string', 'password' => 'xsd:string' ), array( 'output' => 'xsd:string' ), '', '', '', '', 'Mendapatkan token' );
	$server->register( 'ListTable', array( 'token' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan daftar table yang dipakai di GetRecordset, GetCountRecordset, InsertRecordset, UpdateRecordset, DeleteRecordset dan GetDictionary' );
	$server->register( 'GetDictionary', array( 'token' => 'xsd:string', 'table' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan dictionary dari suatu table' );
	$server->register( 'GetRecord', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'filter' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan satu buah record dari sebuah table' );
	$server->register( 'GetRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'filter' => 'xsd:string', 'order' => 'xsd:string', 'limit' => 'xsd:integer', 'offset' => 'xsd:integer' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan recordset dari sebuah table' );
	$server->register( 'GetDeletedRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'filter' => 'xsd:string', 'order' => 'xsd:string', 'limit' => 'xsd:integer', 'offset' => 'xsd:integer' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan recordset yang dihapus dari sebuah table' );
	$server->register( 'GetCountRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan jumlah recordset dari sebuah table' );
	$server->register( 'GetCountDeletedRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mendapatkan jumlah recordset yang dihapus dari sebuah table' );
	$server->register( 'InsertRecord', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Memasukkan data ke dalam table' );
	$server->register( 'InsertRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Memasukkan data ke dalam table lebih dari 1 record' );
	$server->register( 'UpdateRecord', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mengubah data dari suatu table' );
	$server->register( 'UpdateRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mengubah data dari suatu table lebih dari 1 record' );
	$server->register( 'DeleteRecord', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Menghapus data dari suatu table' );
	$server->register( 'DeleteRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Menghapus data dari suatu table lebih dari 1 record' );
	$server->register( 'RestoreRecord', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mengembalikan data yang dihapus dari suatu table (belum sinkronisasi)' );
	$server->register( 'RestoreRecordset', array( 'token' => 'xsd:string', 'table' => 'xsd:string', 'data' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Mengembalikan data yang dihapus dari suatu table lebih dari 1 record (belum sinkronisasi)' );
	$server->register( 'CheckDeveloperMode', array( 'token' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Melihat status developer mode saat ini. 0 = Live, 1 = Developer Mode ' );
	$server->register( 'GetVersion', array( 'token' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Melihat versi webservice saat ini.' );
	$server->register( 'GetExpired', array( 'token' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Melihat tanggal expired web serivce.' );
	$server->register( 'GetChangeLog', array( 'token' => 'xsd:string' ), array( 'output' => 'tns:ReturnSet' ), '', '', '', '', 'Melihat log perubahan web service.' );
	$HTTP_RAW_POST_DATA = (isset( $HTTP_RAW_POST_DATA ) ? $HTTP_RAW_POST_DATA : '');
	$server->service( $HTTP_RAW_POST_DATA );
?>