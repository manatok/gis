<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\DataProvider\IDataProvider;
use Gis\Core\Domain\BaseDomainObject;
use Gis\Core\Domain\IPersistableDomainObject;


/**
 * Maintains this business logic for working
 * with a Layer
 */
class Layer extends BaseDomainObject implements IPersistableDomainObject
{
	private $type;

	/**
	 * Construct this DomainObject with its backing DP
	 *
	 * @param ILayerDataProvider $dataProvider
	 */
	public function __construct(ILayerDataProvider $dataProvider)
	{
		$this->setDataProvider($dataProvider);
	}

	/**
	 * Used to create a new DataStruct ascosiated withi this DO
	 * @return  LayerStruct
	 */
	public function createEmptyDataStruct()
	{
		return new LayerStruct();
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
	public function getDescription()
	{
		return $this->getDataStruct()->name;
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
	 * @param string $minZone
	 */
	public function setMinZone($minZone)
	{
		$this->getDataStruct()->minZone = $minZone;
	}

	/**
	 * @return string $minZone
	 */
	public function getMinZone()
	{
		return $this->getDataStruct()->minZone;
	}

	/**
	 * @param string $maxZone
	 */
	public function setMaxZone($maxZone)
	{
		$this->getDataStruct()->maxZone = $maxZone;
	}

	/**
	 * @return string $maxZone
	 */
	public function getMaxZone()
	{
		return $this->getDataStruct()->maxZone;
	}

	/**
	 * @param int $limitScale
	 */
	public function setLimitScale($limitScale)
	{
		$this->getDataStruct()->limitScale = $limitScale;
	}

	/**
	 * @return int $limitScale
	 */
	public function getLimitScale()
	{
		return $this->getDataStruct()->limitScale;
	}

	/**
	 * @param string $colour
	 */
	public function setColour($colour)
	{
		$this->getDataStruct()->colour = $colour;
	}

	/**
	 * @return string $colour
	 */
	public function getColour()
	{
		return $this->getDataStruct()->colour;
	}

	/**
	 * @TODO - This needs to be refactored. The type needs to live on the layer table.
	 * @return int
	 */
	public function getType()
	{
		if(!isset($this->type)) {
			$this->type = $this->getDataProvider()->getLayerType($this->getId());
		}

		return $this->type;
	}

}