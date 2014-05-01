<?php
namespace Gis\Domain\Spatial\Model\Point;

use Gis\Core\DataProvider\IDataProvider;
use Gis\Core\Domain\BaseDomainObject;
use Gis\Core\Domain\IPersistableDomainObject;
use Gis\Domain\Spatial\TransferObject\ISpatialObject;


/**
 * Maintains this business logic for working
 * with a Point
 */
class Point extends BaseDomainObject implements IPersistableDomainObject
{
	/**
	 * Construct this DomainObject with its backing DP
	 *
	 * @param IPointDataProvider $dataProvider
	 */
	public function __construct(IPointDataProvider $dataProvider)
	{
		$this->setDataProvider($dataProvider);
	}

	/**
	 * Used to create a new DataStruct ascosiated withi this DO
	 * @return  PointStruct
	 */
	public function createEmptyDataStruct()
	{
		return new PointStruct();
	}

	/**
	 * Used to insert/update the current
	 * DomainObject instance.
	 */
	public function save()
	{
		if (is_null($this->getDataStruct()->id)) {
			$this->getDataStruct()->id = $this->getDataProvider()->insert($this->getDataStruct());
		} else {
			$this->getDataProvider()->update($this->getDataStruct());
		}
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->getDataStruct()->id = $id;
	}

	/**
	 * @return int $id
	 */
	public function getId()
	{
		return $this->getDataStruct()->id;
	}

	/**
	 * @param int $setId
	 */
	public function setSetId($setId)
	{
		$this->getDataStruct()->setId = $setId;
	}

	/**
	 * @return int $setId
	 */
	public function getSetId()
	{
		return $this->getDataStruct()->setId;
	}

	/**
	 * Used to store all of the coordinates
	 * @return ISpatialObject
	 */
	public function getSpatialObject()
	{
		return $this->getDataStruct()->spatialObject;
	}

	public function setSpatialObject(ISpatialObject $so)
	{
		$this->getDataStruct()->spatialObject = $so;
	}
}