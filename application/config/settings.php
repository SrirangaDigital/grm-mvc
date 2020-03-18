<?php


define('ARTEFACT_COLLECTION', 'artefacts');
define('FULLTEXT_COLLECTION', 'fulltext');
define('USER_COLLECTION', 'userdetails');

// db table names
define('PURANA_TABLE', 'GM_Toc');
define('MANDALA_TABLE', 'mandala_table');
define('RUKKU_TABLE', 'Rukku_table');
define('RIGTOC_TABLE', 'Rig_Toc');

// Defaults
define('DEFAULT_RIK', '1.1.1');
define('DEFAULT_QUADRUPLET', '1.1.1.1');

define('DEFAULT_MANDALA', '1');
define('DEFAULT_SUKTA', '1');
define('DEFAULT_RIk', '1');

define('DEFAULT_ASHTAKA', '1');
define('DEFAULT_ADHYAYA', '1');
define('DEFAULT_VARGA', '1');

define('DEFAULT_ATTR', 'puranagalu');
define('DEFAULT_PADA_FROM', 'ಅ');
define('DEFAULT_MANTRA_FROM', 'ಅ');


// search settings
define('SEARCH_OPERAND', 'AND');

// user settings (login and registration)
define('SALT', 'grm');
define('REQUIRE_EMAIL_VALIDATION', False);//Set these values to True only
define('REQUIRE_RESET_PASSWORD', False);//if outbound mails can be sent from the server

// mailer settings
define('SERVICE_EMAIL', 'arjun.kashyap@srirangadigital.com');
define('SERVICE_NAME', 'Team Rigveda, Sriranga');

?>
