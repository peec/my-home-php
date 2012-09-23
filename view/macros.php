<?php $view->render('header');?>
<?php $view->render('modal/scriptinghelp')?>

<div class="row-fluid">
		<div class="hero-unit">
			<h1>Macros</h1>
			<p>Macros are created so you can create awesome effects with just one button. Forexample, you might want a macro button that simply turns off all devices available. Also more advanced macros like dimming lights and turning on appliances based on a simple click.</p>
			<p><a class="btn btn-primary btn-large" href="#macro_form" id="new_macro">New macro</a></p>
		</div>
</div>

<div class="row-fluid">
	<div class="span3">
		<div class="well">
		<ul class="nav nav-list" id="script_listing">
			<li class="nav-header">Macros</li>
			<?php foreach($macros as $macro):?>
				<li><a href="#" data-macroid="<?php echo $macro['macro_id']?>"><i class="icon-book"></i><?php echo $macro['macro_name']?></a></li>
			<?php endforeach?>
		</ul>
		</div>
		<div id="formstatus">
			
		</div>
	</div>
	<div class="span9">
		<form method="post" class="form-horizontal" id="macro_form">
			<input type="hidden" name="macro_id" id="macro_id" value="" />
			<div class="control-group">
				<label class="control-label" for="macro_name">Macro name</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="macro_name" name="macro_name" />
					<p class="help-block">A short name for your macro.</p>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label" for="macro_desc">Macro Description</label>
						<div class="controls">
							<textarea class="input-xlarge" id="macro_desc" name="macro_desc" rows="10"></textarea>
							<p class="help-block">A good description of your macro.</p>
						</div>
					</div>
				</div>
				<div class="span6">
					<div class="control-group">
						<label class="control-label" for="floor_id">Assign to floor</label>
						<div class="controls">
							<select name="floor_id" id="floor_id">
								<option value="">None</option>
								<?php foreach($floors as $floor):?><option value="<?php echo $floor['floor_id']?>"><?php echo $floor['floor_name']?></option><?php endforeach?>
							</select>
						</div>
					</div>
					
					
					<div class="control-group">
						<label class="control-label" for="room_id">Assign to room</label>
						<div class="controls">
							<select name="room_id" id="room_id">
								<option value="">None</option>
								<?php foreach($rooms as $room):?><option value="<?php echo $room['room_id']?>"><?php echo $room['room_name']?></option><?php endforeach?>
							</select>
						</div>
					</div>
					
					<div class="control-group deleteItem"  style="display: none;">
						<label class="control-label">Delete</label>
						<div class="controls">
							<div class="well">
								<a href="#" data-itemid="" class="btn btn-danger">Delete</a>
								<p class="help-block">This will permanently delete this macro.</p>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<label class="control-label" for="macro">Macro</label>
						<div class="controls">
							<textarea class="scriptContents input-xlarge" id="macro" name="macro" rows="15"></textarea>
							<p class="help-block">Your script, written in PHP and our own language. <?php btn_scriptinghelp()?></p>
						</div>
					</div>
					
				</div>
				<div class="span6">
					<?php $view->render('modal/scriptrunner', array('formid' => 'macro_form'))?>
					<?php $view->render('modal/devlist')?>
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

	function resetdata(){
		updateMacroData({macro_name: '', macro_desc: '', macro_id: '', macro: '', floor_id: '', room_id: ''});
	}

	$('#macro_form').submit(function(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $url->reverse('MacroHandler.ajaxMacro')?>',
			data: $(this).serialize(),
			success: function(data){
				delaymessage('success', $('#formstatus'), data.message);
				updateMacroData(data.data);
			},
			error: function(data){
				delaymessage('error', $('#formstatus'), data.responseText);
			},
			dataType: 'json'
		});

		return false;
	});

	$('#script_listing a').click(function(){
		var id = $(this).data('macroid');
		$.getJSON('<?php echo $url->reverse('MacroHandler.getMacro')?>?macroid=' + id, function(data){
			updateMacroData(data);
			
			$('.deleteItem').show();
			delaymessage('info', $('#formstatus'), data.macro_name + ' is up for editing.');
		});
	});


	$('.deleteItem a').click(function(){
		var id = $(this).data('itemid');
		$.get('<?php echo $url->reverse('MacroHandler.deleteMacro')?>?itemid=' + id, function(data){
			location.reload();
		});
	});
	

	
	function updateMacroData(data){
		$('.deleteItem').find('a').data('itemid',data.macro_id);
		$('#macro_name').val(data.macro_name);
		$('#macro_id').val(data.macro_id);
		$('#macro').val(data.macro);
		$('#macro_desc').val(data.macro_desc);


		$('#floor_id').val(data.floor_id);
		$('#room_id').val(data.room_id);
		
	}

	
	$('#new_macro').click(function(){
		resetdata();
		$('.deleteItem').hide();
		delaymessage('info', $('#formstatus'), 'Start creating your macro.');
	});


});

</script>
<?php $view->render('footer');?>