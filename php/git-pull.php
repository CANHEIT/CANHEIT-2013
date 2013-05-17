<?php

  require_once '../config.php';

	chdir('../');
	if (is_dir('.git')) {
		$message = `/usr/bin/git pull`;
		
		echo '<pre style="color:#999;">';
		echo $message;
		echo '</pre>';
	}