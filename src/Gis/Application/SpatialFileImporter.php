<?php
namespace Gis\Application;

use Gis\Core\Application\IApplicationService;
use Gis\Core\Connection\GisConnectionFactory;
use Gis\Core\Connection\TransactionManager;
use Gis\Domain\Spatial\Service\Layer;
use Gis\Domain\Spatial\Service\Util\ShpFile;
use Gis\Domain\Spatial\Service\Util\DBaseFile;

/**
 * Imports a .shp file / .dbf file combo
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class SpatialFileImporter implements IApplicationService
{
	/**
	 * A layer is a collection of sets and a set is a collection of spatial data types
	 * e.g (Polygon, Point, MultiPoint, PolyLine)
	 *
	 * A layer is made from 2 files. A .shp file which contains all of the
	 * coordinates and the dbf files which contains all of the meta data
	 * ascosiated with the coordinates.
	 * 
	 * @param  string $layerName What you want to call this layer
	 * @param  string $setReference This is used to save the sets name. The dbf file contains
	 *                              meta data for each spatial point, the $setReference is the
	 *                              name of the meta data value that you want to use as the name
	 *                              of this set.
	 *                              
	 * @param  string $shpFile   The path to the .shp file
	 * @param  string $dbfFile   The path to the accompanying .dbf file
	 * @return void
	 */
	public function importLayer($layerName, $setReference, $shpFile, $dbfFile)
	{
		$connectionFactory = new GisConnectionFactory();
		$transactionManager = new TransactionManager($connectionFactory);
		$transactionManager->start();

		try {
			$layer = new Layer();
			//should do some checking if we have already added this layer here...
			//import the data into the db
			$layer->importLayer($layerName, $setReference, $shpFile, $dbfFile);
			
			//if all goes well
			$transactionManager->commit();
		} catch(\Exception $e) {
			$transactionManager->rollback();
			//If we were a true API we would consume this and set an error
			//in a response object
			throw $e;
		}

	}
}