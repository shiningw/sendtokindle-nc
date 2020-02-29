<?php

/**
 * ownCloud - ocDownloader
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the LICENSE file.
 *
 * @author Xavier Beurois <www.sgc-univ.net>
 * @copyright Xavier Beurois 2015
 */

namespace OCA\SendtoKindle\AppInfo;
use OCP\AppFramework\App;
use OCP\IContainer;
use OCA\SendtoKindle\Controller\Kindle;

class Application extends App {

    public function __construct(array $URLParams = array()) {
        parent::__construct('sendtokindle', $URLParams);
        $container = $this->getContainer();
        $container->registerService('CurrentUID', function (IContainer $Container) {
            $User = $Container->query('ServerContainer')->getUserSession()->getUser();
            return($User) ? $User->getUID() : '';
        });

        $container->registerService('KindleController', function (IContainer $Container) {
            return new Kindle(
                    $Container->query('AppName'), $Container->query('Logger'), $Container->query('Request'), $Container->query('CurrentUID')
            );
        });
    }

}