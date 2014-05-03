<?php
namespace Gis\Domain\Spatial\Model\Layer;

use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\Domain\BaseRetrievableDomainObjectFactory;
use Gis\Core\DataProvider\IDataStruct;

/**
 *  LayerFactory
 *  Create instances of Layer by injecting
 *  in all of its dependencies.
 */
class LayerFactory extends BaseRetrievableDomainObjectFactory implements ILayerFactory
{
	/**
	 * Used to construct the DataProvider needed by this factory
	 */
	public function __construct()
	{
		$dataProvider = new LayerDataProvider(new GisConnectionFactory());
		parent::__construct($dataProvider);
	}

	/**
	 * Will create a constructed instance of an Layer from
     * a LayerStruct
	 * @param	IDataStruct $struct
	 * @return	Layer
	 */
	public function create(IDataStruct $struct = null)
	{
		$layer = new Layer($this->getDomainDataProvider());
		$struct = is_null($struct) ? new LayerStruct() : $struct;
		$layer->setDataStruct($struct);
		return $layer;
	}

	/**
	 * Fetch all of the enabled layers ordered by position,id
	 * @return Layer[]
	 */
	public function getActive($zoom)
	{
		$structs = $this->getDomainDataProvider()->getActive($zoom);
		return $this->createDataObjects($structs);
	}
}
