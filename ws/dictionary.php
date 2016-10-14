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

	function dictionary($table) {
		$table = strtolower( $table );
		$columns = array(  );
		$tables = listReferensi(  );
		$is_ref = array_key_exists( $table, $tables );
		$tables = listData(  );
		$is_data = array_key_exists( $table, $tables );
		$schema = false;

		if ($is_ref) {
			$schema = 'ref';
		}


		if ($is_data) {
			$schema = 'public';
		}


		if (!$schema) {
			return false;
		}

		$table = tableMap( $table );

		if ($table == 'peserta_didik') {
			$rows = getRows( $schema, $table );
			setColumns( $rows, $columns );
			$rows = getRows( $schema, 'reg_pd' );
			setColumns( $rows, $columns, 'regpd_' );
			return $columns;
		}

		$rows = getRows( $schema, $table );
		setColumns( $rows, $columns );
		return $columns;
	}

	function getRows($schema, $table) {
		$sql = 'select t.table_schema, t.table_name,t.column_name,t.data_type ,tc.column_name as pk, t.column_default, t.is_nullable,
            t.character_maximum_length, t.numeric_precision, t.numeric_scale, r.val, r2.val as val2
			from information_schema.columns t 
			left join INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE  tc 
			on tc.TABLE_NAME=t.TABLE_NAME and tc.COLUMN_NAME=t.column_name and tc.constraint_name like \'pk%\'
			left join ref.dictionary r on t.column_name=r.pk
			left join ref.dictionary r2 on t.table_name || \'.\' || t.column_name=r2.pk
			where t.table_schema = \'' . $schema . '\' and t.table_name = \'' . $table . '\'
			order by t.table_name, t.ordinal_position';
		global $conn_str_sandbox;

		$conn = pg_connect( $conn_str_sandbox );
		$result = pg_query( $conn, $sql );
		$records = array(  );

		if ($row = pg_fetch_assoc( $result )) {
			$records[] = $row;
		}

		return $records;
	}

	function setColumns(&$rows, $columns, $prefix = '') {
		$exception_columns = array( 'create_date', 'last_update', 'expired_date', 'last_sync', 'id_updater', 'soft_delete' );
		foreach ($rows as $row) {

			if (in_array( $row['column_name'], $exception_columns )) {
				continue;
			}

			$columns[$prefix . $row['column_name']]['column_name'] = $prefix . $row['column_name'];

			if ($row['pk']) {
				$columns[$prefix . $row['column_name']]['pk'] = 1;
			}

			$columns[$prefix . $row['column_name']]['type'] = $row['data_type'];

			if ($row['character_maximum_length']) {
				$columns[$prefix . $row['column_name']]['type'] = $row['data_type'] . '(' . $row['character_maximum_length'] . ')';
			}


			if ($row['data_type'] == 'numeric') {
				$columns[$prefix . $row['column_name']]['type'] = $row['data_type'] . '(' . $row['numeric_precision'] . ',' . $row['numeric_scale'] . ')';
			}


			if ($row['is_nullable'] == 'NO') {
				$columns[$prefix . $row['column_name']]['not_null'] = 1;
			}


			if ($row['column_default']) {
				$columns[$prefix . $row['column_name']]['default'] = $row['column_default'];
			}

			$columns[$prefix . $row['column_name']]['desc'] = $row['val'];

			if ($row['val2']) {
				$columns[$prefix . $row['column_name']]['desc'] = $row['val2'];
			}


			if (( $row['data_type'] == 'date' && $row['val'] == '' )) {
				$columns[$prefix . $row['column_name']]['desc'] = 'yyyy-mm-dd';
				continue;
			}
		}

	}

?>