<?php
namespace model;

class IMDB extends \reks\Model{
public function __construct($modelwrapper){
		parent::__construct($modelwrapper);
		$this->openDB();
	}
	
	public function getInfo($path_hash, $name){
		$row = $this->db->selectRow("SELECT * FROM imdb WHERE path_hash=?",array(md5($path_hash)));
		if (!$row || (time() > strtotime($row['last_update'])+(60*60*24)) ){
			$m = new \com\boxee\helpers\MovieNameNormalizer($name);
			$readable = $m->parse();
			$readable['t'] = ucfirst($readable['t']);
			$data = json_encode((object)array('name' => $readable, 'imdb' => \com\boxee\helpers\IMDB::getInfo($readable['t'], $readable['y'])));
			if ($row)$this->delete($path_hash);
			
			$this->create($path_hash, $data);
			return $data;
		}
		
		
		
		$ret = $row['data'];
		return $ret;
	}
	
	
	public function create($path_hash, $data){
		return $this->db->insert('imdb', array(
				'data' => $data,
				'path_hash' => md5($path_hash)
				));
	}
	public function delete($path_hash){
		return $this->db->pQuery("DELETE FROM imdb WHERE path_hash=?", array(md5($path_hash)));	
	}
	
}