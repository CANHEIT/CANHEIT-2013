// JavaScript Document

   $(function() {
            
            if($.cookie('crowdvoting') == 'never' || $.cookie('crowdvoting') == 'not-today') {
              $('#app-promo').hide(0);
              return;
            }
            
            $('.cancel').fadeIn('fast');
            
            $('#not-today').click(function(){
              $.cookie('crowdvoting','not-today',{ expires: 1 });
              $('#app-promo').fadeOut('slow');
            });
            
            $('#never').click(function(){
              $.cookie('crowdvoting','never',{ expires: 365 });
              $('#app-promo').fadeOut('slow');
            });
            
          });