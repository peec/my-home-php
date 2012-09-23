<?php
namespace model;

class Macro extends \reks\Model{
	
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	public function getMacro($id){
		return $this->db->selectRow("SELECT * FROM macros WHERE macro_id=?",array($id));
	}
	
	
	public function getMacros(){
		return $this->db->select("SELECT * FROM macros ORDER BY macro_name ASC");
	}
	
	public function getMacrosForFloor($id){
		return $this->db->select("SELECT * FROM macros WHERE floor_id=?", array($id));
	}
	public function getMacrosForRoom($id){
		return $this->db->select("SELECT * FROM macros WHERE room_id=?", array($id));
	}
	public function getMacrosForGlobal(){
		return $this->db->select("SELECT * FROM macros WHERE room_id IS NULL AND floor_id IS NULL");
	}
	
	
	
	public function delete($id){
		return $this->db->pQuery("DELETE FROM macros WHERE macro_id=?", array($id));
	}
	public function insert($name, $desc, $macro, $floor, $room){
		$prep = $this->db->prepare("
				INSERT INTO `macros` (macro_name, macro_desc, macro, floor_id, room_id) 
				VALUES(?,?,?,?,?)");
		$prep->bindParam(1, $name, \PDO::PARAM_STR);
		$prep->bindParam(2, $desc, \PDO::PARAM_STR);
		$prep->bindParam(3, $macro);
		$prep->bindParam(4, $floor);
		$prep->bindParam(5, $room);
		
		if ($prep->execute()){
			return $this->db->lastInsertId();
		}
		
		throw new \Exception("Unable to insert macro with $name, $desc, $macro, $floor, $room.");
	}
	public function update($macro_id, $name, $desc, $macro, $floor, $room){
		$prep = $this->db->prepare("
				UPDATE `macros` 
				SET 
				macro_name=?, 
				macro_desc=?, 
				macro=?, 
				floor_id=?, 
				room_id=? 
				WHERE macro_id=?");
		$prep->bindParam(1, $name, \PDO::PARAM_STR);
		$prep->bindParam(2, $desc, \PDO::PARAM_STR);
		$prep->bindParam(3, $macro, \PDO::PARAM_STR);
		$prep->bindParam(4, $floor, \PDO::PARAM_INT);
		$prep->bindParam(5, $room, \PDO::PARAM_INT);
		$prep->bindParam(6, $macro_id, \PDO::PARAM_INT);
		$e = $prep->execute();
		return $e;
	}

	
}