<?php $view->render('header');?>
<div id="rooms">
	<div class="row-fluid">
	
	<?php foreach($rooms as $k => $room):?>
		<?php echo $k % 3 == 0 ? '</div><div class="row-fluid">' : '';?>
		<div class="room span4" id="room_<?php echo $room['room_id']?>">
			<h3><?php echo $room['room_name']?> <span class="headercontrols"><a href="#" class="newDevice" data-room_id="<?php echo $room['room_id']?>"><i class="icon-plus-sign"></i></a><a href="#" class="openRoomConf" data-room_id="<?php echo $room['room_id']?>"><i class="icon-pencil"></i></a></span></h3>
			
			<?php foreach($room['devices'] as $device):?>
			<div class="well" id="device_<?php echo $device['device_code'] ?>">
				<div class="row-fluid">
				
					<h4><?php echo $device['device_name']?> <a href="#" data-device_code="<?php echo $device['device_code']?>" class="openDeviceConf"><i class="icon-pencil"></i></a></h4>
					
					
					<?php if (\X10Device::FG_ON_OFF & $device['device_flags']):?>
						
						<div class="control row-fluid on-offs">
							<div class="span3">Status</div>
							<div class="span3">
							<?php 
							try{
								if (!($device['x10'] instanceof \X10DevicePower))throw new \Exception("Not a power device.");
								if ($device['x10']->isOn()){
									echo '<span class="label label-success">ON</span>';
								}else{
									echo '<span class="label label-important">OFF</span>';
								}
								
							}catch(\Exception $e){
								echo '<span class="label label-info">Unknown</span>';
							}
							?>
							</div>
							<div class="span6">
							<div class="btn-group">
								<?php if (\X10Device::F_ON & $device['device_flags']):?>
									<a href="#" class="btn btn-success" data-type="ON" data-devicecode="<?php echo $device['device_code']?>">On</a>
								<?php endif?>
								<?php if (\X10Device::F_OFF & $device['device_flags']):?>
									<a href="#" class="btn btn-danger" data-type="OFF" data-devicecode="<?php echo $device['device_code']?>">Off</a>
								<?php endif?>
							</div>
							</div>
						</div>
					<?php endif?>
					<?php if(\X10Device::FG_DIM_BRIGHT & $device['device_flags']):?>
						<div class="control row-fluid">
							<div class="span3">Dimmer</div>
							<div class="span9">
							<div class="dimmer" data-devicecode="<?php echo $device['device_code']?>"></div>
							<div id="dimlevel-<?php echo $device['device_code']?>" style="display: none;"><?php try{if (!($device['x10'] instanceof \X10DevicePower))throw new \Exception("Not a power device."); echo $device['x10']->dimLevel();}catch(\Exception $e){echo "0";}?></div>
							</div>
						</div>
					<?php endif?>
				</div>
			</div>
			<?php endforeach?>
			<?php if (count($room['macros']) > 0):?>
				<h6>Macros</h6>
						<?php foreach($room['macros'] as $macro):?>
						<div class="row-fluid room_macro">
							<div class="span3">
							<i class="icon-question-sign" rel="tooltip" title="<?php echo $macro['macro_desc']?>"></i>
							</div>
							<div class="span9">
							<?php $view->render('modal/scriptrunner', array('macroid' => $macro['macro_id'], 'button_name' => $macro['macro_name']))?>
							</div>
							
						</div>
						<?php endforeach?>
					
			<?php endif?>
		</div>
	<?php endforeach?>
	
	
	</div>
</div>
<div class="managebuttons">
	<a class="btn newRoom"><i class="icon-plus-sign"></i> New Room</a>
</div>
<div class="modal hide" id="deviceCP">
	<form method="post" class="form-horizontal" id="device_form">
		<input type="hidden" name="room_id" id="room_id" value="" />
		<input type="hidden" name="old_device_code" id="old_device_code" value="" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Device Configuration</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="device_code">Device code</label>
				<div class="controls">
					<input type="text" class="input-small" id="device_code" name="device_code" />
					<p class="help-block">The X10 device code, eg. a1, a4, e2 etc.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="device_name">Device name</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="device_name" name="device_name" />
					<p class="help-block">A describing name for your device.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="device_desc">Device description</label>
				<div class="controls">
					<textarea id="device_desc" name="device_desc"></textarea>
					<p class="help-block">A describing description for this device.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Communication type</label>
				<div class="controls">
					<label class="radio inline">
						<input type="radio" class="radio inline" name="device_communication" checked="checked" value="1" /> Power
					</label>
					<label class="radio inline">
						<input type="radio" class="radio inline" name="device_communication" value="0" /> Radio
					</label>
					
						
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Device features</label>
				<div class="controls">
					<select multiple="multiple" class="multiselect" id="device_features" name="device_features[]">
						<?php foreach(X10Device::getFeatures() as $val => $name):?>
						<option value="<?php echo $val?>"><?php echo $name?></option>
						<?php endforeach;?>
					</select>
						
				</div>
			</div>
			
			
			
			<div class="control-group">
				<label class="control-label">Delete</label>
				<div class="controls">
					<a href="#" id="deleteDevice" class="btn btn-danger">Delete</a>
					<p class="help-block">NOTE! This can not be undone.</p>
				</div>		
			</div>
		</div>
		<div class="modal-footer">
			
			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
		
	</form>
</div>

