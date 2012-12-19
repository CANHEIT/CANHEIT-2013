jQuery(document).ready(function(){

	_gaq = window._gaq || [];
  
  $('a[rel=external]').each(function(){
    var category = 'outbound-links';
    var action = 'click';
    $(this).click(function() {
      action = 'click';
      label = $(this).data('ga-label') || this.href;
      try {
          _gaq.push(['canheit._trackEvent', category , action, label ]);
          setTimeout('document.location = "' + this.href + '"', 100);
      } catch(err) {}
      return false;
    });
  });
  
  $('a[rel=file]').each(function(){
    $(this).click(function() {
      try {
          console.log(this.href.pathname);
          _gaq.push(['canheit._trackPageview', 'file/' + $(this).attr('href') ]);
          setTimeout('document.location = "' + this.href + '"', 100);
      } catch(err) {}
      return false;
    });
  });

  $('a[href^="mailto:"]').each(function(){
    var category = 'email';
    var action = 'click';
    $(this).click(function() {
      action = 'click';
      label = $(this).data('ga-label') || this.href;
      try {
          _gaq.push(['canheit._trackEvent', category , action, label ]);
      } catch(err) {}
    });
  });
  
  $('a[href^="tel:"]').each(function(){
    var category = 'tel';
    var action = 'click';
    $(this).click(function() {
      action = 'click';
      label = $(this).data('ga-label') || this.href;
      try {
          _gaq.push(['canheit._trackEvent', category , action, label ]);
      } catch(err) {}
    });
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

jQuery(document).ready(function(){
  if (Modernizr.touch){
     window.previousTitle = document.title;
     document.title = "CANHEIT.ca";
  }
});
