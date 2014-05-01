<?php
namespace Gis\Domain\Spatial\Service;

use Gis\Core\Domain\IDomainService;
use Gis\Domain\Spatial\Service\FileHandler\ShpFile;
use Gis\Domain\Spatial\Service\FileHandler\DBaseFile;
use Gis\Domain\Spatial\Model\Layer\LayerFactory;
use Gis\Domain\Spatial\Model\Set\SetFactory;
use Gis\Domain\Spatial\Model\Attribute\AttributeFactory;
use Gis\Domain\Spatial\Model\Polygon\PolygonFactory;
use Gis\Domain\Spatial\Model\Point\PointFactory;
use Gis\Domain\Spatial\Model\Linestring\LinestringFactory;
use Gis\Domain\Spatial\TransferObject\SpatialObject;

/**
 * @author David Berliner <dsberliner@gmail.com>
 */
class Layer implements IDomainService
{
	/**
	 * import a shp & dbf file into the db
	 * 
	 * @param  string $layerName
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
		//allows easy access to the data in the shp file
		$shpFile = new ShpFile($shpFile);
		//allows easy access to the data in the dbf file
		$dBaseFile = new DBaseFile($dbfFile);

		//lets create the layer record fist
		$layerFactory = new LayerFactory();
		$layer = $layerFactory->create();
		$layer->setName($layerName);
		$layer->setMinX($shpFile->getMinX());
		$layer->setMinY($shpFile->getMinY());
		$layer->setMaxX($shpFile->getMaxX());
		$layer->setMaxY($shpFile->getMaxY());
		$layer->save();
		$record = 1;

		while(($spatialObject = $shpFile->getNextSpatialObject())) {
			$metaData = $dBaseFile->getField($record++);
			$setName = trim($metaData[$setReference]);

			$setFactory = new SetFactory();
			$set = $setFactory->create();
			$set->setName($setName);
			$set->setLayer($layer->getId());
			$set->setType($spatialObject->getType());
			$set->setMinX($spatialObject->getMinX());
			$set->setMinY($spatialObject->getMinY());
			$set->setMaxX($spatialObject->getMaxX());
			$set->setMaxY($spatialObject->getMaxY());
			$set->save();

			$attributeFactory = new AttributeFactory();
			$sequence = 0;
			foreach($metaData as $key => $value) {
				$attr = $attributeFactory->create();
				$attr->setSetId($set->getId());
				$attr->setSequence($sequence);
				$attr->setName($key);
				$attr->setValue($value);
				$attr->save();
				$sequence++;
			}

			switch($spatialObject->getType()) {
				case SpatialObject::TYPE_POINT:
					$typeFactory = new PointFactory();
					break;
				case SpatialObject::TYPE_LINESTRING:
					$typeFactory = new LinestringFactory();
					break;
				case SpatialObject::TYPE_POLYGON:
					$typeFactory = new PolygonFactory();
					break;
				case SpatialObject::TYPE_MULTIPOINT:
					//@TODO implement this
					break;
			}

			$domainObject = $typeFactory->create();
			$domainObject->setSpatialObject($spatialObject);
			$domainObject->setSetId($set->getId());
			$domainObject->save();
		}
	}
}