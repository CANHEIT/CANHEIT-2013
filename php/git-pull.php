<?php

  require_once '../config.php';

	chdir('../');
	if (is_dir('.git')) {
		$message = `/usr/bin/git pull`;
		
		echo '<pre>';
		echo $message;
		echo '</pre>';
	}