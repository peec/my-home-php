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
		->render();
		
		$view->head->js
		->add('js/jqueryui/js/jquery-1.7.2.min.js')
		->add('js/jqueryui/js/jquery-ui-1.8.21.custom.min.js')
		->add('bootstrap/js/bootstrap.min.js')
		->add('js/lou-multi-select/js/jquery.multi-select.js')
		->add('x10.js')
		->render();
	?>
	<?php 
		// Some magic here, when using head class methods this will output all the data
		// like <title></title> tags , description, and etc... See Head class in the reks\view package.
		echo $view->head?>
	
	
</head>
<body>
	<?php 
	function versiondisplay($version){
		if ($version === true)return 'success';
		elseif($version === null)return 'unknown';
		else return 'fail';
	}
	
	?>
	
	<div class="container">
		<h1>Install</h1>
		
		<div class="well">
			<p>Welcome to the installation of this software. Please follow the simple steps to install.</p>
		</div>
		
		
		<?php echo $form = $view->form->create('installForm')->attr('class','form-horizontal')?>
		
		
		
		<div class="row-fluid">
			<div class="span6">
				<div id="step1">
					<h4>Mysql info</h4>
					<div class="control-group">
						<label class="control-label" for="db_host">Host</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="db_host" name="db_host" value="localhost" />
							<p class="help-block">Your database host, normally its localhost or 127.0.0.1</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="db_name">Database name</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="db_name" name="db_name" value="myhome" />
							<p class="help-block">You will have to create the database that we will import the tables to. Open up PhpMyAdmin or any other db client to create the database.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="db_username">Database username</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="db_username" name="db_username" value="root" />
							<p class="help-block">The database username.</p>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="db_password">Database password</label>
						<div class="controls">
							<input type="password" class="input-xlarge" id="db_password" name="db_password" value="" />
							<p class="help-block">The database password.</p>
						</div>
					</div>
				
					<div class="form-actions">
						<a href="#" class="btn-large btn-primary">Continue to Step 2</a>
					</div>
				</div>
				
				
				<div id="step2" style="display: none;">
				
					<h4>Features</h4>
					<p>Select the type of features you want to have enabled. Notice each feature requires some type of hardware.</p>
					
					<?php foreach($view->config['modules_available'] as $key => $val):?>
						<div class="control-group modules">
							<label class="control-label"><?php echo $val['name']?></label>
							<div class="controls">
								<input type="checkbox" data-module="<?php echo $key?>" name="modules[<?php echo $key?>]" value="1" checked="checked" />
								<p class="help-block"><?php echo $val['info']?></p>
							</div>
						</div>
					<?php endforeach?>
					
					<h4>Specific settings</h4>
					<div class="control-group x10_settings">
						<label class="control-label" for="x10_executable">X10 Executable</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="x10_executable" name="x10_executable" value="C:\ahsdk\bin\ahcmd.exe" />
							<p class="help-block">Path to where the <a href="http://kbase.x10.com/wiki/Activehome_Pro_SDK" rel="external">Active Home Pro</a> ahcmd.exe file. If you have not installed Active Home Pro, please install it.</p>
						</div>
					</div>
					
					<h5>Global settings</h5>
					<div class="control-group">
						<label class="control-label" for="home_name">Name of your website</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="home_name" name="home_name" value="My Home" />
							<p class="help-block">A reansonable name for your website.</p>
						</div>
					</div>
					<div class="form-actions">
						<a href="#" class="btn-large btn-primary">Finish</a>
					</div>
				</div>
				
				<div id="complete" style="display: none;">
					<h4>Congratulations!</h4>
					<p>You have installed the software, you can now go to your <a href="<?php echo $url->reverse('Main.index')?>">main page</a> and start customizing your home. Good luck!</p>
				</div>
				
			</div>
			
			
			
			<div class="span4">
				<h3>Requirements</h3>
				<table class="versiontable table table-striped table-bordered table-condensed">
					<tr>
						<th>Config file writable</th>
						<td><span class="<?php echo versiondisplay($version->getResult('configWritable')) ?>"></span></td>
					</tr>
					<tr>
						<th>Cache folder writable</th>
						<td><span class="<?php echo versiondisplay($version->getResult('cacheWritable')) ?>"></span></td>
					</tr>
					<tr>
						<th>Logs folder writable</th>
						<td><span class="<?php echo versiondisplay($version->getResult('logsWritable')) ?>"></span></td>
					</tr>
					<tr>
						<th>PHP 5.3+</th>
						<td><span class="<?php echo versiondisplay($version->getResult('php')) ?>"></span></td>
					</tr>
					
					<tr>
						<th>MySQL 4.0+</th>
						<td><span id="mysqlVersionCheck"></span></td>
					</tr>
				</table>
				<div id="globalmessages"></div>
			</div>
		</div>
		
		
		
	
		<?php echo $form->close()?>
	</div>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('#step1 .form-actions a').live('click', function(){
			$.ajax({
				  type: 'POST',
				  url: '<?php echo $url->reverse('Base.installDB')?>',
				  data: $('#installForm').serialize(),
				  success: function(data){
					  $('#mysqlVersionCheck').attr('class','success');
					  $('#step1').hide();

					  $('#step2').fadeIn();

					  
					  globalmessage('success', data);
				  },
				  error: function(data){
					  globalmessage('error', data.responseText);
				  }
			});	

			return false;
		});
		$('#step2 .form-actions a').live('click', function(){
			$.ajax({
				  type: 'POST',
				  url: '<?php echo $url->reverse('Base.installWriteConfig')?>',
				  data: $('#installForm').serialize(),
				  success: function(data){

					  $('#step2').hide();

					  $('#complete').fadeIn();
					  globalmessage('success', data);
				  },
				  error: function(data){
					  globalmessage('error', data.responseText);
				  }
			});	

			return false;
		});


		$('.modules input').click(function(){
			var box = $('.' + $(this).data('module') + '_settings');
			if ($(this).is(':checked')){
				box.show();
			}else{
				box.hide();
			}
			
		});
	});	
	</script>
	
</body>
</html>