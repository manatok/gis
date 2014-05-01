<?php

namespace Gis\Core\Connection;

/**
 * Provides {@link IConnection} instances.
 * Implemetations should decide which IConnection instance to return.
 */
interface IWriteConnectionFactory
{
	/**
	 * @return IConnection - an instantiated Read/Write IConnection which is ready to use.
	 */
	function getWriteConnection();
}
