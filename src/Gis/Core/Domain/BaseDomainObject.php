<?php

namespace Gis\Core\Domain;

use Gis\Core\DataProvider\IDataProvider;
use Gis\Core\DataProvider\IDataStruct;

/**
 * @author David Berliner <dsberliner@gmail.com>
 */
abstract class BaseDomainObject implements IDomainObject
{
	private $dataStruct;
	private $dataProvider;

	/**
	 * Construct this DomainObject with its backing DP
	 *
	 * @param IDataProvider $dataProvider
	 */
	public function __construct(IDataProvider $dataProvider)
	{
		$this->setDataProvider($dataProvider);
	}

	/**
	 * @return IDataStruct
	 */
	public function getDataStruct()
	{
		if (is_null($this->dataStruct))
		{
			$this->dataStruct = $this->createEmptyDataStruct();
		}

		return $this->dataStruct;
	}

	/**
	 * @param IDataStruct $dataStruct
	 */
	public function setDataStruct(IDataStruct $dataStruct)
	{
		$this->dataStruct = $dataStruct;
	}

    /**
     * The dataProvider used to persist this DO
     * @param IDataProvider $dataProvider
     */
    protected function setDataProvider(IDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * The dataProvider used to persist this DO
     * @return IDataProvider
     */
    protected function getDataProvider()
    {
        return $this->dataProvider;
    }

	/**
	 * @return IDataStruct
	 */
	abstract function createEmptyDataStruct();
}
