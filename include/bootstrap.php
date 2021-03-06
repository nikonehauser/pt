<?php

namespace Tbmt;

spl_autoload_register(function ($name) {
  if (\substr($name, 0, 5) === NS_ROOT_PART) {
    $name = substr($name, 5);
    $pos = strpos($name, '\\');
    if ( $pos !== false ) {
      list($base, $name) = explode('\\', $name);
      $base .= DIRECTORY_SEPARATOR;
    } else
      $base = INC_DIR;

    require $base.$name.'.php';
  }
});

\set_error_handler(function($errno, $errstr, $errfile, $errline) {
 throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

define('PROJECT_NAME', 'miltype');
define('NS_ROOT_NAME', 'Tbmt');
define('NS_ROOT_PART', 'Tbmt\\');

define('CONFIG_DIR', BASE_DIR.'config'.DIRECTORY_SEPARATOR);
define('LIB_DIR', BASE_DIR.'lib'.DIRECTORY_SEPARATOR);
define('ENTITIES_DIR', BASE_DIR.'entities'.DIRECTORY_SEPARATOR);
define('ENTITIES_CLASSES_DIR', ENTITIES_DIR.'build'.DIRECTORY_SEPARATOR.'classes'.PATH_SEPARATOR);
define('VENDOR_DIR', BASE_DIR.'vendor'.DIRECTORY_SEPARATOR);
define('ASSETS_DIR', BASE_DIR.'assets'.DIRECTORY_SEPARATOR);
define('INC_DIR', BASE_DIR.'include'.DIRECTORY_SEPARATOR);
define('API_DIR', BASE_DIR.'api'.DIRECTORY_SEPARATOR);
define('MODULES_DIR', BASE_DIR.'modules'.DIRECTORY_SEPARATOR);
define('VIEWS_DIR', BASE_DIR.'views'.DIRECTORY_SEPARATOR);
define('LOCALES_DIR', BASE_DIR.'locales'.DIRECTORY_SEPARATOR);

require INC_DIR.'Exceptions.php';
require INC_DIR.'Val.php';
require INC_DIR.'Config.php';
require INC_DIR.'Localizer.php';
require INC_DIR.'Router.php';
require INC_DIR.'ControllerDispatcher.php';

Config::load(CONFIG_DIR.'cfg.json');
$baseUrl = Config::get('baseurl');
if ( !$baseUrl )
  throw new \Exception('Invalid configuration. Missing "baseurl" definition.');

Localizer::load(LOCALES_DIR);
Router::init($baseUrl);

/* Setup propel
---------------------------------------------*/
set_include_path(
  get_include_path().PATH_SEPARATOR.
  ENTITIES_CLASSES_DIR.
  LIB_DIR.PATH_SEPARATOR.
  BASE_DIR.PATH_SEPARATOR
);

require_once LIB_DIR.'/propel/runtime/lib/Propel.php';

try {
  \Propel::init(ENTITIES_DIR.'build'.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.PROJECT_NAME.'-conf.php');
  \Propel::getDB()->setCharset(\Propel::getConnection(), 'UTF8');

  \Transaction::initAmounts(
    Config::get('amounts', TYPE_ARRAY),
    Config::get('member_fee', TYPE_FLOAT),
    Config::get('base_currency')
  );

} catch (\Exception $e) {
  // Do NOT output stacktrace because it holds the plain pg password.
  echo $e->getMessage();
  error_log($e->__toString());
  exit();
}


?>