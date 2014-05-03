<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\DataProvider\IDataStruct;

/**
 * A camelCasse mapping of the layers table
 */
class LayerStruct implements IDataStruct
{
	public $id;
	public $name;
	public $minX;
	public $minY;
	public $maxX;
	public $maxY;
	public $color;
	public $enabled;
	public $position;
	public $zoomMin;
	public $zoomMax;
}
