	<?php if (isset($sidebar)):?>
				</div><!-- /.row-fluid -->
			</div><!-- /.span9 -->
	<?php endif?>
		</div><!-- /.row-fluid -->
	</div><!-- /.container -->
	<footer>
		Software delivered by Petter Kjelkenes, kjelkenes@gmail.com - <a href="<?php echo $url->reverse('DeviceLogs.getList')?>">Device Logs</a>, <a data-toggle="modal" href="#about" >About</a>
	</footer>
	<div class="modal hide" id="about">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>About</h3>
		</div>
		<div class="modal-body">
			<h4>Author</h4>
			<p>This software is free and is developed by Petter Kjelkenes. Do you need custom scripting help or customization? Contact Petter Kjelkenes to get a free quote for the work at <a href="mailto:kjelkenes@gmail.com">kjelkenes@gmail.com</a>. We can offer you low prices.</p>
			
			<h4>Technologies</h4>
			<p>This software is written in PHP and uses techonologies such as MySQL, Ajax, Javascript, Jquery, REKS framework. It's written to make your home modular and easy to manage.</p>
			
			<h4>Bug reports &amp; enhancements</h4>
			<p>Have you noticed a bug or want a new feature? We are activly working to keep this software great. Go to our <a href="http://code.google.com/p/x10php/issues/list">google hosting issue tracker</a> to submit a ticket.</p>
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
	</div>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$('.scriptingPanel a').live('click', function(){
			var status = $(this).parent().find('.status');
			if ($(this).data('running') == true){
				delaymessage('error', status, "Please wait for macro to finish.");
				return false;
			}else $(this).data('running', true);

			
			var loader = $(this).parent().find('.loader');
			loader.find('span').text('Running');
			loader.show();
			
			var macroid = $(this).data('macroid');
			var scriptid = $(this).data('scriptid');
			var formdata = {};
			if (macroid){
				formdata = {macroid: macroid};
			}else if(scriptid){
				formdata = {scriptid: scriptid};
			}else{
				formdata = {script: $($(this).data('formid')).find('.scriptContents').val()};
			}
			
			
			var linker = $(this);
			
			
			$.ajax({
				type: 'POST',
				url: '<?php echo $url->reverse('MacroHandler.scriptHandler')?>',
				data: formdata,
				success: function(data){
					loader.hide();
					linker.data('running', false);
					delaymessage('success', status, data);

					updateMacroData(data.data);
					
				},
				error: function(data){
					loader.hide();
					linker.data('running', false);
					delaymessage('error', status, data.responseText);
				}
			});
			
		});

	});
	</script>
	
	<div id="ajaxLoading" style="display: none;">
		<span></span><img src="<?php echo $url->asset('img/ajaxload-flat.gif')?>" alt="Loading, please wait ..." />
	</div>
	
</body>
</html>