<?php

define('BASE_URL', 'http://localhost/grm-mvc/');
define('PUBLIC_URL', BASE_URL . 'public/');
define('XML_SRC_URL', BASE_URL . 'md-src/xml/');
define('JSON_SRC_URL', BASE_URL . 'md-src/json/');
define('VOL_URL', PUBLIC_URL . 'Volumes/');
define('FLAT_URL', BASE_URL . 'application/views/flat/');

// Physical location of resources
define('PHY_BASE_URL', '/var/www/html/grm-mvc/');
define('PHY_PUBLIC_URL', PHY_BASE_URL . 'public/');
define('PHY_XML_SRC_URL', PHY_BASE_URL . 'md-src/xml/');
define('PHY_JSON_SRC_URL', PHY_BASE_URL . 'md-src/json/');
define('PHY_VOL_URL', PHY_PUBLIC_URL . 'Volumes/');
define('PHY_FLAT_URL', PHY_BASE_URL . 'application/views/flat/');

//~ define('DB_PREFIX', 'rig');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '27017');
define('DB_NAME', 'grmARCHIVES');
define('DB_USER', 'grmUSER');
define('DB_PASSWORD', 'grm123');

//use grmARCHIVES;
//db.createUser(
//   {
//     user: "grmUSER",
//     pwd: "grm123",
//     roles:
//       [
//         { role: "readWrite", db: "grmARCHIVES" }
//       ]
//   }
//)

?>
