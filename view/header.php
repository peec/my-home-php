<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Home Automation software for x10 products.">
	<meta name="author" content="Petter Kjelkenes, kjelkenes@gmail.com">
	<?php 
		// Use the Head class to add many style sheets.
		// If you change render() to compile(3600) all css files will be packed into one file.
		$view->head->css
		->add('bootstrap/css/bootstrap.min.css')
		->add('bootstrap/css/bootstrap-responsive.min.css')
		->add('js/jqueryui/css/ui-lightness/jquery-ui-1.8.21.custom.css')
		->add('style.css')
		->add('js/lou-multi-select/css/multi-select.css')
		->add('js/fileTree-1.0.1/jqueryFileTree.css')
		->render();
		
		$view->head->js
		->add('js/jqueryui/js/jquery-1.7.2.min.js')
		->add('js/jqueryui/js/jquery-ui-1.8.21.custom.min.js')
		->add('bootstrap/js/bootstrap.min.js')
		->add('js/lou-multi-select/js/jquery.multi-select.js')
		->add('x10.js')
		->add('js/fileTree-1.0.1/jqueryFileTree.js')
		->add('js/knockout-2.1.0.js')
		->render();
	?>
	<?php 
		// Some magic here, when using head class methods this will output all the data
		// like <title></title> tags , description, and etc... See Head class in the reks\view package.
		echo $view->head?>
	
	
</head>
<body>

	<div id="headwrap">
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container">

				<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>
				</a>

				<!-- Be sure to leave the brand out there if you want it shown -->
				<a class="brand" href="<?php echo $url->reverse('Main.index')?>"><?php echo $view->config['home_name']?></a>
				
				<!-- Everything you want hidden at 940px or less, place within here -->
				<div class="nav-collapse">
				<ul class="nav">
					<?php if ($view->config['modules']['x10']):?>
						<li class="active">
							<a href="<?php echo $url->reverse('DeviceControl.floors')?>">Device Control</a>
						</li>
						<li><a href="<?php echo $url->reverse('MacroHandler.index')?>">Macros</a></li>
						<li><a href="<?php echo $url->reverse('DeviceControl.scripts')?>">Automation</a></li>
					<?php endif?>
					<?php if ($view->config['modules']['boxee']):?>
						<li><a href="<?php echo $url->reverse('BoxeeBox.index')?>">Boxee</a></li>
					<?php endif;?>
				</ul>
				</div>

			</div>
		</div>
	</div>

	<header class="visible-desktop">
		<div class="container">
		<h1><?php echo $view->config['home_name']?></h1>
		</div>
	</header>
	
	</div>
	
	<div class="container" id="contentwrapper">
		<div class="row-fludi"><div id="globalmessages"></div></div>
		<div class="row-fluid">
		<?php if (isset($sidebar)):?>
		
			<div class="span3">
			<ul class="nav nav-list">
				<?php echo $sidebar?>
			</ul>
			</div>

			<div class="span9">
				<div class="row-fluid">
		<?php endif;?>
		
		<?php if (isset($breadcrumbs)):?>
		<ul class="breadcrumb">
			<?php foreach($breadcrumbs as $l => $n):?>
				<li><a href="<?php echo $l?>"><?php echo $n?></a> <span class="divider">/</span> </li>
			<?php endforeach?>
		</ul>
		<?php endif?>
		
		<?php if (isset($globalmacros) && count($globalmacros) > 0):?>
			<h3>Macros</h3>
			<div class="row-fluid macros">
			<div class="row-fluid">
			<?php foreach($globalmacros as $k => $macro):?>
				<?php echo $k % 6 == 0 ? '</div><div class="row-fluid">' : '';?>
				<div class="span2">
					<h5><?php echo $macro['macro_name']?> <a class="icon-question-sign" rel="tooltip" title="<?php echo $macro['macro_desc']?>"></a></h5>
					
					<div class="well">
					<?php $view->render('modal/scriptrunner', array('macroid' => $macro['macro_id'], 'button_name' => 'Run'))?>
					</div>
				</div>
			<?php endforeach?>
			</div>
			</div>
		<?php endif?>
		
		