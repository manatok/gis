<?php
namespace Gis\Domain\Spatial\TransferObject;

/**
 * Used to store the x/y position of a coordinate
 * At the moment only LAT_LNG types are supported, at somepoint
 * we may introduce some mutators for switching between utm and lat_lng
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class Coord
{
	private $x;
	private $y;
	private $type;

	const TYPE_LAT_LNG = 1;
	const TYPE_UTM = 2;

	/**
	 * @param int $type 
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return float
	 */
	public function getX()
	{
		return $this->x;
	}

	/**
	 * @param float $x
	 */
	public function setX($x)
	{
		$this->x = $x;
	}

	/**
	 * @return float
	 */
	public function getY()
	{
		return $this->y;
	}

	/**
	 * @return float $y
	 */
	public function setY($y)
	{
		$this->y = $y;
	}
}