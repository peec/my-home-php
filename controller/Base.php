<?php
namespace controller;

class Base extends \reks\Controller{
	
	
	// Intercept install.
	public function setup(){
		
		if(!$this->isInstalled()){
			
			if (!$this->ui->post->db_host){
				$version = new VersionChecks($this);
				
				$version->php(5300)->configWritable()->logsWritable()->cacheWritable();
	
				$this->addFormListener('installForm', 'post', function($ui){
					
				});
				
				
				$this->view->assign('version', $version);
				$this->view->head->title('Install');
				$this->view->render('install');
				die();
			}
		}
		
	}
	
	public function installDB(){
		if (!$this->isInstalled()){
			$config = $this->config;
			$ui = $this->ui->post;
			
			$config['db']['dsn'] = "mysql:dbname={$ui->db_name};host={$ui->db_host}";
			$config['db']['username'] = $ui->db_username;
			$config['db']['password'] = $ui->db_password;
			$config['db']['driver_options'] = array();
			
			// Test connection.
			
			try {
				$dbh = new \PDO($config['db']['dsn'], $config['db']['username'], $config['db']['password']);
				
				if (!$state = $dbh->query(file_get_contents($this->app->APP_PATH.'/db/tables.sql')))throw new \Exception("Unable to import /db/tables.sql to database.");
			}catch (\PDOException $e){
				header(' ', true, 404);
				echo "Could not connect to the database. (#{$e->getCode()}) Error: ".$e->getMessage();
				die();
			}catch(\Exception $e){
				header(' ', true, 404);
				echo $e->getMessage();
				die();
			}
			
			
			$this->writeconf($config);
			
					
			
			echo "Successfully created database, imported sample data.";
			
		}
	}
	
	protected function writeconf($config){
		$cfg = var_export($config, true);
		file_put_contents($this->app->APP_PATH.'/cache/autoconfig.php', "<?php /* File written at ".date('y/m/d')." by installer. To override these configurations use the /config.php file and override config after the include of this file. */ \$config = $cfg;");
	}
	public function installWriteConfig(){
		if (!$this->isInstalled()){
			
			$config = $this->config;
			$ui = $this->ui->post;
			
			$config['home_name'] = $ui->home_name;
				
			
			foreach($config['modules'] as $k => $v){
				if (isset($ui->modules[$k]))$config['modules'][$k] = true;
				else $config['modules'][$k] = false;
			}
			
			if ($config['modules']['x10']){
				$config['sdk_binary'] = $ui->x10_executable;
				
				if (!file_exists($config['sdk_binary']) || !is_executable($config['sdk_binary'])){
					header(' ', true, 404);
					echo "Could not find the X10 API executable or it's not executable.";
					die();
				}
				
			}
			
			$this->writeconf($config);
			file_put_contents($this->app->APP_PATH.'/cache/APP_INSTALLED', "Installed at ".date('r').". Remove this file to reinstall the database and the application. ");
			
			echo "OK. Install completed.";
		}
	}
	
	
	
	public function isInstalled(){
		return file_exists($this->app->APP_PATH.'/cache/APP_INSTALLED');
	}
	
	
	
	/**
	 * Creates a new X10 object.
	 * @return X10
	 */
	protected function getX10(){
		include_once $this->app->APP_PATH.'/com/x10/bootstrap.php';
		$x10 =  new \X10($this->config['sdk_binary']);
		$x10->setCallback($this->model->Device->callableAction());
		return $x10;
	}
	
	protected function addDevices(\X10 $x10){
		$devices = $this->model->Device->getDevices();
		foreach($devices as $dev){
			if ($dev['powerline_communication'])$x10->addPowerDevice($dev['device_code']);
			else $x10->addRadioDevice($dev['device_code']);

			// Add allowance flags.
			$x10->dev($dev['device_code'])->setAllowedFeatures(intval($dev['device_flags']));
		}
	}
}


class VersionChecks{
	
	private $checks = array();
	
	private $con;

	public function __construct(\reks\Controller $con){
		$this->con = $con;
	}
	
	/**
	 * Checks php version is more or equal to .
	 * Example $this->php(5300);
	 * @param int $check
	 */
	public function php($check){
		if (!defined('PHP_VERSION_ID')) {
			$version = explode('.', PHP_VERSION);
		
			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}
		$this->checks[__FUNCTION__] = PHP_VERSION_ID >= $check;
		
		return $this;
	}
	
	
	public function cacheWritable(){
		$this->checks[__FUNCTION__] = is_writable($this->con->app->APP_PATH.'/cache');
		return $this;
	}
	public function logsWritable(){
		$this->checks[__FUNCTION__] = is_writable($this->con->app->APP_PATH.'/logs');
		return $this;
	}
	
	
	
	public function configWritable(){
		$this->checks[__FUNCTION__] = is_writable($this->con->app->APP_PATH.'/config.php');
		return $this;
	}
	
	public function getResults(){
		return $this->checks;
	}
	public function getResult($method){
		return $this->checks[$method];
	}
	
	
	public function hasFailed(){
		foreach($this->checks as $check){
			if (!$check)return true;
		}
		return false;
	}
	


	
	
}