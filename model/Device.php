<?php
namespace model;

class Device extends \reks\Model{
	
	
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	
	public function getDevices($room_id = null){
		$q = "
				SELECT devices.*,rooms.*, floors.*
				FROM devices
				INNER JOIN rooms ON devices.room_id = rooms.room_id
				INNER JOIN floors ON rooms.floor_id = floors.floor_id
				".($room_id ? ' WHERE devices.room_id=?' : '')."
				GROUP BY devices.device_code
				ORDER BY room_name ASC, device_name ASC
				";
		$args = array();
		if ($room_id)$args[] = $room_id;
		$rows = $this->db->select($q, $args);
		return $rows;
	}
	
	/**
	 * Loggable behaviour logging.
	 */
	public function callableAction(){
		$db = $this->db;

		return function(\X10Action $action) use($db){

			$db->insert('device_logs', array('device_code' => $action->getDevCode(), 'method' => $action->getAction(), 'command' => $action->getCommand(), 'ip_addr' => $_SERVER['REMOTE_ADDR'], 'args' => json_encode($action->getArgs())));	
			
			
		};
	}

	
	public function getDevice($device_code){

		$row = $this->db->selectRow("
				SELECT *
				FROM devices
				WHERE device_code=?",array($device_code));
		if (!$row)return null;
		// Get features as array aswell as bitwise.
		$row['features'] = array();
		foreach(\X10Device::getFeatures() as $flag => $readable){
			if ($flag & intval($row['device_flags']))$row['features'][] = $flag;
		}
		return $row;
	}
	
	public function deleteGroup($room_id){
		$this->db->pQuery("UPDATE macros SET room_id=NULL WHERE room_id=?",array($room_id));
		$devices = $this->db->select("SELECT * FROM devices WHERE room_id=?", array($room_id));
		foreach($devices as $dev){
			$this->delete($dev['device_code']);
		}
	}
	
	public function delete($device_code){
		$this->db->pQuery("DELETE FROM device_logs WHERE device_code=?",array($device_code));
		return $this->db->pQuery("DELETE FROM devices WHERE device_code=?", array($device_code));
	}
	
	public function create($room_id, $device_code, $power_device, $device_name, array $features, $desc = null){
		$data = array('room_id' => $room_id, 'powerline_communication' => $power_device, 'device_code' =>$device_code, 'device_name' => $device_name, 'device_desc' => $desc, 'device_flags' => $this->featureArrayToFlags($features));
		
		$this->db->insert('devices', $data);
		
		$dev = $this->getDevice($device_code);
		$id = $dev['device_code'];
		if (!$id)throw new \Exception("Error creating new device in device table.");
		
		return $id;
	}
	
	protected function featureArrayToFlags(array $features){
		$v = 0;
		foreach($features as $f){
			$v |= $f;
		}
		return $v;
	}
	
	
	public function update($room_id, $device_code, $new_devicecode, $power_device, $device_name, array $features, $desc=null){
		if ($device_code != $new_devicecode){
			// Delete log entries.
			$this->db->pQuery("DELETE FROM device_logs WHERE device_code=?",array($device_code));
		}
		$this->db->pQuery("
				UPDATE devices 
				SET 
				device_code=?, 
				device_name=?, 
				device_desc=?, 
				device_flags=?, 
				powerline_communication=?
				WHERE device_code=?", array(
						$new_devicecode,
						$device_name,
						$desc,
						$this->featureArrayToFlags($features),
						$power_device,
						$device_code
						));
	}
	
	
	public function deviceCodeAvailable($code){
		return !$this->db->selectOne("SELECT device_code FROM devices WHERE device_code = ?", array($code));
	}
	
}