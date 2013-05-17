<?php
$jsonurl = "https://gears.guidebook.com/api/v1/event/" . $_GET['sessionid'] . "/?guide__id=5396&format=json&username=jarsenea@uottawa.ca&api_key=DYJmr8vDWBrfZeBUr8wfgrhxMQUemRvnvSGYnfdKDQQxsvY";
$json = file_get_contents($jsonurl,0,null,null);
$json_output = json_decode($json);
$session_name = $json_output->name;
?>

<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Interactive Questions - <?php echo $session_name; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <meta name="apple-itunes-app" content="app-id=595230973, app-argument=gb://guide/5396/">
        <meta name="google-play-app" content="app-id=com.guidebook.apps.CANHEIT2013.android">

        <link rel="icon" type="image/png" href="../../favicon-256x256.png">

        <link rel="stylesheet" href="../../css/normalize.css">
        <link rel="stylesheet" href="../../css/boilerplate.css">
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,400italic,600italic,700italic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="../../css/screen.css"><link rel="stylesheet" href="../../js/vendor/jquery.smartbanner/jquery.smartbanner.css">
        <script src="../../js/vendor/modernizr-2.6.2.min.js"></script>
		        
    </head>
    <body>
        <!--[if lt IE 9]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="container">
          <header>
            <a href="../../" rel="home" class="return"><img src="../../img/canheit2013_logo_horizontal.png" alt="home" id="logo" /></a>
            <p class="tagline">Canada's premier Higher Education IT Conference</p>
            <p class="location-date">
              <span class="host">University of Ottawa</span>
              <span class="location">Ottawa, Ontario</span>
              <span class="date">June 9 - 12, 2013</span>
            </p>
          </header>
          <article>
            <header>
              <a href="http://canheit.uottawa.ca/program/<?php echo $_GET[sessionid]; ?>" rel="home" class="return">Return to session page: <?php echo $session_name; ?></a>
              
<!--               <h1><?php echo $session_name; ?></h1> -->
              <h2>Interactive Question Period</h2>
              <p>Enter your question for the speaker in the text area below and click "Submit". Up-vote the questions you want to get answered! Only one vote per question allowed.</p>
            </header>
            
				<form name="question_add" id="question_add" action="" method="POST">  
				<!-- The Name form field -->
					<label for="question" id="name_label">Enter your question</label>  
          
					<!-- <input type="text" name="question" id="question" size="30" value=""/>   -->
					<textarea name="question" id="question" autofocus></textarea>
					<br>
				<!-- The Submit button -->
					<input type="hidden" name="sessionid" value="<?php $_GET['sessionid'] ?>"/>
					<input type="submit" name="submit" value="Submit"> 
				</form>
				<!-- We will output the results from process.php here -->

				<div id="questions"><div>
            
					</article>  				
					<footer>
					  <h2>Don't be shy! Stay in touch&hellip;</h2>
					  <a href="https://twitter.com/CANHEIT2013" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @CANHEIT2013</a>
					  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					  <div id="subscribe">
  						<div id="mesaj"></div>
  						<form action="../../php/subscribe.php" method="post" id="subscribeform" name="subscribeform" class="clearfix">
  							<input type="text" name="email" placeholder="Enter your email address to get notified" id="subemail">
  							<input type="submit" name="send" value="Subscribe" id="subsubmit">
  						</form>
  					</div>
					</footer>
					
        </div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="../../js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
        <script src="../../js/plugins.js"></script>
        <script src="../../js/main.js"></script><script src="../../js/vendor/jquery.smartbanner/jquery.smartbanner.js"></script>
        <script src="../../js/vendor/jquery.collapse.js"></script>
		<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#question_add").validate({
					debug: false,
					rules: {
						question: "required",
					},
					messages: {
						question: "Please enter a question.",
					},
					submitHandler: function(form) {
						// do other stuff for a valid form
						$.post('question_add.php', $("#question_add").serialize(), function(data) {
							$('#questions').html(data);
						});
						$('#question_add')[0].reset();
					}
				});
			});
		</script>

		<script>
			$(document).ready(function() {
				$("#questions").load("question_show.php?sessionid=" + <?php echo $_GET[sessionid]; ?>);
				var refreshId = setInterval(function() {
					$("#questions").load('question_show.php?sessionid=' + <?php echo $_GET[sessionid]; ?>);
				}, 1000);
				$.ajaxSetup({ cache: false });
			});
		</script>

        <script>
          jQuery(document).ready(function($){
            $("aside.note").collapse({
              open: function() {
                // The context of 'this' is applied to
                // the collapsed details in a jQuery wrapper 
                _gaq = window._gaq || [];
                _gaq.push(['canheit._trackEvent', 'page-content' , 'open', 'sessions-formats' ]);
                this.slideDown(200);
              },
              close: function() {
                _gaq = window._gaq || [];
                _gaq.push(['canheit._trackEvent', 'page-content' , 'close', 'sessions-formats' ]);
                this.slideUp(200);
              },
              accordion : true,
              persist: false
            });
          });
        </script>
        

        <script>
            var _gaq=[['canheit._setAccount','UA-35890616-1'],['canheit._trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>