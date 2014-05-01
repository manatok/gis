<?php
namespace Gis\Core\Connection;

/**
 *
 * @author robertadams
 *        
 */
interface ITransactionManager 
{
	function start();
	function commit();
	function rollback();
}
