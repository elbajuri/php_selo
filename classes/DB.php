<?php

class DB{

	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_results,
			$_count = 0;

	//create pdo connection
	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),
				Config::get('mysql/username'),Config::get('mysql/password'));

			
		}
		catch(PDOException $ex){
			die($ex->getMessage());
		}

	}

	public static function getInstance(){
		//cek koneksi 
		if(!isset(Self::$_instance)){
			Self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql, $params=array()){

		$this->_error = false;
		if($this->_query=$this->_pdo->prepare($sql)){
			$x=1;
			if(count($params)){
				foreach($params as $param){
					$this->_query->bindValue($x,$param);
					$x++;
				}
			}

			if($this->_query->execute()) {
				$this->_result = $this->_query->FetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();

			} else {
				$this->_error=true;

			}
		}
		return $this;
	}


	private function action($action, $table, $where = array()){

		if(count($where)===3){
			$operators = array('=','>','<','<=',">=");

			$field		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)){
				$sql = "{$action} from {$table} where {$field} {$operator} ?";
				print_r($sql);
				if(!$this->query($sql, array($value))->error()){
					return $this;
				}
			}
		}


		return $this;
	}

	public function get($table, $where){

	
		return $this->action('select *', $table, $where);
	}

	public function insert($table, $fields=array()){

		if(count($fields)){

			$keys = array_keys($fields);
			$value = null;
			$x=1;

			foreach($fields as $field){
				$value .= '?';

				if($x < count($fields)){
					$value .=',';
				}
				$x++;
			}
			$sql = "Insert into {$table} (`".implode('`, `', $keys)."`) values ({$value})";
			
			if(!$this->query($sql, $fields)->error()){

				return true;
			} 

		}
		return false;

	}

	public function update ($fields, $id, $table) {
		$set = '';
		$x=1;

		foreach ($fields as $name=> $value) {
				$set .= "{$name} = ?";
				if($x < count($fields)){
					$set .= ", ";
				}
				$x++;
			}

		$sql = "Update {$table} set {$set} where id = {$id} ";
		if(!$this->query($sql, $fields)->error()){

				return true;
			}
			return false;
		}
		
	public function getAll($table){

		return $this->query('Select * from '.$table);

	}

	public function delete($table, $where){

		return $this->action('DELETE',$table,$where);
	}

	public function result(){

		return $this->_result;
	}

	public function error(){
		return $this->_error;
	}

	public function count() {
		return $this->_count;
	}

}