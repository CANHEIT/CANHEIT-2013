jQuery(document).ready(function(){

  function recordOutboundLink(link, category, action) {
    try {
      var myTracker=_gat._getTrackerByName();
        _gaq.push(['myTracker._trackEvent', category , action ]);
        setTimeout('document.location = "' + link.href + '"', 100)
    } catch(err) {}
  }
  
  $('a[rel=external]').each(function(){
    var category = 'outbound-links';
    var action = '';
    if ($(this).data('ga-label')) {
      $(this).click(function() {
        action = $(this).data('ga-label')
        recordOutboundLink(this,category,action);
        return false;
      })
    } else if ($(this).attr('href')) {
      $(this).click(function() {
        action = $(this).attr('href')
        recordOutboundLink(this,category,action);
        return false;
      })
    }
  });

  $('a[rel=email]').each(function(){
    var category = 'email';
    var action = '';
    if ($(this).data('ga-label')) {
      $(this).click(function() {
        action = $(this).data('ga-label')
        recordOutboundLink(this,category,action);
        return false;
      })
    } else if ($(this).attr('href')) {
      $(this).click(function() {
        action = $(this).attr('href')
        recordOutboundLink(this,category,action);
        return false;
      })
    }
  });
  
});


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