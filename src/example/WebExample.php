<?php
namespace example;

use Gis\Domain\Spatial\Service\FileHandler\DBaseFile;
use Gis\Domain\Spatial\Service\FileHandler\ShpFile;
use Gis\Domain\Spatial\Service\Layer;
use Gis\Application\MapTileApplicationService;
use Gis\Application\SpatialFileImporter;

/**
 * This class is just a wrapper for the demo serves to illustrate how to make
 * use of the Gis application. This could be cleaned up and put inside a proper
 * framework.
 *
 * For now this just makes use of the html snippets in the html folder.
 */
class WebExample
{
	private $uploadPath = 'uploads/';	//Where the .shp files will be uploaded
	private $tilePath = 'tiles/';		//Where the tiles will be cached

	/**
	 * List all of the uploaded files. From here you will be able to preview
	 * or import the file.
	 *
	 * @return  renders html/filelist
	 */
	public function listFiles()
	{
		$files = array();

		if (is_dir($this->uploadPath)) {
			if ($dh = opendir($this->uploadPath)) {
				while (($file = readdir($dh)) !== false) {
					if (($file!=".") && ($file!="..")) {
						array_push($files, $file);
					}
				}
				closedir($dh);
			}
			sort($files);
		}

		$data['files'] = $files;
		$this->render('filelist', $data);
	}

	/**
	 * Used to preview the data inside the shp file / dbh file combo.
	 * This allows you to see what spatial type is stored in the file
	 * e.g (polygons, linestrings, points...) as well as the meta data 
	 * stored in the dbh file.
	 * 
	 * @param  string $fname 	here we just pass in the .shp file path. We make the
	 *                       	assumption that there is a dbh file and that it has
	 *                       	the same name as the shp file.
	 *                       	
	 * @return renders html/preview 
	 */
	public function preview($fname)
	{
		//both files need the same name for the example
		$shpPath = $this->uploadPath.$fname;
		$dbfPath = $this->uploadPath.current(explode('.', $fname)).'.dbf';
		//allows easy access to the data in the shp file
		$shpFile = new ShpFile($shpPath);
		//allows easy access to the data in the dbf file
		$dBaseFile = new DBaseFile($dbfPath);

		$record = 1;
		$data = array();

		//only display the first 10 records
		while(($spatialObject = $shpFile->getNextSpatialObject()) && $record <= 10) {
			$data[$record]['meta'] = $dBaseFile->getField($record);
			$data[$record]['type'] = $spatialObject->getTypeString();
			$data[$record]['maxx'] = $spatialObject->getMaxX();
			$data[$record]['minx'] = $spatialObject->getMinX();
			$data[$record]['maxy'] = $spatialObject->getMaxY();
			$data[$record]['miny'] = $spatialObject->getMinY();
			$record++;
		}

		$this->render('preview', $data);
	}

	/**
	 * This will first present you with a form and when the form is submitted
	 * it will import the selected file into the db. This does no checks to make
	 * sure that you haven't already loaded this file. i.e you could import
	 * the same file multiple times, you would probably want to change this.
	 * 
	 * @param  string $fname
	 * @return renders html/insert
	 */
	public function insert($fname)
	{
		$rawName = current(explode('.', $fname));
		$dbfPath = $this->uploadPath.$rawName.'.dbf';
		$shpPath = $this->uploadPath.$fname;

		//do the insert
		if(isset($_POST['load']) && $_POST['load'] == 'load') {
			$spatialFileImporter = new SpatialFileImporter();
			$spatialFileImporter->importLayer($_POST['name'], $_POST['setref'], $shpPath, $dbfPath);
		} 
		
		$dBaseFile = new DBaseFile($dbfPath);
		$firstRecord = $dBaseFile->getField(1);

		$data['fname'] = $fname;
		$data['name'] = $rawName;

		foreach($firstRecord as $key => $value) {
			$data['setReference'][] = array('name' => $key, 'val' => $value);	
		}
		
		$this->render('insert', $data);
	}

	/**
	 * Render a tile for the map. This will be called by the javascript in html/map
	 * @return renders a PNG
	 */
	public function getTile()
	{
		$mapTile = new MapTileApplicationService();
		$mapTile->getDemoTile($_GET['w'], $_GET['h'], $_GET['x'], $_GET['y'], $_GET['z']);
	}

	/**
	 * Display the map
	 * @return renders html/map
	 */
	public function map()
	{
		$this->render('map', null);
	}

	/**
	 * used to display the html snippet
	 * 
	 * @param  string $page
	 * @param  mixed $data the data that will be passed to the view
	 */
	private function render($page, $data = null)
	{
		include "html/head.php";
		include "html/$page.php";
		include "html/footer.php";
	}
}