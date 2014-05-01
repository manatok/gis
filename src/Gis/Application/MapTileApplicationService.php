<?php
namespace Gis\Application;

use Gis\Core\Application\IApplicationService;
use Gis\Domain\Spatial\Service\MapTile;
use Gis\Domain\Spatial\Model\Layer\LayerFactory;

/**
 * Used for fetching configured tiles. This will set up any configuration
 * e.g which layers you may want to include, and then make use of the underlying
 * domain services.
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class MapTileApplicationService implements IApplicationService
{
	/**
	 * Render a tile image
	 * @param  int $width
	 * @param  int $height
	 * @param  int $x  see the MapTile for more info
	 * @param  int $y  see the MapTile for more info
	 * @param  int $zoom
	 * @return Content-type: image/png
	 */
	public function getDemoTile($width, $height, $x, $y, $zoom)
	{
		$layers = array();
		$layerFactory = new LayerFactory();
		$layers[] = $layerFactory->getById(1);
		$map = new MapTile($width, $height);
		$map->get($layers, $x, $y, $zoom);
	}
}