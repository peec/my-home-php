<?php
namespace model;


class Script extends \reks\Model{
	
	public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	
	public function getScripts(){
		return $this->db->select("SELECT * FROM scripts");
	}
	
	
	public function delete($id){
		return $this->db->pQuery("DELETE FROM scripts WHERE script_id=?", array($id));
	}
	
	public function insert($name, $script, $active){
		return $this->db->insert('scripts', array('script_name' => $name, 'script' => $script, 'active' => $active));
	}
	public function update($scriptid, $name, $script, $active){
		$this->db->pQuery("UPDATE scripts SET script_name=?, script=?, active=? WHERE script_id=?", array($name, $script, $active, $scriptid));
	}
	public function getScript($scriptId){
		return $this->db->selectRow('SELECT * FROM scripts WHERE script_id=?', array($scriptId));
	}
}