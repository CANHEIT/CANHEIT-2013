// JavaScript Document

   $(function() {
            
            if($.cookie('app-download') == 'never' || $.cookie('app-download') == 'not-today') {
              $('#app-promo').hide(0);
              return;
            }
            
            $('.cancel').fadeIn('fast');
            
            $('#not-today').click(function(){
              $.cookie('app-download','not-today',{ expires: 1 });
              $('#app-promo').fadeOut('slow');
            });
            
            $('#never').click(function(){
              $.cookie('app-download','never',{ expires: 365 });
              $('#app-promo').fadeOut('slow');
            });
            
          });