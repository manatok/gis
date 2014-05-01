<?php
ini_set('memory_limit', '1000M');
require_once 'includes/autoload.inc.php';
require_once 'WebExample.php';

use example\WebExample;

$example = new WebExample();

/**
 * Some basic routing for the demo.
 */
switch($_GET['action'])
{
	case 'filelist': 	$example->listFiles();
						break;
	case 'preview':		
						$example->preview($_GET['fname']);
						break;
	case 'load':		
						$example->insert($_GET['fname']);
						break;
	case 'draw':		
						$example->getTile();
						break;
	case 'map':		
						$example->map();
						break;

	default : $example->listFiles();
}


