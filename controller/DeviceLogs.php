<?php
namespace controller;

class DeviceLogs extends Base{
	
	public function getList(){
		$this->view->render('logs');
	}
	
	public function jsonList(){
		
		$page = $this->ui->post->page;
		
		$logs = $this->model->DeviceLog->getList($page, 30);
		
		if (empty($logs)){
			header(' ', true, 404);
			return;
		}
		
		echo json_encode($logs);
	}
	
	
	public function runEntry(){
		$log_id = $this->ui->post->log_id;
		
		$entry = $this->model->DeviceLog->getEntry($log_id);
		
		if (!$entry){
			header(' ', true, 404);
			return;
		}
		try{
			$x10 = $this->getX10();
			$this->addDevices($x10);
			
			$action = $entry['method'];
			$args = json_decode($entry['args']);
			
			$dev = $x10->dev($entry['device_code']);
			
			// Call it.
			call_user_func_array(array($dev, $action), $args);
			
			
		}catch(\Exception $e){
			header(' ', true, 404);
			return;
		}
	}
	
}

