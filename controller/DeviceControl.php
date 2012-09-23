<?php
namespace controller;

class DeviceControl extends Base{
	
	public function floors(){
		$this->view->assign('globalmacros', $this->model->Macro->getMacrosForGlobal());
		$this->view->assign('floors', $this->model->Floor->getFloors());
		$this->view->head->title('Floors');
		$this->view->render('floors');
	}
	
	public function floor($id){
		$this->view->assign('globalmacros', $this->model->Macro->getMacrosForFloor($id));
		$x10 = $this->getX10();
		$this->addDevices($x10);
		
		
		$floor = $this->model->Floor->getFloor($id);
		
		$rooms = $this->model->Room->getRooms($x10, $id);
		$this->view->assign('rooms', $rooms);
		
		$this->view->assign('floor', $floor);
		$this->view->head->title($floor['floor_name']);

		$this->view->assign('breadcrumbs', array(
				$this->url->reverse('DeviceControl.floors') => 'Floors',
				$this->url->reverse('DeviceControl.floor', array('id' => $floor['floor_id'])) => $floor['floor_name'],
				
				));
		
		$this->view->render('rooms');
	}
	
	public function deviceAjax(){
		$x10 = $this->getX10();
		$this->addDevices($x10);
		
		$code = $this->ui->post->device_code;
		switch($this->ui->post->type){
			case 'DIM':
				$val = (int)$this->ui->post->value;
				$x10->dev($code)->dim($val);
				$msg = "$code dimmed to $val.";
				
				echo $msg;
				break;
			case 'ON':
				$x10->dev($code)->on();
				$msg = "$code turned ON.";
				break;
			case 'OFF':
				$x10->dev($code)->off();
				$msg = "$code turned OFF.";
				break;
			default:
				$msg = "'{$this->ui->post->type}': No such type action implemented yet on $code.";
		}
		$this->log->info($msg);
		echo $msg;
	}
	
	public function scripts(){
		$this->view->head->title('Automation');
		$scripts = $this->model->Script->getScripts();
		
		$this->view->assign('scripts', $scripts);
		$this->view->assign('allDevices', $this->model->Device->getDevices());
		
		$this->view->render('scripts');
	}
	
	public function ajaxScript(){
		$ui = $this->ui->post;
		
		$x10 = $this->getX10();
		$this->addDevices($x10);
		$parser = new \com\ScriptParser($ui->script, $x10->getDevices());
		$checks = $parser->syntaxCheck($ui->script, $x10, $this->config, $this, $this->log);
		
		if ($checks == false){
			$new = false;			
			if ($scriptid = $ui->script_id){
				$this->model->Script->update($scriptid, $ui->script_name, $ui->script, $ui->active);
				
			}else{
				$scriptid = $this->model->Script->insert($ui->script_name, $ui->script, $ui->active);
				$new = true;
			}
			
			$data = $this->model->Script->getScript($scriptid);
			if ($new)$data['script_is_new'] = true;
			echo json_encode(array('message' => "Script [$scriptid] updated.", 'data' => $data));
		}else{
			
			header(' ', true, 404);
			
			echo json_encode(array('message' => 'Error saving script, please correct the syntax errors. Error: '.implode(' ', $checks)));

		}
		
		
	}
	public function deleteScript(){
		$id = $this->ui->get->itemid;
		$this->model->Script->delete($id);
	}
	public function ajaxFloor(){
		$ui = $this->ui->post;
		
		if($ui->remove){
			$this->model->Floor->delete($ui->floor_id);
			echo "Floor and its components deleted.";
			return;
		}
		
		
		if ($floorid = $ui->floor_id){
			$this->model->Floor->update($floorid, $ui->floor_name);
		}else{
			$floorid = $this->model->Floor->insert($ui->floor_name);
		}
		
		$data = $this->model->Floor->getFloor($floorid);
		echo json_encode(array('message' => "Floor [{$data['floor_name']}] updated.", 'data' => $data));
	}
	
	public function ajaxFloorsSort(){
		$floorItem = $this->ui->post->floorItem;
		
		foreach($floorItem as $pos => $id){
			$this->model->Floor->updatePosition($id, $pos);
		}
	}
	public function getScript($script_id = null){
		if ($script_id == null)$script_id = (int)$this->ui->get->scriptid;
		$script = $this->model->Script->getScript($script_id);
		echo json_encode($script);
	}
	
	public function getFloor($floor_id = null){
		if($floor_id == null)$floor_id = (int)$this->ui->post->floorid;
		echo json_encode($this->model->Floor->getFloor($floor_id));
	}
	public function getDevice($device_code = null){
		$this->getX10(); // Important , import X10.
		if($device_code == null)$device_code = $this->ui->post->device_code;
		echo json_encode($this->model->Device->getDevice($device_code));
	}
	public function getRoom($room_id = null){
		if($room_id == null)$room_id = $this->ui->post->room_id;
		echo json_encode($this->model->Room->getRoom($room_id));
	}
	
	public function deleteDevice(){
		$this->model->Device->delete($this->ui->post->device_code);
		echo "Deleted device.";
	}
	public function cuDevice(){
		$ui = $this->ui->post;
		$features = $ui->device_features;
		
		// Validate input...
		$x10 = $this->getX10();
		if (!$x10->isX10CodeValid($ui->device_code)){
			
			header(' ', true, 404);
				
			echo 'X10 code of device is not valid. Must be one character + one number. Example: a1, e2 etc.';
			return;
		}
		
		if (!$features || count($features) == 0){
			header(' ', true, 404);
			
			echo 'Atleast one device feature must be selected.';
			return;
		}
		
		if ($devicecode = $ui->old_device_code){
			$this->model->Device->update($ui->room_id, $devicecode, $ui->device_code, $ui->device_communication, $ui->device_name, $features, $ui->device_desc);
		}else{
			if (!$this->model->Device->deviceCodeAvailable($ui->device_code)){
				header(' ', true, 404);
					
				echo 'The device code '.$ui->device_code.' is used in another room.';
				return;
			}
			
			$devicecode = $this->model->Device->create($ui->room_id, $ui->device_code, $ui->device_communication, $ui->device_name, $features, $ui->device_desc);
		}
		
		$data = $this->model->Device->getDevice($ui->device_code);
		echo json_encode(array('message' => "Device [{$data['device_name']}] updated.", 'data' => $data));
	}
	
	
	public function ajaxRoom(){
		$ui = $this->ui->post;
		
		if($ui->remove){
			$this->model->Room->delete($ui->room_id);
			echo "Room and its components deleted.";
			return;
		}
		
		
		if ($roomid = $ui->room_id){
			$this->model->Room->update($roomid, $ui->floor_id, $ui->room_name, $ui->room_description);
		}else{
			$roomid = $this->model->Room->insert($ui->floor_id, $ui->room_name, $ui->room_description);
		}
		
		$data = $this->model->Room->getRoom($roomid);
		echo json_encode(array('message' => "Room [{$data['room_name']}] updated.", 'data' => $data));
	}
	
}