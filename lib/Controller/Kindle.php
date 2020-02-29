<?php

namespace OCA\SendtoKindle\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;
use OCP\Http\Client\IClientService;
use OCP\ILogger;

class Kindle extends Controller {

    public function __construct($AppName, ILogger $logger, IRequest $Request, $uid) {
        parent::__construct($AppName, $Request);
        $this->uid = $uid;
        $this->logger = $logger;
        $folder = isset($_POST['dir']) ? $_POST['dir'] : '/';
        $this->filename = $filename = (string) $_POST['file'];
        $this->folder = trim($folder, '/');
        $this->file = $folder . '/' . $filename;
        $this->init();
    }

    protected function init() {

        $systemConfig = \OC::$server->getSystemConfig();
        $this->email = $systemConfig->getValue('kdeFromAddr');
        $this->to = $systemConfig->getValue('kdeToAddr');

        if (!$this->email || !$this->to) {

            $msg = "no from or target email address is set";
            return JSONResponse(array('status' => 'error', 'data' => array('message' => $msg)));
        }
    }

    public function mail($data) {
        extract($data);
        try {
            $mailer = \OC::$server->getMailer();
            $message = $mailer->createMessage();
            $attachment = $mailer->createAttachmentFromPath($file)->setFilename($name);
            $message->setSubject($subject);
            $message->setFrom(array($fromAddr => $fromAddr));
            $message->setTo(array($toAddr => $toAddr));
            $message->setBody($name, 'text/html');
            $message->attach($attachment);
            $resp = $mailer->send($message);
            return $resp;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return JSONResponse(array('status' => 'error', 'data' => array('message' => $msg)));
        }
    }

    public function send() {

        $file = $this->getAbsoluteFilePath();


        if (!file_exists($file)) {

            $msg = sprintf("%s does not exist on the server",$file);
            $this->logger->error($msg);
            return JSONResponse(array('status' => 'error', 'data' => array('message' => $msg)));
        } else {
            $mailInfo = array(
                'file' => $file,
                'name' => basename($file),
                'subject' => 'Send to Kindle',
                'fromAddr' => $this->email,
                'toAddr' => $this->to,
            );
            $data = $this->mail($mailInfo);
        }

        $resp = new JSONResponse(array('data' => $data, 'status' => 'success', 'output' => \OC::$server->getWebRoot()));
        return $resp;
    }

    private function getAbsoluteFilePath() {
        //$systemConfig = \OC::$server->getSystemConfig();
        //$dataDir = $systemConfig->getValue('datadirectory');
        //$username = \OC::$server->getUserSession()->getLoginName();
        return \OC\Files\Filesystem::getLocalFile($this->file);
        //return sprintf("%s/%s/files/%s", $dataDir, $username, $file);
    }

}
