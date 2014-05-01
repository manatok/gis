<?php
namespace Gis\Domain\Spatial\TransferObject;

interface ISpatialObject extends \Iterator
{
	function setType($type);
	function getType();
	function setName($name);
	function getName();
	function addCoordToPart(Coord $coord, $part = null);
	function setMinX($x);
	function getMinX();
	function setMinY($y);
	function getMinY();
	function setMaxX($x);
	function getMaxX();
	function setMaxY($y);
	function getMaxY();
}