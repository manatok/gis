<?php
namespace Gis\Domain\Spatial\Model\Linestring;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\DataProvider\IDataStruct;
use Gis\Core\DataProvider\IDataProvider;
use Gis\Domain\Spatial\TransferObject\SpatialObject;
use Gis\Domain\Spatial\TransferObject\Coord;

/**
 *  LinestringFactory
 *  Create instances of Linestring by injecting
 *  in all of its dependencies.
 */
class LinestringFactory implements ILinestringFactory
{
	private $domainDataProvider;

	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new LinestringDataProvider(new GisConnectionFactory());
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
	 * Will create a constructed instance of an Linestring from
     * a LinestringStruct
	 * @param	IDataStruct $struct
	 * @return	Linestring
	 */
	public function create(IDataStruct $struct = null)
	{
		$linestring = new Linestring($this->getDomainDataProvider());
		$struct = is_null($struct) ? new LinestringStruct() : $struct;
		$linestring->setDataStruct($struct);
		return $linestring;
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
		$linestrings = array();

		$prevSetId = 0;

		foreach($structs as $struct) {
			if($struct->setId != $prevSetId) {
				$spatialObject = new SpatialObject();
				$prevSetId = $struct->setId;
			}

			$gx=unpack('d*',substr($struct->latLongBin,9));
			$j=1;
		
			while ($j<count($gx))
			{ 
				$coord = new Coord();
				$coord->setX($gx[$j]);
				$coord->setY($gx[$j+1]);
				$j += 2;

				$spatialObject->addCoordToPart($coord, $struct->part);
			}

			$linestringStruct = new LinestringStruct();
			$linestringStruct->id = $struct->id;
			$linestringStruct->setId = $struct->setId;
			$linestringStruct->spatialObject = $spatialObject;

			$linestrings[] = $this->create($linestringStruct);
		}

		return $linestrings;
	}
}
