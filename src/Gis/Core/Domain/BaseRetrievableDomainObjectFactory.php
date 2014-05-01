<?php

namespace Gis\Core\Domain;

use Gis\Core\Domain\Exception\MissingDomainObjectException;
use Gis\Core\DataProvider\IRetrievableDataProvider;

/**
 * Default IRetrievableDomainObjectFactory implementation.
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
abstract class BaseRetrievableDomainObjectFactory implements IRetrievableDomainObjectFactory
{
	/**
	 * 
	 * @var IRetrievableDataProvider
	 */
	private $domainDataProvider;

	/**
	 * Creates a new BaseRetrievableDomainObjectFactory associating it with
	 * a IRetrievableDataProvider.
	 *
	 * @param IRetrievableDataProvider $domainDataProvider
	 */
	protected function __construct(IRetrievableDataProvider $domainDataProvider)
	{
		$this->setDomainDataProvider($domainDataProvider);
	}

	/**
	 * @return IRetrievableDataProvider The provider to use for this domain object.
	 */
	protected function getDomainDataProvider()
	{
		return $this->domainDataProvider;
	}

	/**
	 * @param IRetrievableDataProvider $domainDataProvider
	 */
	protected function setDomainDataProvider(IRetrievableDataProvider $domainDataProvider)
	{
		$this->domainDataProvider = $domainDataProvider;
	}

	/**
	 * @param mixed $id
	 * 
	 * @throws MissingDomainObjectException If we cannot find this entity
	 * 
	 * @return IDomainObject
	 */
	public function getById($id)
	{
		/* get the struct representing the domain object identified by $id */
		$dataStruct = $this->getDomainDataProvider()->getById($id);

		if (is_null($dataStruct)) {
			throw new MissingDomainObjectException();
		}

		$domainObject = $this->create($dataStruct);

		return $domainObject;
	}

	/**
	 * This will convert an array of DataStructs into an array of
	 * DomainObjects. This is just a helper function.
	 *
	 * @param mixed $_structArray[]
	 * @return IDomainObject[] representing this entity
	 */
	public function createDataObjects($structArray)
	{
		$domainObjects = array();

		if (!is_null($structArray))
		{
			foreach ($structArray as $struct)
			{
				$domainObjects[] = $this->create($struct);
			}
		}

		return $domainObjects;
	}	
}
