<?php

  require_once '../config.php';

	chdir('../');
	if (is_dir('.git')) {
		$message = `/usr/bin/git pull`;
		$message .= `/usr/bin/git reset --hard FETCH_HEAD`;
		$message .= `/usr/bin/git clean -df`;
		
		echo '<pre style="color:#999;">';
		echo $message;
		echo '</pre>';
	}