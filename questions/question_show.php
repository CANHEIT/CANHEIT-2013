<script type="text/javascript">
$(function() {
	$(".vote").click(function() 
	{
		var id = $(this).attr("id");
		var name = $(this).attr("name");
		var dataString = 'id='+ id + '&vote=' + name;
		var parent = $(this);

		if (name=='up')
		{
			$.ajax({
				type: "POST",
				url: "question_vote.php",
				data: dataString,
				cache: false,

				success: function(html)
				{
					parent.html(html);
				} 
			});
		}
		else
		{
			$.ajax({
				type: "POST",
				url: "question_vote.php",
				data: dataString,
				cache: false,

				success: function(html)
				{
					parent.html(html);
				}
			});
		}
		return false;
	});
});
</script>
<?php

// Include our database and other necessary global configuration
include("config.php");

// Our SQL query for fetching the questions for a particular Session ID
$query = "SELECT * FROM questions WHERE sessionid = '$_GET[sessionid]' ORDER BY score DESC";

// Are we in a "view only" mode for demonstration purposes?
if ($_GET['viewonly'] == 1) { $viewonly = 1; } else { $viewonly = 0; }

if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($obj = $result->fetch_object()) {
		printf("<div class=\"question\">");
		printf("<div class=\"question-text\"><span class=\"question-score\">%s</span>%s</div>", $obj->score, urldecode($obj->question));
        if(!$voting_closed and !$viewonly) { printf("<div class=\"voting-buttons\"><a href=\"#\" id=\"%s\" class=\"vote vote-up\" name=\"up\">Up vote</a>&nbsp;&nbsp;<a href=\"#\" id=\"%s\" class=\"vote vote-down\" name=\"down\">Down vote</a></div>", $obj->id, $obj->id); }
        printf("</div><hr>");
    }

    /* free result set */
    $result->close();
}

// Close the connection
$mysqli->close;

?>