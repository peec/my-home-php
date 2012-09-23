<?php
namespace model;

class DeviceLog extends \reks\Model{

	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	


	public function delete($logs_id){
		$this->db->pQuery("DELETE FROM device_logs WHERE log_id=?",array($logs_id));
	}

	public function getEntry($log_id){
		return $this->db->selectRow("
				SELECT * FROM device_logs
				INNER JOIN devices ON device_logs.device_code = devices.device_code
				WHERE log_id=?
				", array($log_id));
	}
	
	public function getList($page, $howmany = 30){
		$items = $page * $howmany;
		$rows =  
		$this->db->select("
		SELECT * FROM device_logs
			INNER JOIN devices ON device_logs.device_code = devices.device_code
			ORDER BY created_at DESC
		LIMIT $items,$howmany
		");
		
		// Add human readable action.
		
		foreach($rows as $k => $row){
			
			$rows[$k]['timeago'] = $this->relativeTime(strtotime($row['created_at']));
			
			
			$readable = 'Device was ';
			
			$exp = explode('::', $row['method']);
			
			$args = json_decode($row['args']);
			switch($exp[1]){
				case 'dim':
					$readable .= vsprintf('dimmed to %s', $args);
					break;
				case 'on':
					$readable .= 'turned on'; break;
				case 'off':
					$readable .= 'turned off'; break;
				case 'bright':
					$readable .= vsprintf('brightend to %s', $args); break;
				case 'extendedCode':
					$readable .= vsprintf('sent an extended code with values %s - %s', $args);
				case 'allUnitsOff':
					$readable = 'All units was turned off'; break;
				case 'allLightsOn':
					$readable = 'All lights was turned on'; break;
					
				default:
					$readable = $row['method'];
			}
			
			
			$rows[$k]['readable_action'] = $readable;
			
		}
		
		
		
		return $rows;
	}

	protected function relativeTime($timestamp){
		$difference = time() - $timestamp;
		$periods = array("sec", "min", "hour", "day", "week",
				"month", "years", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");

		if ($difference > 0) { // this was in the past
			$ending = "ago";
		} else { // this was in the future
			$difference = -$difference;
			$ending = "to go";
		}
		for($j = 0; $difference >= $lengths[$j]; $j++)
			$difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] $ending";
		return $text;
	}


}