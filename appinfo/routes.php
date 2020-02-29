<?php
/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\SendtoKIndle\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
 
/*$this->create('sendtokindle_ajax_mail', 'ajax/mail.php')
	->actionInclude('sendtokindle/ajax/mail.php');
*/
return [
    'routes' => [
	   ['name' => 'kindle#send', 'url' => 'kindle/send.php', 'verb' => 'POST'],
           ['name' => 'test#fire','url' => 'kind']
    ]
];


