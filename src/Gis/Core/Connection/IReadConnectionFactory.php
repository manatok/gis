<?php
namespace Gis\Core\Connection;

interface IReadConnectionFactory
{
	/**
	 * @return IConnection - an instantiated ReadOnly IConnection which is ready to use.
	 */
	function getReadConnection();
}
