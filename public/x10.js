
	/*
	 * Sends a message to user and removes it after 3 seconds.
	 */
	function delaymessage(type, wrapperbox, message){
		var alertclass = '';
		if (type == 'info'){
			alertclass = 'alert-info';
		}
		if (type == 'success'){
			alertclass = 'alert-success';
		}
		if (type == 'error'){
			alertclass = 'alert-error';
		}
		var html = '<div class="alert '+alertclass+'" style="display: none;">'+message+'</div>';
		wrapperbox.prepend(html);

		

		
		var div = wrapperbox.find('div').first();
		div.fadeIn();
		setTimeout(function(){
			div.fadeOut("slow", function () {
				div.remove();
			});
		}, 3000);

	}
	
	
	function globalmessage(type, message){
		delaymessage(type, $('#globalmessages'), message);
	}
	
	function loadbar(title){
		return $('#ajaxLoading').html();
	}
	
$(document).ready(function(){

	$('.multiselect').multiSelect();
	$('a[rel*=external]').click( function() {
        window.open(this.href);
        return false;
    });
	
	$('body').tooltip({
	    selector: '[rel=tooltip]'
	});

	
	
	$('.loader').hide();
	$('.loader').html(loadbar());
	
	

	
});	
	