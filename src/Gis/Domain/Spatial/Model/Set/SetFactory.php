<?php
namespace Gis\Domain\Spatial\Model\Set;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\Domain\BaseRetrievableDomainObjectFactory;
use Gis\Core\DataProvider\IDataStruct;

/**
 *  SetFactory
 *  Create instances of Set by injecting
 *  in all of its dependencies.
 */
class SetFactory extends BaseRetrievableDomainObjectFactory implements ISetFactory
{
	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new SetDataProvider(new GisConnectionFactory());
		parent::__construct($dataProvider);
	}

	/**
	 * Will create a constructed instance of an Set from
     * a SetStruct
	 * @param	IDataStruct $struct
	 * @return	Set
	 */
	public function create(IDataStruct $struct = null)
	{
		$set = new Set($this->getDomainDataProvider());
		$struct = is_null($struct) ? new SetStruct() : $struct;
		$set->setDataStruct($struct);
		return $set;
	}
}
