<?php

class Lava {
	function __construct($db, $table) {
		$this->db = $db;
		$this->table = $table;
	}

	function _escape(&$fields) {
		foreach($fields as $key => $value) {
			$safeKey = "`" . str_replace("`", "", $key) . "`";
			$safeValue = $this->db->quote($value);

			unset($fields[$key]);
			$fields[$safeKey] = $safeValue;
		}
	}

	function _where($fields) {
		$query = " WHERE ";
		$args = array();

		foreach($fields as $key => $value) {
			array_push($args, "$key = $value");
		}

		$query .= implode(" AND ", $args);

		return $query;
	}

	function _set($fields) {
		$query = " SET ";
		$args = array();

		foreach($fields as $key => $value) {
			array_push($args, "$key = $value");
		}

		$query .= implode(", ", $args);

		return $query;
	}

	function execute($query) {
		$t = $this->db->prepare($query);
		$t->execute();
		return $t->fetchAll(PDO::FETCH_ASSOC);
	}

	function find($fields = array()) {
		$query = "SELECT * FROM " . $this->table;
		$this->_escape($fields);

		if(count($fields) > 0) {
			$query .= $this->_where($fields);
		}

		return $this->execute($query);
	}

	function insert($fields = array()) {
		$query = "INSERT INTO " . $this->table;
		$this->_escape($fields);

		$query .= "(" . implode(", ", array_keys($fields)) . ")";
		$query .= " VALUES ";
		$query .= "(" . implode(", ", array_values($fields)) . ")";

		return $this->execute($query);
	}

	function update($actions, $conditions) {
		$query = "UPDATE " . $this->table;
		$this->_escape($actions);
		$this->_escape($conditions);

		$query .= $this->_set($actions);
		$query .= $this->_where($conditions);

		return $this->execute($query);
	}
}
