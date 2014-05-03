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
	 * @param string $color
	 */
	public function setColor($color)
	{
		$this->getDataStruct()->color = $color;
	}

	/**
	 * @return string $color
	 */
	public function getColor()
	{
		return $this->getDataStruct()->color;
	}

	/**
	 * @param bool $enabled
	 */
	public function setEnabled($enabled)
	{
		$this->getDataStruct()->enabled = $enabled;
	}

	/**
	 * @return bool $enabled
	 */
	public function getEnabled()
	{
		return $this->getDataStruct()->enabled;
	}

	/**
	 * @param int $position
	 */
	public function setPosition($position)
	{
		$this->getDataStruct()->position = $position;
	}

	/**
	 * @return int $position
	 */
	public function getPosition()
	{
		return $this->getDataStruct()->position;
	}

	/**
	 * @param int $zoomMin
	 */
	public function setZoomMin($zoomMin)
	{
		$this->getDataStruct()->zoomMin = $zoomMin;
	}

	/**
	 * @return int $zoomMin
	 */
	public function getZoomMin()
	{
		return $this->getDataStruct()->zoomMin;
	}

	/**
	 * @param int $zoomMax
	 */
	public function setZoomMax($zoomMax)
	{
		$this->getDataStruct()->zoomMax = $zoomMax;
	}

	/**
	 * @return int $zoomMax
	 */
	public function getZoomMax()
	{
		return $this->getDataStruct()->zoomMax;
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