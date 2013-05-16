<?php
  define('TEMPLATE_DIR', dirname(__FILE__) . '/templates');
  
  define('CACHE_DIR', dirname(__FILE__) . '/.cache/');

  define('DB_FILE', CACHE_DIR . '/guide.db');
  define('DB_DOWNLOAD_FILE', CACHE_DIR . '/guide-new.db');
  
  define('DB_SOURCE_URL','http://s3.amazonaws.com/media.guidebook.com/service/vXSEB4weN3Px5jc7gCRKnAqask9yup6t/guide.db');
  define('DB_MANIFEST_URL','http://s3.amazonaws.com/media.guidebook.com/service/vXSEB4weN3Px5jc7gCRKnAqask9yup6t/manifest.json');
  define('IMAGE_AWS_ROOT_URL','http://s3.amazonaws.com/media.guidebook.com/upload/5396/');