<?php
# set defaults

	require_once '../config.php';

# load requirements

	require_once '../lib/.vendor/autoload.php';
  	require_once '../php/db.php';

	$db = load_db();
	$session_id = $_GET['sessionid'];

	//$results = $db->query('SELECT * FROM guidebook_event WHERE id = 969446');

	$stmt = $db->prepare('SELECT * FROM `guidebook_event` WHERE id = :id');
    $stmt->bindParam(':id', $session_id, SQLITE3_INTEGER);
    $is_single_object_expected = true;


	if ($stmt) {
		$data = array();
 		$data['objects'] = array();
		$result = $stmt->execute();

	if ($is_single_object_expected) {
		$data = $result->fetchArray();
	} else {
	  	while($row = $result->fetchArray()) {
			array_push($data['objects'], $row);
	  }
	}
		$session_name = $data['name'];
		$session_start = strtotime($data['startTime']);
		$session_end = strtotime($data['endTime']);
		$current_time = strtotime("now");

		// The following provides the ability to test using any timestamp we want.
		if($_GET['startTime']) { $session_start = $_GET['startTime']; }
		if($_GET['endTime']) { $session_end = $_GET['endTime']; }

		$db->close();
		
		// Are we in a "view only" mode for demonstration purposes?
		if ($_GET['viewonly'] == 1) { $viewonly = 1; } else { $viewonly = 0; }
	}
	
	if ($current_time < $session_start) {
		$refresh = ($session_start - $current_time) * 1000;
	}
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

        <?php readfile('../include/head-bottom.html'); ?>
    </head>
    <body>
        <!--[if lt IE 9]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
      <div class='content'>
        <div class="container">
          <header>
            <a href="/" rel="home" class="return"><img src="/img/canheit2013_logo_horizontal.png" alt="home" id="logo" /></a>
            <p class="tagline">Canada's premier Higher Education IT Conference</p>
            <p class="location-date">
              <span class="host">University of Ottawa</span>
              <span class="location">Ottawa, Ontario</span>
              <span class="date">June 9 - 12, 2013</span>
            </p>
          </header>
          <article>
            <header>
              <?php
              		if($_GET['app'] != 1) {
              			echo '<a href="../'.$session_id.'" rel="home" class="return">'.$session_name.'</a>';
              		}
              	?>
<!--               <h1><?php echo $session_name; ?></h1> -->
              <a name="live"><h2>Live Q&amp;A</h2></a>
              <?php if(!$viewonly) { ?>
              	<p>Enter your question for the speaker in the text area below and click "Submit". Up-vote the questions you want answered! Only one vote per question is permitted.</p>
              <?php } ?>

            </header>
            
	           	<?php 
	           		if (($current_time >= $session_start) and ($current_time <= ($session_end + 300)))
	           		{ 
						if(!$viewonly) {
	           	?>
							<!-- The Name form field -->

							<form name="question_add" id="question_add" action="" method="POST">  
								<label for="question" id="name_label">Enter your question</label>  
		  
								<!-- <input type="text" name="question" id="question" size="30" value=""/>   -->
								<textarea name="question" id="question" autofocus></textarea>
								<br>
							<!-- The Submit button -->
								<input type="hidden" name="sessionid" value="<?php echo $session_id; ?>"/>
								<input type="submit" name="submit" value="Submit"> 
							</form>
				<?php
					}
				?>				
				<!-- Question and voting buttons automatically outputted into this div -->
				<div id="questions"><div>

				<?php
					}
					elseif ($current_time < $session_start)
					{
				?>
				<form name="question_add" id="question_add" action="" method="POST">  
					<p style="color:red;">The interactive question period for the session "<?php echo $session_name; ?>" has not yet started. The session is scheduled to start on <?php echo date("l, F jS", $session_start); ?> at <?php echo date("g:i a", $session_start); ?>. This page will automatically refresh when the session starts.</p>
				</form>
				<?php
				}
				elseif ($current_time > ($session_end + 300))
				{
					$voting_closed = 1;
				?>
				<form name="question_add" id="question_add" action="" method="POST">  
					<p style="color:red;">Sorry, the interactive question period for the session "<?php echo $session_name; ?>" is now closed.</p>
				</form>

				<!-- Question and voting buttons automatically outputted into this div -->
				<div id="questions"><div>
				<?php
				}
				?>
            
					</article>  				

					<footer>
					  <h2>Don't be shy! Stay in touch&hellip;</h2>
					  <a href="https://twitter.com/CANHEIT2013" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @CANHEIT2013</a>
					  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					  <div id="subscribe">
  						<div id="mesaj"></div>
  						<form action="/php/subscribe.php" method="post" id="subscribeform" name="subscribeform" class="clearfix">
  							<input type="text" name="email" placeholder="Enter your email address to get notified" id="subemail">
  							<input type="submit" name="send" value="Subscribe" id="subsubmit">
  						</form>
  					</div>
					</footer>

        </div>
      </div

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
        <script src="/js/plugins.js"></script>
        <script src="/js/main.js"></script><script src="/js/vendor/jquery.smartbanner/jquery.smartbanner.js"></script>
        <script src="/js/vendor/jquery.collapse.js"></script>
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
						$.post('../../questions/question_add.php', $("#question_add").serialize(), function(data) {
							$('#questions').html(data);
						});
						$('#question_add')[0].reset();
					}
				});
			});
		</script>

		<script>
			$(document).ready(function() {
				$("#questions").load("../../questions/question_show.php?sessionid=" + <?php echo $session_id; ?> + "&viewonly=" + <?php echo $viewonly; ?>);
				var refreshId = setInterval(function() {
					$("#questions").load('../../questions/question_show.php?sessionid=' + <?php echo $session_id; ?> + "&viewonly=" + <?php echo $viewonly; ?>);
				}, 1000);
				$.ajaxSetup({ cache: false });
			});
		</script>


        <script>
            var _gaq=[['canheit._setAccount','UA-35890616-1'],['canheit._trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
        
        <script>
	        function ReloadPage() {
			   location.reload();
			};

		$(document).ready(function() {
		  setTimeout("ReloadPage()", <?php echo $refresh; ?> );
		  });
        </script>
    </body>
</html>