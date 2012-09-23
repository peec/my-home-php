	<div class="modal hide" id="scriptinghelp">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>Scripting help</h3>
		</div>
		<div class="modal-body">
				<h4>Scripting help</h4>
				<p>Scripts are written in PHP but you can use some of our own language to make your scripts smaller.</p>
				<h4>Variables</h4>
				<p><strong>$x10</strong> The X10 variable is a instance of <a href="http://code.google.com/p/x10php/source/browse/trunk/x10/X10.php">X10 class</a>. You can turn of / on / dim devices, bastically everything that has to do with your devices.</p>
				<p><strong>$config</strong> The configuration keys and values.</p>
				<p><strong>$reks</strong> Instance of the controller class in the application. You can use models/database and more.</p>
				<h4>Functions</h4>
				<p><strong>isWeekend()</strong> Usage: isWeekend(). Returns true if its weekend else false.</p>
				<p><strong>clockIs(0-23)</strong> Usage: clockIs(0) // Midnight (24:00) ... Returns true if the clock is $i.</p>
				<h4>Example</h4>
<pre class="prettyprint linenums">
$x10->dev('a1')->on();
// Sleep for 15 seconds
sleep(15);
$x10->dev('b2')->dim(50);
</pre>
				

		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
	</div>
	
	
<?php 
	function btn_scriptinghelp(){
		echo '<a class="btn" data-toggle="modal" href="#scriptinghelp"><i class="icon-question-sign"></i> Scripting guide</a>';
	}

?>