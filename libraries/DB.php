<?php

class DB {
	private static function connect() {
		try {
			$pdo = new PDO('mysql:host=127.0.0.1;dbname=eventhorizon;charset=utf8', 'root', '');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $pdo;
		} catch (PDOException $e) {
			return NULL;
		}
	}

	public static function query($query, $params = array()) {
		$pdo = self::connect();

		if ($pdo != NULL) {
			$statement = $pdo->prepare($query);
			$statement->execute($params);

			if (explode(' ', $query)[0] == 'SELECT') {
				$data = $statement->fetchAll();
				return $data;
			}
		}
		return NULL;
	}

	public static function getData($table, $value, $identifier, $identifiervalue) {
		$identifiers = array();

		$v = '*';
		if ($value != '') $v = $value;
		$q = 'SELECT ' . $v . ' FROM ' . $table . ' WHERE ';

		for ($i = 2; $i < func_num_args(); $i+=2) {
			if ($i == 2) {
				$q = $q . func_get_arg($i) . '=:' . func_get_arg($i);
			} else {
				$q = $q . ' AND ' . func_get_arg($i) . '=:' . func_get_arg($i);
			}

			$identifiers[':' . func_get_arg($i)] = func_get_arg($i+1);
		}

		$query = self::query($q, $identifiers);

		if (!count($query))
			return NULL;

		if (count($query) == 1) {
			if ($v == '*')
				return $query[0];
			else
				return $query[0][$v];
		} else {
			return $query;
		}

	}

	public static function updateData($table, $data, $identifier, $identifiervalue) {
		if (count($data) < 1) return false;

		$q = 'UPDATE ' . $table . ' SET ';

		$identifiers = array();

		foreach ($data as $key => $value) {
			if (count($data) <= 1)
				$q = $q . $key . '=:' . $key;
			else
				if ($data[$key] == $value && end($data) == $value)
					$q = $q . $key . '=:' . $key;
				else
					$q = $q . $key . '=:' . $key . ', ';

			$identifiers[':'.$key] = $value;
		}

		$q = $q . ' WHERE ';

		

		for ($i = 2; $i < func_num_args(); $i+=2) {
			if ($i == 2) {
				$q = $q . func_get_arg($i) . '=:' . func_get_arg($i);
			} else {
				$q = $q . ' AND ' . func_get_arg($i) . '=:' . func_get_arg($i);
			}

			$identifiers[':' . func_get_arg($i)] = func_get_arg($i+1);
		}

		self::query($q, $identifiers);

		return true;
	}

	public static function setData($table, $data, $primarykey = true) {
		$q = '';
		if ($primarykey) 
			$q = 'INSERT INTO ' . $table . ' VALUES ' . '(\'\', ';
		else
			$q = 'INSERT INTO ' . $table . ' VALUES ' . '(';

		$a = array();

		foreach ($data as $key => $value) {
			if (count($data) <= 1)
				$q = $q . ':' . $key . ')';
			else
				if ($data[$key] == $value && end($data) == $value)
					$q = $q . ' :' . $key . ')';
				else
					$q = $q . ':' . $key . ', ';

			$a[$key] = $value;
		}
		self::query($q, $a);
	}

	public static function deleteData($table, $identifier, $identifiervalue) {
		$identifiers = array();

		$q = 'DELETE FROM ' . $table . ' WHERE ';

		for ($i = 1; $i < func_num_args(); $i+=2) {
			if ($i == 1) {
				$q = $q . func_get_arg($i) . '=:' . func_get_arg($i);
			} else {
				$q = $q . ' AND ' . func_get_arg($i) . '=:' . func_get_arg($i);
			}

			$identifiers[':' . func_get_arg($i)] = func_get_arg($i+1);
		}

		

		self::query($q, $identifiers);
	}

	public static function createLoginTokensTable() {
		try {
			self::query('CREATE TABLE logintokens (
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				value VARCHAR(255) NOT NULL,
				user_id INT(6) NOT NULL 
		)');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function createUserTable() {
		try {
			self::query('CREATE TABLE accounts (
			id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			RegistrationDate TIMESTAMP NOT NULL,
			Username VARCHAR(32) NOT NULL,
			Password VARCHAR(60) NOT NULL,
			Email TEXT NOT NULL,
			Role VARCHAR(32) NOT NULL,
			BirthDate DATE NOT NULL,
			Verified TINYINT(1) NOT NULL,
			ProfileImg VARCHAR(255)

			)');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function createFollowerTable() {
		try {
			self::query(
				'CREATE TABLE followers (
				user_id INT(6) NOT NULL,
				follower_id INT(6) NOT NULL,
				blocked INT(1) NOT NULL
			)');
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}