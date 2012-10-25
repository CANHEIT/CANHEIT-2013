
jQuery(document).ready(function(){

	$('#subscribeform').append('<div class="subscribe-loader hidden" />');

	$('#subscribeform').submit(function(){

		var action = $(this).attr('action');

		$("#mesaj").slideUp(750,function() {
		$('#mesaj').hide();

 		$('#subsubmit')
			.attr('disabled','disabled');

		$('#subscribeform div.subscribe-loader').fadeIn('slow');
		

		$.post(action, {
			email: $('#subemail').val()
		},
			function(data){
				document.getElementById('mesaj').innerHTML = data;
				$('#mesaj').slideDown('slow');
				$('#subscribeform div.subscribe-loader').fadeOut('slow');
				$('#subsubmit').removeAttr('disabled');
				if(data.match('success') != null) $('#subscribeform').slideUp('slow');

			}
		);

		});

		return false;

	});

});