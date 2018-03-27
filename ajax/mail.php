<?php
 
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
\OC::$server->getSession()->close();

$folder = isset($_POST['dir']) ? $_POST['dir'] : '/';


$file = (string)$_POST['file'];


$folder = rtrim($folder, '/') . '/';
$filename = $folder.$file;
$systemConfig = \OC::$server->getSystemConfig();

$dataDir = $systemConfig->getValue('datadirectory');
$username = \OC::$server->getUserSession()->getLoginName();
$fromAddr = $systemConfig->getValue('kdeFromAddr');
$toAddr = $systemConfig->getValue('kdeToAddr');

if (!$fromAddr || !$toAddr) {
	
	 $msg = "no from or target email address is set";
	 OCP\JSON::error(array("data" => array('message' => $msg)));
	 
	 return;
	
}
	   
$filePath = sprintf("%s/%s/files/%s",$dataDir,$username,$filename);

if (!file_exists($filePath)) {
	
	 $msg = "file doesnt exist on the server";
	 OCP\JSON::error(array("data" => array('message' => $msg)));
	 return;
}else {
	
  $msg = sprintf("%s sent to your kindle",$filename);
}

 try {
	$mailer = \OC::$server->getMailer();
  	$message = $mailer->createMessage();
  	$attachment = $mailer->createAttachmentFromPath($filePath)->setFilename($filename);
  	$message->setSubject('kindle');
 	$message->setFrom(array($fromAddr =>$fromAddr));
  	$message->setTo(array($toAddr => $toAddr));
  	$message->setBody($filename,'text/html');
  	$message->attach($attachment);
 	$mailer->send($message); 
 }catch (\Exception $e) {
	 
	 $msg = $e->getMessage();
	 OCP\JSON::error(array("data" => array('message' => $msg)));
	 return;
 }


OCP\JSON::success(array("data" => array('message' => $msg)));
