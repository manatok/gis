<?php
namespace Gis\Domain\Spatial\Model\Polygon;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\DataProvider\IDataProvider;
use Gis\Domain\Spatial\TransferObject\SpatialObject;
use Gis\Domain\Spatial\TransferObject\Coord;

/**
 *  PolygonFactory
 *  Create instances of Polygon by injecting
 *  in all of its dependencies.
 */
class PolygonFactory implements IPolygonFactory
{
	private $domainDataProvider;

	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new PolygonDataProvider(new GisConnectionFactory());
		$this->setDomainDataProvider($dataProvider);
	}

	/**
	 * @return IDataProvider The provider to use for this domain object.
	 */
	private function getDomainDataProvider()
	{
		return $this->domainDataProvider;
	}

	/**
	 * @param IDataProvider $domainDataProvider
	 */
	private function setDomainDataProvider(IDataProvider $domainDataProvider)
	{
		$this->domainDataProvider = $domainDataProvider;
	}

	/**
	 * Will create a constructed instance of an Polygon from
     * a PolygonStruct
	 * @param	IDataStruct $struct
	 * @return	Polygon
	 */
	public function create(IDataStruct $struct = null)
	{
		$polygon = new Polygon($this->getDomainDataProvider());
		$struct = is_null($struct) ? new PolygonStruct() : $struct;
		$polygon->setDataStruct($struct);
		return $polygon;
	}

	/**
	 * Fetch all of the Polygons that belong to this layer and fall within
	 * the specified bounds.
	 * 
	 * @param  int $layerId
	 * @param  Coord $boundsMin
	 * @param  Coord $boundsMax
	 * @return Polygon[]
	 */
	public function getByLayerInBounds($layerId, $boundsMin, $boundsMax)
	{
		$structs = $this->getDomainDataProvider()->getByLayerInBounds($layerId, $boundsMin, $boundsMax);
		$polygons = $tmpStructs = array();

		$prevSetId = 0;

		foreach($structs as $struct) {
			$tmpStructs[$struct->setId][] = $struct;
		}
		
		unset($structs);

		foreach($tmpStructs as $setId => $structs) {
			$spatialObject = new SpatialObject();

			foreach($structs as $struct) {
				$gx=unpack('d*',substr($struct->latLongBin,13));
				$j=1;
			
				while ($j<count($gx))
				{ 
					$coord = new Coord();
					$coord->setX($gx[$j]);
					$coord->setY($gx[$j+1]);
					$j += 2;

					$spatialObject->addCoordToPart($coord, $struct->part);
				}
			}
			
			$polyStruct = new PolygonStruct();
			$polyStruct->id = $struct->id;
			$polyStruct->setId = $struct->setId;
			$polyStruct->spatialObject = $spatialObject;

			$polygons[] = $this->create($polyStruct);
		}

		return $polygons;
	}
}
