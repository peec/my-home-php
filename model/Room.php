<?php
namespace model;

class Room extends \reks\Model{
	
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	
	public function getRooms(\X10 $x10, $floor_id){
		
		$rooms =  $this->db->select("SELECT * FROM rooms
				INNER JOIN `floors` ON floors.floor_id = rooms.floor_id
				WHERE rooms.floor_id = ?", array($floor_id));
		foreach($rooms as $k => $room){
			$rooms[$k]['devices'] = $this->model->Device->getDevices($room['room_id']);
			$rooms[$k]['macros'] = $this->model->Macro->getMacrosForRoom($room['room_id']);
			foreach($rooms[$k]['devices'] as $dk => $device){
				$rooms[$k]['devices'][$dk]['x10'] = $x10->dev($device['device_code']);
			}	
		}
		return $rooms;
	}
	
	public function getRoom($id){
		return $this->db->selectRow("SELECT * FROM rooms WHERE room_id=?",array($id));
	}
	
	public function getAllRooms($floor_id){
		return $this->db->select('SELECT * FROM rooms WHERE floor_id=?', array($floor_id));
	}
	
	
	public function getGlobalRooms(){
		return $this->db->select('SELECT * FROM rooms');
	}
	
	public function delete($room_id){
		$this->model->Device->deleteGroup($room_id);
		return $this->db->pQuery("DELETE FROM rooms WHERE room_id=?", array($room_id));
	}
	
	
	public function update($room_id, $floor_id, $name, $desc){
		return $this->db->pQuery("UPDATE rooms SET floor_id=?,room_name=?, room_description=? WHERE room_id=?", array($floor_id, $name, $desc, $room_id));
	}
	
	public function insert($floor_id, $name, $desc){
		return $this->db->insert('rooms', array('floor_id' => $floor_id, 'room_name' => $name, 'room_description' => $desc));
	}
	
	
	
	
}