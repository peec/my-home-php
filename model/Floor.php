<?php
namespace model;

class Floor extends \reks\Model{
	
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	
	public function getFloors(){
		return $this->db->select("SELECT * FROM floors ORDER BY floor_pos ASC");
	}
	
	public function getFloor($id){
		return $this->db->selectRow("SELECT * FROM floors WHERE floor_id=?", array($id));
	}
	
	public function delete($id){
		$rooms = $this->model->Room->getAllRooms($id);
		$this->db->pQuery("UPDATE macros SET floor_id=NULL WHERE floor_id=?", array($id));
		foreach($rooms as $room){
			$this->model->Room->delete($room['room_id']);
		}
		
		return $this->db->pQuery("DELETE FROM floors WHERE floor_id=?", array($id));
	}
	public function insert($name){
		return $this->db->insert('floors', array('floor_name' => $name));
	}
	public function update($floorid, $name){
		$this->db->pQuery("UPDATE floors SET floor_name=? WHERE floor_id=?", array($name, $floorid));
	}
	
	public function updatePosition($id, $pos){
		$this->db->pQuery("UPDATE floors SET floor_pos=? WHERE floor_id=?", array($pos, $id));
	}
	
}