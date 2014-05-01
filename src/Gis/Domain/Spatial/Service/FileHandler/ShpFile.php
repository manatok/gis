<?php
namespace Gis\Domain\Spatial\Service\FileHandler;

use Gis\Domain\Spatial\TransferObject\SpatialObject;
use Gis\Domain\Spatial\TransferObject\Coord;
use Gis\Domain\Spatial\Service\FileHandler\Exception\UnsupportedTypeException;
/**
 * Access the spatial objects within a .shp file
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class ShpFile
{
	private $filepath;
    private $filehandle;
    private $headers;
    private $minX;
    private $minY;
    private $maxX;
    private $maxY;
    private $seekposition;
    private $position;

    const STYPE_POSITION = 32;
    const MIN_X_POSITION = 36;
    const MIN_Y_POSITION = 44;
    const MAX_X_POSITION = 52;
    const MAX_Y_POSITION = 60;
    const DATA_START_POSITION = 100;

    /**
     * @param string $filepath the path to the .shp file
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;
        $this->openHandle();
    }

    /**
     * Open a read handle on the .shp file
     * @return void
     */
    private function openHandle()
    {
        $this->filehandle = fopen($this->filepath, 'r');
    }

    /**
     * Fetch the bounding box of this layer. the min/max x/y values of the
     * spatial objects within the file.
     * 
     * @return array(minx,miny,maxx,maxy)
     */
	public function getBoundingBox()
	{
		return array(
			$this->getXMin(),
			$this->getYMin(),
			$this->getXMax(),
			$this->getYMax()
		);
	}

	/**
	 * get the bounding box minx value
	 * @return float x
	 */
	public function getMinX()
	{
		if(!isset($this->minX)) {
			fseek($this->filehandle, self::MIN_X_POSITION);
			$this->minX = $this->readAndUnpack("d", fread($this->filehandle, 8));
		}

		return $this->minX;
	}

	/**
	 * get the bounding box miny value
	 * @return float y
	 */
	public function getMinY()
	{
		if(!isset($this->minY)) {
			fseek($this->filehandle, self::MIN_Y_POSITION);
			$this->minY = $this->readAndUnpack("d", fread($this->filehandle, 8));
		}

		return $this->minY;
	}

	/**
	 * get the bounding box maxx value
	 * @return float x
	 */
	public function getMaxX()
	{
		if(!isset($this->maxX)) {
			fseek($this->filehandle, self::MAX_X_POSITION);
			$this->maxX = $this->readAndUnpack("d", fread($this->filehandle, 8));
		}

		return $this->maxX;
	}

	/**
	 * get the bounding box maxy value
	 * @return float y
	 */
	public function getMaxY()
	{
		if(!isset($this->maxY)) {
			fseek($this->filehandle, self::MAX_Y_POSITION);
			$this->maxY = $this->readAndUnpack("d", fread($this->filehandle, 8));
		}

		return $this->maxY;
	}

	/**
	 * Unpack binary data into a type
	 * 
	 * @param  string $type the format - see http://www.php.net/manual/en/function.pack.php
	 * @param  binary $data
	 * @return mixed
	 */
	private function readAndUnpack($type, $data)
	{
		if(!$data) {
			return $data;
		}

		return current(unpack($type, $data));
	}

	/**
	 * Each time this is call the next spatial object is read from the file
	 * (if it exists) and the file pointer is incremented.
	 * 
	 * @return SpatialObject either a point, polygon or linestring
	 * @todo  implement the TYPE_MULTIPOINT
	 */
	public function getNextSpatialObject()
	{
		// if its the first record we set the starting position
		if(ftell($this->filehandle) < 100) {
			fseek($this->filehandle, 100);
		} else {
			//if we at the end of the file
			if(feof($this->filehandle)) {
				return false;
			}
		}

		$recordNumber = $this->readAndUnpack("N", fread($this->filehandle, 4));
		$contentLength = $this->readAndUnpack("N", fread($this->filehandle, 4));
		$recordShapeType = $this->readAndUnpack("i", fread($this->filehandle, 4));

		switch ($recordShapeType) {
			case SpatialObject::TYPE_POINT:
				return $this->createPoint();
				break;
			case SpatialObject::TYPE_LINESTRING:
				return $this->createLinestring();
				break;
			case SpatialObject::TYPE_POLYGON:
				return $this->createPolygon();
				break;
			default: //throw new UnsupportedTypeException($recordShapeType.' is not currently supported');
		}
	}

	/**
	 * @return SpatialObject containing a point
	 */
	private function createPoint()
	{
		$x = $this->readAndUnpack("d", fread($this->filehandle, 8));
		$y = $this->readAndUnpack("d", fread($this->filehandle, 8));
		
		$spatialObject = new SpatialObject();
		$spatialObject->setType(SpatialObject::TYPE_POINT);
		$spatialObject->setMinX($x);
		$spatialObject->setMaxX($x);
		$spatialObject->setMinY($y);
		$spatialObject->setMaxY($y);

		$coord = new Coord();
    	$coord->setX($x);
    	$coord->setY($y);
    	$coord->setType(Coord::TYPE_LAT_LNG);
    	$spatialObject->addCoordToPart($coord);

    	return $spatialObject;
	}

	/**
	 * @return SpatialObject containing a lineString
	 */
	private function createLinestring()
	{
		$spatialObject = new SpatialObject();
		$spatialObject->setType(SpatialObject::TYPE_LINESTRING);
		$spatialObject->setMinX($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMinY($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMaxX($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMaxY($this->readAndUnpack("d",fread($this->filehandle, 8)));

		$rec_numparts = $this->readAndUnpack("i", fread($this->filehandle, 4));
        $rec_numpoints = $this->readAndUnpack("i", fread($this->filehandle, 4));

        $pnt_dx=array();
		for($i=0; $i<$rec_numparts; $i++) {
        	$pnt_dx[$i] = $this->readAndUnpack("i", fread($this->filehandle, 4));
        }

        $points_read = 0;
        $part = 0;

        while($points_read < $rec_numpoints && !feof($this->filehandle)) {
        	$x = $this->readAndUnpack("d", fread($this->filehandle, 8));
        	$y = $this->readAndUnpack("d", fread($this->filehandle, 8));
        	
        	if ($points_read > 0) {
        		if (in_array($points_read, $pnt_dx)) {
        			$part++;
        		}
        	}

        	$coord = new Coord();
        	$coord->setX($x);
        	$coord->setY($y);
        	$coord->setType(Coord::TYPE_LAT_LNG);
        	$spatialObject->addCoordToPart($coord, $part);

        	$points_read++;
        }
        
        return $spatialObject;
	}

	/**
	 * @return SpatialObject containing a polygon
	 */
	private function createPolygon()
	{
		$spatialObject = new SpatialObject();
		$spatialObject->setType(SpatialObject::TYPE_POLYGON);
		$spatialObject->setMinX($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMinY($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMaxX($this->readAndUnpack("d",fread($this->filehandle, 8)));
		$spatialObject->setMaxY($this->readAndUnpack("d",fread($this->filehandle, 8)));

		$rec_numparts = $this->readAndUnpack("i", fread($this->filehandle, 4));
        $rec_numpoints = $this->readAndUnpack("i", fread($this->filehandle, 4));

        $pnt_dx=array();
		for($i=0; $i<$rec_numparts; $i++) {
        	$pnt_dx[$i] = $this->readAndUnpack("i", fread($this->filehandle, 4));
        }

        $points_read = 0;
        $part = 0;

        while($points_read < $rec_numpoints && !feof($this->filehandle)) {
        	$x = $this->readAndUnpack("d", fread($this->filehandle, 8));
        	$y = $this->readAndUnpack("d", fread($this->filehandle, 8));

        	if ($points_read > 0) {
        		if (in_array($points_read, $pnt_dx)) {
        			$part++;
        		}
        	}

        	$coord = new Coord();
        	$coord->setX($x);
        	$coord->setY($y);
        	$coord->setType(Coord::TYPE_LAT_LNG);
        	$spatialObject->addCoordToPart($coord, $part);

        	$points_read++;
        }

        return $spatialObject;
	}
}