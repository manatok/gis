<?php

namespace Gis\Core\Domain;

interface IRetrievableDomainObjectFactory extends IDomainObjectFactory
{
	/**
	 * @param mixed $id The id of the domain object to get.
	 * @return IDomainObject The domain object.
	 */
	function getById($id);
}