<!-- Room CP -->
<div class="modal hide" id="roomCP">
	<form method="post" class="form-horizontal" id="room_form">
		<input type="hidden" name="room_id" id="room_room_id" value="" />
		<input type="hidden" name="floor_id" value="<?php echo $floor['floor_id']?>" />
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Room Configuration</h3>
		</div>
		<div class="modal-body">
			<div class="control-group">
				<label class="control-label" for="room_name">Room name</label>
				<div class="controls">
					<input type="text" class="input-xlarge" id="room_name" name="room_name" />
					<p class="help-block">A describing name for the room.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="room_description">Room description</label>
				<div class="controls">
					<textarea id="room_description" name="room_description"></textarea>
					<p class="help-block">A description for this room.</p>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Delete</label>
				<div class="controls">
					<a href="#" id="deleteRoom" class="btn btn-danger">Delete room</a>
					<p class="help-block">NOTE! This can not be undone.</p>
				</div>		
			</div>
		</div>
		<div class="modal-footer">
			
			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			
			<button type="submit" class="btn btn-primary">Save changes</button>
		</div>
		
	</form>
</div>

<script type="text/javascript">
$(function() {
	$('.openDeviceConf').live('click', function(){
		$('#deviceCP').modal('show');
		var id = $(this).data('device_code');

		$.post('<?php echo $url->reverse('DeviceControl.getDevice')?>', {device_code: id}, function(data){

			
			$('#device_features').multiSelect('deselect_all');
			for(i = 0; i < data.features.length; i++){
				$('#device_features').multiSelect('select', data.features[i]);
			}
			$('#room_id').val(data.room_id);
			$('#old_device_code').val(data.device_code);
			$('#device_code').val(data.device_code);
			$('#device_name').val(data.device_name);
			$('#device_desc').val(data.device_desc);

			$(':radio[value='+data.powerline_communication+']').attr('checked',true);
			$('#deleteDevice').parent().parent().show();
			
		}, "json");		
	});
	$('.newDevice').click(function(){
		$('#device_features').multiSelect('deselect_all');
		$('#room_id').val($(this).data('room_id'));

		$('#old_device_code').val('');
		$('#device_code').val('');
		$('#device_name').val('');
		$('#device_desc').val('');
		$(":radio[value=1]").attr('checked',true);
		$('#deleteDevice').parent().parent().hide();
		$('#deviceCP').modal('show');
	});
	$('#deleteDevice').live('click', function(){
		$.post('<?php echo $url->reverse('DeviceControl.deleteDevice')?>', {device_code: $('#old_device_code').val()}, function(data){
			$('#device_'+$('#old_device_code').val()).fadeOut();
			$('#deviceCP').modal('hide');
			globalmessage('success', data);
		}); 		
	});
	$('#device_form').submit(function(){

		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('DeviceControl.cuDevice')?>',
			  data: $(this).serialize(),
			  success: function(data, text){
				  location.reload();
			  },
			  error: function(data){
				  alert(data.responseText);
			  },
			  dataType: "json"
		});

		return false;
	});





	/* ROOM */
	$('.openRoomConf').live('click', function(){
		$('#roomCP').modal('show');
		var id = $(this).data('room_id');

		$.post('<?php echo $url->reverse('DeviceControl.getRoom')?>', {room_id: id}, function(data){
			$('#room_room_id').val(data.room_id);
			$('#room_name').val(data.room_name);
			$('#room_description').val(data.room_description);
			$('#deleteRoom').parent().parent().show();
			
		}, "json");		
	});
	$('.newRoom').click(function(){
		
		$('#room_room_id').val('');
		$('#room_name').val('');
		$('#room_description').val('');
		$('#deleteRoom').parent().parent().hide();
		$('#roomCP').modal('show');
	});
	$('#deleteRoom').live('click', function(){
		$.post('<?php echo $url->reverse('DeviceControl.ajaxRoom')?>', {room_id: $('#room_room_id').val(), remove: true}, function(data){
			$('#room_'+$('#room_room_id').val()).fadeOut();
			$('#roomCP').modal('hide');
			globalmessage('success', data);
		}); 		
	});
	$('#room_form').submit(function(){

		$.ajax({
			  type: 'POST',
			  url: '<?php echo $url->reverse('DeviceControl.ajaxRoom')?>',
			  data: $(this).serialize(),
			  success: function(data, text){
				  location.reload();
			  },
			  error: function(data){
				  alert(data.responseText);
			  },
			  dataType: "json"
		});

		return false;
	});
	
	

	
	
	$( ".dimmer" ).slider({

		max: 100,
		min: 0,
		stop: function(event, ui){
			var dim = ui.value;
			$.post('<?php echo $url->reverse('DeviceControl.deviceAjax')?>', 
					{type: 'DIM', device_code: $(this).data('devicecode'), value: dim},
					function(data){

					}
			);
		}
	});

	// Set current dim based on X10.
	$('.dimmer').each(function() {
		var init_value = $('#dimlevel-'+$(this).data('devicecode')).text();
		$(this).slider({value: init_value});
	});

	$(".on-offs a").click(function(){
		var type = $(this).data('type');
		var code = $(this).data('devicecode');
		$.post('<?php echo $url->reverse('DeviceControl.deviceAjax')?>', 
				{type: $(this).data('type'), device_code: code},
				function(data){
					var sel = $('#device_'+code+' .on-offs span');
					if (type == 'ON'){
						sel.attr('class', 'label label-success');
						sel.html("ON");
					}else{
						sel.attr('class', 'label label-important');
						sel.html("OFF");
					}
				}
		);
	});

	
});
</script>
<?php $view->render('footer');?>