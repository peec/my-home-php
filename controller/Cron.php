<?php
namespace controller;


class Cron extends Base{
	
	public function runnable(){
		set_time_limit(0);
		
		
		
		$scripts = $this->model->Script->getScripts();
		
		$x10 = $this->getX10();
		
		$this->addDevices($x10);
		
		
		foreach($scripts as $script){
			try{
				$parser = new \com\ScriptParser($script['script'], $x10->getDevices());
				$parser->run($x10, $this->config, $this, $this->log);
				$this->log->info("Script {$script['script_name']} was runned.");
			}catch(\Exception $e){
				$msg = "Script error running script {$script['script_name']} ...: " . $e->getMessage();
				echo $msg,"\n";
				$this->log->info($msg);
			}
		}
		

		touch($this->app->APP_PATH . '/cache/x10cronjob');
	}
	
}