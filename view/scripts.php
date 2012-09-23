<?php $view->render('header');?>
<?php $view->render('modal/scriptinghelp')?>

<div class="row-fluid">
	<div class="span6">
		<div class="hero-unit">
			<h1>Automation</h1>
			<p>Create awesome automation with the automation feature. Turn devices off/on or dim them at specific timestamps, based on weather or more.</p>
			<p><a class="btn btn-primary btn-large" href="#script_form" id="new_script">New script</a></p>
		</div>
	</div>
	<div class="span6">
		
		<?php if (!file_exists($view->app->APP_PATH . '/cache/x10cronjob') || filemtime($view->app->APP_PATH . '/cache/x10cronjob') < time()-(2*60*60)):?>
			<h2>One-time Installation</h2>
			<div class="alert alert-error">We could not see jobs running the past 2 hours. Please setup a CRON or sheduled task. Instructions are below.</div>
			
			<?php $cronurl = 'http://127.0.0.1'.$url->reverse('Cron.runnable');?>
			<h3>Linux</h3>
			<p>Add this code into the crontab file.</p>
			<pre class="prettyprint linenums">0 * * * * wget -O /dev/null <?php echo $cronurl?></pre>
			<h3>Windows</h3>
			<p>Run this command once and your set to go.</p>
			<pre class="prettyprint linenums">schtasks /create /tn "X10 PHP Cronjob" /tr "\"C:\Program Files (x86)\Mozilla Firefox\firefox.exe\" <?php echo $cronurl?>" /sc hourly</pre>
			<p>PS! Change <strong>C:\Program Files (x86)\Mozilla Firefox\firefox.exe</strong> with the path to any webbrowser exe file.</p>
		<?php else: ?>
			<h2>Install done</h2>
			<div class="alert alert-info">Cron tasks seems to be running fine.</div>
		<?php endif?>

	</div>
</div>

<div class="row-fluid">
	<div class="span3">
		<div class="well">
		<ul class="nav nav-list" id="script_listing">
			<li class="nav-header">Scripts</li>
			<?php foreach($scripts as $script):?>
				<li><a href="#" data-scriptid="<?php echo $script['script_id']?>"><i class="icon-book"></i><?php echo $script['script_name']?></a></li>
			<?php endforeach?>
		</ul>
		</div>
		<div id="formstatus">
			
		</div>
	</div>
	<div class="span9">
		<form method="post" class="form-horizontal" id="script_form">
			<input type="hidden" name="script_id" id="script_id" value="" />
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label" for="script_name">Scriptname</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="script_name" name="script_name" />
							<p class="help-block">A describing name for your script.</p>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group deleteItem"  style="display: none;">
						<label class="control-label">Delete</label>
						<div class="controls">
							<div class="well">
								<a href="#" data-itemid="" class="btn btn-danger">Delete</a>
								<p class="help-block">This will permanently delete this script.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
			<div class="span6">
			<div class="control-group">
				<label class="control-label" for="script">Script</label>
				<div class="controls">
					<textarea class="scriptContents input-xlarge" id="script" name="script" rows="15"></textarea>
					<p class="help-block">Your script, written in PHP and our own language. <?php btn_scriptinghelp();?></p>
					
				</div>
			</div>
			</div>
			<div class="span6">
				 <?php $view->render('modal/scriptrunner', array('formid' => 'script_form'))?>
				 <?php $view->render('modal/devlist')?>
			</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="script_active">Script active</label>
				<div class="controls">
					<label class="checkbox">
					<input type="checkbox" id="script_active" name="active" checked="checked" value="1">Are your script active? Do you want it to be run automatically every hour?
					</label>
				</div>
			</div>
			
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){

	$('#script_form').submit(function(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $url->reverse('DeviceControl.ajaxScript')?>',
			data: $(this).serialize(),
			success: function(data){
				delaymessage('success', $('#formstatus'), data.message);
				updateScriptData(data.data);
				
			},
			error: function(data){
				delaymessage('error', $('#formstatus'), $.parseJSON(data.responseText).message);
			},
			dataType: 'json'
		});

		return false;
	});

	$('#script_listing a').click(function(){
		var id = $(this).data('scriptid');
		$.getJSON('<?php echo $url->reverse('DeviceControl.getScript')?>?scriptid=' + id, function(data){
			updateScriptData(data);
			$('.deleteItem').show();
			delaymessage('info', $('#formstatus'), data.script_name + ' is up for editing.');
		});
	});

	$('.deleteItem a').click(function(){
		var id = $(this).data('itemid');
		$.get('<?php echo $url->reverse('DeviceControl.deleteScript')?>?itemid=' + id, function(data){
			location.reload();
		});
	});
	
	function updateScriptData(data){
		$('.deleteItem').find('a').data('itemid',data.script_id);
		$('#script_name').val(data.script_name);
		$('#script_id').val(data.script_id);
		$('#script').val(data.script);
		$('#script_active').attr('checked', data.active);
	}

	
	$('#new_script').click(function(){
		$('.deleteItem').hide();
		updateScriptData({script_name: '', script_id: '', script: '', active: true});
		delaymessage('info', $('#formstatus'), 'Start creating your new script.');
	});


});

</script>


<?php $view->render('footer');?>