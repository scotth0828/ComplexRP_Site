<?php

class DB {
	private static function connect() {
		try {
			$pdo = new PDO('mysql:host=127.0.0.1;dbname=accounts;charset=utf8', 'root', '');
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
}