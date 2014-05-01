<?php
namespace Gis\Domain\Spatial\Model\Set;

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
interface ISetDataProvider extends IRetrievableDataProvider, IPersistableDataProvider
{
	
}
