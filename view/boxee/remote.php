<div class="row-fluid">
	<div class="span6">
		<?php if ($player):?>
			<?php if ($player instanceof \com\boxee\service\Player):?>
				<div id="remPlayer">
					
					<h6>Player</h6>
					<div class="btn-group">
					<button class="btn boxeeAct playBtn" data-method="Player.PlayPause"><i class="icon-play"></i> Play/Pause</button>
					<button class="btn boxeeAct" data-method="Player.Stop"><i class="icon-stop"></i> Stop</button>
					</div>
					
					<h6>Seeker</h6>
					<div id="seeker">
						<div></div>
						<p></p>
					</div>
					
					<h6>File</h6>
					<p class="state lbl"></p>
					<p class="state file"></p>
					
					<h6>Information</h6>
					<a class="btn btn-primary remoteInformation">More information</a>
					
				</div>
				
				
				<script type="text/javascript">
				var MA_STATE = {};


				function getState(){
					sendbox('Player.State', false, function(data){
						MA_STATE = data.result.state;
						if (data.result.paused){
							$('.playBtn').html('<i class="icon-play"></i> Play');
						}else{
							$('.playBtn').html('<i class="icon-pause"></i> Pause');
						}
						
						setTimeout(function(){getState();}, 5000);
				        $('.state.lbl').text(MA_STATE.label);
				        $('.state.file').text(MA_STATE.file);
					});
				}
				
				$(document).ready(function(){
					getState(); // Update state.

					$('.remoteInformation').click(function(){
						openMovieInfo($('.state.file').text(), $('.state.lbl').text());
					});
					
					$('#seeker div').slider({
						stop: function(event, ui){			
							sendbox('Player.SeekTime', {value: ui.value}, function(data){
								
							});
						}
					});

					
					// {hours:0, milliseconds:225, minutes:31, seconds:24}
					function objToS(o){
						return (o.hours * 60 * 60) + (o.minutes * 60) + o.seconds;
					}
		
					
					function updateSeeker(){
						var btn = $('#seeker div');
						sendbox('Player.GetTime', false, function(data){
		
							btn.slider( "option", "max", objToS(data.result.total));
							btn.slider( "option", "value", objToS(data.result.time));
							btn.slider( "option", "step", 1);
		
					        setTimeout(function(){updateSeeker();}, 7000);
		
					        $('#seeker p').html(data.result.time.hours + ":" + data.result.time.minutes
					    	         + ":" + data.result.time.seconds + " / " + data.result.total.hours + ":" + data.result.total.minutes
					    	         + ":" + data.result.total.seconds);
							
						});
					}
					updateSeeker();
					
				});
				</script>
				
			
			<?php elseif ($player instanceof \com\boxee\service\PicturePlayer):?>
				<div id="remPicture">
					
				</div>
			<?php endif?>
			
		<?php else: ?>
			<div class="alert alert-error">No player are currently active.</div>
		<?php endif?>
	
	</div>
	
	<div class="span6">
		<div id="remGlobal">
			<h6>Volume</h6>
			<div class="row-fluid">
				<div class="span9"><div id="volume"><div></div></div></div>
				<div class="span3"><button class="btn boxeeAct" data-method="XBMC.ToggleMute">Mute</button></div>
			</div>
			<h6>System</h6>
			<div class="btn-group">
				<button class="btn boxeeAct" data-method="System.Shutdown">Shutdown</button>
				<button class="btn boxeeAct" data-method="System.Reboot">Reboot</button>
				<button class="btn boxeeAct" data-method="System.Suspend">Suspend</button>
				<button class="btn boxeeAct" data-method="System.Hibernate">Hibernate</button>
			</div>
							
		</div>		
		<script type="text/javascript">
		
		$(document).ready(function(){
			
			$('#volume div').slider({
				max: 100,
				min: 0,
				step: 1,
				stop: function(event, ui){			
					sendbox('XBMC.SetVolume', {value: ui.value}, function(data){
						
					});
				}
			});
			function updateVolume(){
				var btn = $('#volume div');
				sendbox('XBMC.GetVolume', false, function(data){
					
					btn.slider( "option", "value", data.result);
			        setTimeout(function(){updateVolume();}, 15000);
				});
			}
			updateVolume();

			
			$('.boxeeAct').click(function(){
		
				var method = $(this).data('method');
				var args = $(this).data('args');
		
				sendbox(method, args, function(data){
					
				});
				return false;
			});
		
			
			
		});
		</script>
	</div>
</div>