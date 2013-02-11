<?php
/*
 * Copyright (c) 2013, webvariants GbR, http://www.webvariants.de
 *
 * This file is released under the terms of the MIT license. You can find the
 * complete text in the attached LICENSE file or online at:
 *
 * http://www.opensource.org/licenses/mit-license.php
 */

if (PHP_SAPI !== 'cli') {
	die('This script must be run from CLI.');
}

$travis = getenv('TRAVIS') !== false;
$here   = dirname(__FILE__);
$root   = dirname($here);

// define vital paths
define('SLY_BASE',          $root);
define('SLY_DEVELOPFOLDER', $here.DIRECTORY_SEPARATOR.'develop');
define('SLY_MEDIAFOLDER',   $here.DIRECTORY_SEPARATOR.'mediapool');
define('SLY_ADDONFOLDER',   $here.DIRECTORY_SEPARATOR.'addons');
define('SLY_VENDORFOLDER',  $root.DIRECTORY_SEPARATOR.'vendor');
define('SLY_DATAFOLDER',    $here.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'run-'.uniqid());

if (!is_dir(SLY_MEDIAFOLDER)) mkdir(SLY_MEDIAFOLDER);
if (!is_dir(SLY_ADDONFOLDER)) mkdir(SLY_ADDONFOLDER);
if (!is_dir(SLY_DATAFOLDER))  mkdir(SLY_DATAFOLDER, 0777, true);

// set our own config folder
if ($travis) {
	define('SLY_CONFIGFOLDER', $here.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'travis');
}
else {
	define('SLY_CONFIGFOLDER', $here.DIRECTORY_SEPARATOR.'config');
}

// load core system
$slyAppName = 'tests';
$slyAppBase = 'tests';
require $here.'/../master.php';
$container = sly_Core::getContainer();

// do not overwrite config or write the cachefile
$container->getConfig()->setFlushOnDestruct(false);

// configure autoloader
$loader = $container->getClassLoader();
$loader->add('sly_', $here.DIRECTORY_SEPARATOR.'lib');
$loader->add('sly_', $here.DIRECTORY_SEPARATOR.'tests');

// init the app
$app = new sly_App_Tests($container, 1);
$container->set('sly-app', $app);
$app->initialize();
