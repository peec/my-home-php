<?php 





$config['modules_available'] = array(

		'x10' => array(
				'name' => 'X10 Home Automation',
				'info' => 'A fully CMS for your X10 (Home automation) components.',
		),
		'boxee' => array(
				'name' => 'BoxeeBox remote',
				'info' => 'A super advanced remote for your boxeebox. It can browse files and find external imdb data of movies. Support for multiple boxeeboxes.'
		)

);


// Automatic override.
if (!isset($config['modules'])){
	
	$config['modules'] = array(
			'x10' => true,
			'boxee' => true
			
			);
}


