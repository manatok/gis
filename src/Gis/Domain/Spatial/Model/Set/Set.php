<?php
namespace Gis\Domain\Spatial\Model\Set;

use Gis\Core\DataProvider\IDataProvider;
use Gis\Core\Domain\BaseDomainObject;
use Gis\Core\Domain\IPersistableDomainObject;


/**
 * Maintains this business logic for working
 * with a Set
 */
class Set extends BaseDomainObject implements IPersistableDomainObject
{
	/**
	 * Construct this DomainObject with its backing DP
	 *
	 * @param ISetDataProvider $dataProvider
	 */
	public function __construct(ISetDataProvider $dataProvider)
	{
		$this->setDataProvider($dataProvider);
	}

	/**
	 * Used to create a new DataStruct ascosiated withi this DO
	 * @return  SetStruct
	 */
	public function createEmptyDataStruct()
	{
		return new SetStruct();
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
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->getDataStruct()->name = $name;
	}

	/**
	 * @return string $name
	 */
	public function getName()
	{
		return $this->getDataStruct()->name;
	}

	/**
	 * @param int $layer
	 */
	public function setLayer($layer)
	{
		$this->getDataStruct()->layer = $layer;
	}

	/**
	 * @return int $layer
	 */
	public function getLayer()
	{
		return $this->getDataStruct()->layer;
	}

	/**
	 * @param int $type
	 */
	public function setType($type)
	{
		$this->getDataStruct()->type = $type;
	}

	/**
	 * @return int $type
	 */
	public function getType()
	{
		return $this->getDataStruct()->type;
	}

	/**
	 * @param double $minX
	 */
	public function setMinX($minX)
	{
		$this->getDataStruct()->minX = $minX;
	}

	/**
	 * @return double $minX
	 */
	public function getMinX()
	{
		return $this->getDataStruct()->minX;
	}

	/**
	 * @param double $minY
	 */
	public function setMinY($minY)
	{
		$this->getDataStruct()->minY = $minY;
	}

	/**
	 * @return double $minY
	 */
	public function getMinY()
	{
		return $this->getDataStruct()->minY;
	}

	/**
	 * @param double $maxX
	 */
	public function setMaxX($maxX)
	{
		$this->getDataStruct()->maxX = $maxX;
	}

	/**
	 * @return double $maxX
	 */
	public function getMaxX()
	{
		return $this->getDataStruct()->maxX;
	}

	/**
	 * @param double $maxY
	 */
	public function setMaxY($maxY)
	{
		$this->getDataStruct()->maxY = $maxY;
	}

	/**
	 * @return double $maxY
	 */
	public function getMaxY()
	{
		return $this->getDataStruct()->maxY;
	}

	/**
	 * @param int $minUtm
	 */
	public function setMinUtm($minUtm)
	{
		$this->getDataStruct()->minUtm = $minUtm;
	}

	/**
	 * @return int $minUtm
	 */
	public function getMinUtm()
	{
		return $this->getDataStruct()->minUtm;
	}

	/**
	 * @param int $minZone
	 */
	public function setMinZone($minZone)
	{
		$this->getDataStruct()->minZone = $minZone;
	}

	/**
	 * @return int $minZone
	 */
	public function getMinZone()
	{
		return $this->getDataStruct()->minZone;
	}

	/**
	 * @param int $maxUtm
	 */
	public function setMaxUtm($maxUtm)
	{
		$this->getDataStruct()->maxUtm = $maxUtm;
	}

	/**
	 * @return int $maxUtm
	 */
	public function getMaxUtm()
	{
		return $this->getDataStruct()->maxUtm;
	}

	/**
	 * @param int $maxZone
	 */
	public function setMaxZone($maxZone)
	{
		$this->getDataStruct()->maxZone = $maxZone;
	}

	/**
	 * @return int $maxZone
	 */
	public function getMaxZone()
	{
		return $this->getDataStruct()->maxZone;
	}



}