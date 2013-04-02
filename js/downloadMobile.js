// JavaScript Document

   $(function() {
            
            if($.cookie('crowdvoting') == 'never' || $.cookie('crowdvoting') == 'not-today') {
              $('#crowdvote').hide(0);
              return;
            }
            
            $('.cancel').fadeIn('fast');
            
            $('#not-today').click(function(){
              $.cookie('crowdvoting','not-today',{ expires: 1 });
              $('#crowdvote').fadeOut('slow');
            });
            
            $('#never').click(function(){
              $.cookie('crowdvoting','never',{ expires: 365 });
              $('#crowdvote').fadeOut('slow');
            });
            
          });