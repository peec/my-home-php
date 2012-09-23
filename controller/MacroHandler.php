<?php
namespace controller;

class MacroHandler extends Base{
	
	
	public function index(){
		$this->view->head->title('Macros');
		$this->view->assign('macros', $this->model->Macro->getMacros());
		$this->view->assign('floors', $this->model->Floor->getFloors());
		$this->view->assign('rooms', $this->model->Room->getGlobalRooms());
		
		$this->view->assign('allDevices', $this->model->Device->getDevices());
		
		
		$this->view->render('macros');
	}
	
	public function getMacro($id = null){
		if($id == null)$id = $this->ui->get->macroid;
		echo json_encode($this->model->Macro->getMacro($id));
	}
	
	public function deleteMacro(){
		$id = $this->ui->get->itemid;
		$this->model->Macro->delete($id);
	}

	public function ajaxMacro(){
		$ui = $this->ui->post;
		
		$x10 = $this->getX10();
		$this->addDevices($x10);
		
		try{
			$parser = new \com\ScriptParser($ui->macro, $x10->getDevices());
			$checks = $parser->syntaxCheck($ui->macro, $x10, $this->config, $this, $this->log);
			if ($checks)throw new \Exception("There is an error in your macro: ".implode(',', $checks));
			
			$floor_id = $ui->floor_id ? $ui->floor_id : null;
			$room_id = $ui->room_id ? $ui->room_id : null;
				
			if ($scriptid = $ui->macro_id){
				
				$this->model->Macro->update($scriptid, $ui->macro_name, $ui->macro_desc, $ui->macro, $floor_id, $room_id);
				
			}else{
				$scriptid = $this->model->Macro->insert($ui->macro_name, $ui->macro_desc, $ui->macro, $floor_id, $room_id);
			}
			
			
			echo json_encode(array('message' => "Macro [$scriptid] updated.", 'data' => $this->model->Macro->getMacro($scriptid)));
		}catch(\Exception $e){
			
			header(' ', true, 404);
			
			echo $e->getMessage();
		}
		
		
	}
	
	public function scriptHandler(){
		// Important step.
		set_time_limit(0);
		$x10 = $this->getX10();
		$this->addDevices($x10);
		
		
		
		$macroid = $this->ui->post->macroid;
		$scriptid = $this->ui->post->scriptid;
		if ($macroid){
			$macro = $this->model->Macro->getMacro($macroid);
			$script = $macro['macro'];
		}elseif($scriptid){
			$script = $this->model->Script->getScript($scriptid);
			$script = $script['script'];
		}else{
			$script = $this->ui->post->script;
		}
		
		
		if (!$script){
			header(' ', true, 404);
			echo "No script to execute.";
			return;
		}
		
		
		
		$time_start = microtime(true);
		
		try{
			$parser = new \com\ScriptParser($script, $x10->getDevices());
			
			$checks = $parser->syntaxCheck($script, $x10, $this->config, $this, $this->log);
			if ($checks)throw new \Exception("There is an error in your script: ".implode(',', $checks));
			
			$parser->run($x10, $this->config, $this, $this->log);
			
			
			
			$time_end = microtime(true);
			$execution_time = ($time_end - $time_start);
			
			echo "Success ($execution_time s).";
		}catch(\Exception $e){
			header(' ', true, 404);
			$msg = "Script error running macro ...: " . $e->getMessage();
			echo $msg;
		}
		
	}
	
	
	
	
}