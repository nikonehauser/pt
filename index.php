<?php

namespace Tbmt;

define('PROJECT_NAME', 'miltype');
define('NS_ROOT_NAME', 'Tbmt');
define('NS_ROOT_PART', 'Tbmt\\');

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

try {
  require BASE_DIR.'/include/bootstrap.php';

  session_name(PROJECT_NAME);
  session_start();

  /* Dispatch controller
  ---------------------------------------------*/
  list(
    $controllerName,
    $controllerAction
  ) = Arr::initList($_REQUEST, [
    Router::KEY_MODULE => [\Tbmt\TYPE_STRING, 'home'],
    Router::KEY_ACTION => [\Tbmt\TYPE_STRING, 'index'],
  ]);

  $controllerBody = ControllerDispatcher::dispatchAction(
    $controllerName,
    $controllerAction
  );

  // TODO we may want to check the controller result to handle specials types like
  // ControllerResultCommand::REDIRECT etc.
  //
  // at the moment a controller action has to return a html string

  echo (new view\Index())->render([
    'basePath'    => '',
    'windowtitle' => 'TostiMiltype',
    'controllerBody' => $controllerBody
  ]);

} catch (PublicException $e) {
  error_log($e->__toString());
  echo view\PublicError::fromPublicException($e);
} catch (\Exception $e) {
  error_log($e->__toString());
  echo view\Error::fromException($e);
}


?>
