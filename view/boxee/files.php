<h2>Browse files</h2>
<hr />
<div class="row-fluid mediatypes">
	<h4>Select media</h4>
	<a class="btn btn-large btn-primary" href="<?php echo $url->reverse('BoxeeBox.setUseMedia', array('media' => 'video'))?>">Movies</a>
	<a class="btn btn-large btn-primary" href="<?php echo $url->reverse('BoxeeBox.setUseMedia', array('media' => 'music'))?>">Music</a>
	<a class="btn btn-large btn-primary" href="<?php echo $url->reverse('BoxeeBox.setUseMedia', array('media' => 'pictures'))?>">Images</a>
	<a class="btn btn-large btn-primary" href="<?php echo $url->reverse('BoxeeBox.setUseMedia', array('media' => 'files'))?>">Files</a>
	</div>
</div>
<hr />

<div id="filetree">

</div>
<script type="text/javascript">
$(function() {

	$('.mediatypes a').click(function(){
		
		$.get($(this).attr('href'), function(data){
			location.reload();	
		});	
		return false;
	});

	



	$('#filetree').fileTree({
		root: '/',
		script: '<?php echo $url->reverse('BoxeeBox.dirlisting')?>',
		expandSpeed: 1000,
		collapseSpeed: 1000,
		multiFolder: false
		},
		function(obj){
			openMovieInfo(obj.attr('rel'), obj.text());
		}
	);

	

	

	
});
</script>


