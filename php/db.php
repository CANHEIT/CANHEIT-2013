<?

  require_once '../config.php';

  function load_db() {

    # if db_file doesn't exist, download it
    if (!file_exists(DB_FILE)) {
      if(!download_db()) {
        return false;
      }
    }
    
    return new SQLite3(DB_FILE);
  }
  
  function download_db() {
    $ch = curl_init(DB_SOURCE_URL);
    $fp = fopen(DB_DOWNLOAD_FILE, "w");
    
    if ($fp === FALSE) {
      return false;
    }
    
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    # if error downloading, return a 404
    if (curl_exec($ch) === FALSE) {
      return false;
    }
    curl_close($ch);
    fclose($fp);
    
    unlink(DB_FILE);
    rename(DB_DOWNLOAD_FILE, DB_FILE);
    
    return file_exists(DB_FILE);
  }