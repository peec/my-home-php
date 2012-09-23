<?php
namespace controller;

class Main extends Base{
	
	public function index(){
		
		$this->view->head->title('Homepage');
		$imagePath = $this->app->PUBLIC_PATH . '/img/house/';
		$this->addFormListener('imageForm', 'post', function($ui) use($imagePath){
			$bits = explode('.', $_FILES['image']['name']);
			$extension =  end($bits);
				
			move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath . md5($_FILES["image"]["name"]).'.'.$extension);
		});

		$images = scandir($imagePath);

		foreach($images as $k => $f){
			if ($f == '.' || $f == '..')unset($images[$k]);
		}
		$images = array_values($images);
		
		$this->view->assign('images', $images);
		$this->view->assign('devices', $this->model->Device->getDevices());
		// Render a view file.
		$this->view->render('index');
	}
	
	
}