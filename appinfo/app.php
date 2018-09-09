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

//namespace OCA\sendtokindle\AppInfo;

$l = \OC::$server->getL10N('sendtokindle');

\OC::$server->getNavigationManager()->add([
    'id' => 'sendtokindle',
    'order' => 11,
    'name' => $l->t('send to kindle'),
    'appname' => 'sendtokindle',
    'script' => 'list.php',
    
]);

OCP\Util::addScript('sendtokindle', 'app');
OCP\Util::addStyle('sendtokindle', 'style');


