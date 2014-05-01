<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\DataProvider\IRetrievableDataProvider;
use Gis\Core\DataProvider\IPersistableDataProvider;

/**
 * Force the implementing dataprovider to use the functions defined below.
 *
 * Since this extends IRetrievableDataProvider and IPersistableDataProvider the
 * dataprovider will need to implement a:
 * 
 * function getById(int) 
 * function insert(IDataStruct $struct) 
 * function update(IDataStruct $struct)
 */
interface ILayerDataProvider extends IRetrievableDataProvider, IPersistableDataProvider
{
	/**
	 * @param  int $layerId [description]
	 * @return int Constants defined in TransferObject\SpatialObject
	 */
	public function getLayerType($layerId);
}
