<?php
namespace Gis\Domain\Spatial\TransferObject;

/**
 * Used as the backing transfer object of all the spatial domain objects
 * (Found in Gis\Domain\Spatial\Model\ Polygon for example)
 *
 * Contains 1 or more parts, each consisting of 1 or more Coord records.
 *
 * This can probably be refactored or re looked at. Just because we have an 
 * instance of a SpatialObject doesn't mean we know the type or the name or the
 * bounding coordinates, all of these attributes need to be set by the creator and
 * if they are not there is no way of getting them. This object works well for
 * polygons that have multiple points but in the case of a Point it seems a little
 * over the top to have a SpatialObject->part->coord->x when we could just have a 
 * Coord...
 *
 * @author David Berliner <dsberliner@gmail.com>
 *
 */
class SpatialObject implements ISpatialObject
{
	private $type;
	private $name;
	private $position;
	private $minX;
	private $minY;
	private $maxX;
	private $maxY;
	private $parts;
	private $partCount;

	const TYPE_POINT = 1;
	const TYPE_LINESTRING = 3;
	const TYPE_POLYGON = 5;
	const TYPE_MULTIPOINT = 8;

	public function __construct()
	{
		$this->position = 0;
		$this->partCount = 0;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getTypeString()
	{
		switch($this->getType()) {
			case self::TYPE_POINT: return "Point";
			case self::TYPE_LINESTRING: return "PolyLine";
			case self::TYPE_POLYGON: return "Polygon";
			case self::TYPE_MULTIPOINT: return "MultiPoint";
		}
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * A Spatial Object is made up of parts, each part potentially
	 * containing multiple coordinates.
	 * 
	 * @param Coord  $coord
	 * @param int $part The part that this coord belongs to.
	 */
	public function addCoordToPart(Coord $coord, $part = null)
	{
		$part = is_null($part) ? $this->partCount : $part;
		$this->parts[$part][] = $coord;
	}

	/**
	 * @return array(Coord[])
	 */
	public function getParts()
	{
		return $this->parts;
	}

	/**
	 * @param float $x the bounding box min x
	 */
	public function setMinX($x)
	{
		$this->minX = $x;
	}

	/**
	 * @return float the bounding box min x
	 */
	public function getMinX()
	{
		return $this->minX;
	}

	/**
	 * @param float $y the bounding box min y
	 */
	public function setMinY($y)
	{
		$this->minY = $y;
	}

	/**
	 * @return float
	 */
	public function getMinY()
	{
		return $this->minY;
	}

	/**
	 * @param float $x
	 */
	public function setMaxX($x)
	{
		$this->maxX = $x;
	}

	/**
	 * @return float
	 */
	public function getMaxX()
	{
		return $this->maxX;
	}

	/**
	 * @param float $y
	 */
	public function setMaxY($y)
	{
		$this->maxY = $y;
	}

	/**
	 * @return float
	 */
	public function getMaxY()
	{
		return $this->maxY;
	}

	/**
	 * required to be iterable. Reset the counter to 0
	 * @return void
	 */
	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * @return Coords[] that are a part of the current part
	 */
	public function current()
	{
		return $this->parts[$this->position];
	}

	/**
	 * @return int
	 */
	public function key()
	{
		return $this->position;
	}
	
	public function next()
	{
		++$this->position;
	}

	public function valid()
	{
		return isset($this->parts[$this->position]);
	}
}