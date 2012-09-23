<?php
namespace controller;


class BoxeeBox extends Base{
	
	
	public function setup(){
		parent::setup();
		
		$boxies = $this->model->Boxee->getBoxees();
		$this->view->assign('boxees', $boxies);
		
		$cur = $this->model->Boxee->getBoxee($this->ui->session->use_boxee);		
		if ($this->ui->session->use_boxee == null || !$cur){
			if (isset($boxies[0]))$this->ui->session->use_boxee = $boxies[0]['boxee_id'];
		}
		
		
		if ($this->ui->session->use_media == null){
			$this->ui->session->use_media = \com\boxee\service\Files::T_VIDEO;
		}
	}
	
	
	public function index(){
		
		$this->view->head->title('Boxee Remote Control');
		$this->view->render('boxee/template', array('file' => 'index'));
	}
	
	public function files(){
		$this->view->head->title('Boxee files');
		$this->view->render('boxee/template', array('file' => 'files'));
	}
	
	public function pair(){
		$this->view->head->title('Configure boxee');
		$this->view->render('boxee/template', array('file' => 'pair'));
	}
	
	
	
	public function dirlisting(){
		$dir = urldecode($this->ui->post->dir);
		$media = $this->ui->session->use_media;
		
		$b = $this->getBoxFromSession();
		
		
		try{
		
			echo '<ul class="jqueryFileTree" style="display: none;">';
			if ($dir == '/'){	
				$files = $b->files()->GetSources($media);
				if (isset($files->result->shares) && count($files->result->shares) > 0){		
					foreach($files->result->shares as $file) {
						echo '<li class="directory collapsed"><a href="#" rel="'.$file->file.'">'.$file->label.'</a></li>';
					}
				}
			}else{
				$files = $b->files()->GetDirectory($media, $dir);
				if (isset($files->result->files) && count($files->result->files) > 0){
					foreach($files->result->files as $file) {
						if($file->filetype == 'directory'){
							echo '<li class="directory collapsed"><a href="#" rel="'.$file->file.'">'.$file->label.'</a></li>';
						}else{
							$parts=explode('.',$file->file);
							$ext = $parts[count($parts)-1];
							echo '<li class="file ext_'.$ext.'"><a href="#" rel="'.$file->file.'">'.$file->label.'</a></li>';
						}
					}
				}
			}
			echo "</ul>";
			
		}catch(\Exception $e){
			header(' ', true, 404);
			echo $e->getMessage();
		}
	}
	
	public function setUseBoxee(){
		$this->ui->session->use_boxee = $this->ui->post->use_boxee;
	}
	public function setUseMedia($media){
		$_SESSION['use_media'] = $media;
		echo "$media is set.";
	}
	
	
	public function remote(){
		
		$player = false;
		$this->view->head->title("Boxee Remote");
		try{
			$b = $this->getBoxFromSession();
			$player = $b->player();
		}catch(\Exception $e){
			
		}
		
		$this->view->assign('player', $player);
		$this->view->render('boxee/template', array('file' => 'remote'));
	}
	
	
	public function imdbapi(){
		$post = $this->ui->post;
		$name = $post->name;
		$file = $post->file;
		
		echo $this->model->IMDB->getInfo($file, $name);
	}
	
	public function ajaxBoxee(){
		$post = $this->ui->post;
		
		$id = $post->boxee_id;
		if ($post->remove){
			$this->model->Boxee->delete($id);
			return;
		}
		if ($post->get){
			echo json_encode($this->model->Boxee->getBoxee($id));
			return;
		}
		if ($id){
			$this->model->Boxee->update($id, $post->boxee_name, $post->host);
		}else{
			$id = $this->model->Boxee->create($post->boxee_name, $post->host);
		}
		$data = $this->model->Boxee->getBoxee($id);
		echo json_encode($data);
	}
	
	
	/**
	 * Ajax commands can be sent here.
	 * Uses only raw command for simple usage of javascript.
	 * Pass:
	 * boxee_id ( id in database )
	 * method ( Namespace.method )
	 * args ( array )
	 */
	public function command(){
		$post = $this->ui->post;
		
		
		$id = $post->boxee_id ? $post->boxee_id : $this->ui->session->use_boxee;
		
		$data = $this->model->Boxee->getBoxee($id);
		
		$boxee = new \com\boxee\Boxee($data['host'], $data['identity']);
		
		$post->args = $post->args ? $post->args : null;
		
		try{
			
			$parts = explode('.', $post->method);
			if ($post->method == 'pair'){		
				
				$ret = $boxee->device()->PairChallenge($data['boxee_name'], $data['app_id']);
			}elseif($post->method == 'confirmPair'){
				$ret = $boxee->device()->PairResponse($post->args);
			}elseif($parts[0] == 'Player'){
				if ($player = $boxee->player()){
					if (!($player instanceof \com\boxee\service\Player))throw new \Exception("Audio/Video player is not active.");
					
					$ret = call_user_func_array(array($player, $parts[1]), (array)$post->args);
				}else{
					throw new \Exception("Audio / Video player functions is not available because these players are not active.");
				}
				
			}else{
				$ret = $boxee->raw($post->method, $post->args != false  ? $post->args : null);
			}
			echo json_encode($ret);
		}catch(\Exception $e){
			header(' ', true, 404);
			echo $e->getMessage();
		}
	}
	
	/**
	 * @return com\boxee\Boxee
	 */
	public function getBoxFromSession(){
		$data = $this->model->Boxee->getBoxee($this->ui->session->use_boxee);	
		$boxee = new \com\boxee\Boxee($data['host'], $data['identity']);
		return $boxee;
	}
}