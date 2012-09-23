<?php
namespace model;

class Boxee extends \reks\Model{
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	public function getBoxee($boxee_id){
		return $this->db->selectRow("SELECT * FROM boxee WHERE boxee_id=?",array($boxee_id));
	}
	public function getBoxees(){
		return $this->db->select("SELECT * FROM boxee");
	}
	
	public function create($boxee_name, $host){
		return $this->db->insert('boxee', array(
				'identity' => 'COMP_'.uniqid (rand(), true),
				'host' => $host,
				'app_id' => 'REM_'.uniqid (rand(), true),
				'boxee_name' => $boxee_name
				));
	}
	public function update($id, $boxee_name, $host){
		return $this->db->pQuery("
				UPDATE boxee SET 
				boxee_name=?,
				host=?
				WHERE boxee_id=?
				", array($boxee_name, $host, $id));
	}
	public function delete($boxee_id){
		return $this->db->pQuery("DELETE FROM boxee WHERE boxee_id=?",array($boxee_id));
	}
	
}