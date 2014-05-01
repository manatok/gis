<?php

namespace Gis\Core\Domain;

use Gis\Core\DataProvider\IDataStruct;

interface IDomainObjectFactory
{

	/**
	 * @return IDomainObject A new domain object instance.
	 */
	function create(IDataStruct $struct = null);
}
