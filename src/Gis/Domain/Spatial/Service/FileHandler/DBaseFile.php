<?php
namespace Gis\Domain\Spatial\Service\FileHandler;

use Gis\Core\Domain\IDomainService;

/**
 * Allow easy access to the data stored in a .dbf file
 *
 * @author David Berliner <dsberliner@gmail.com>
 */
class DBaseFile implements IDomainService
{
    private $filepath;
    private $filehandle;
    private $headers;
    private $fields = array();
    private $unpackString;

    /**
     * @param string $filepath
     */
    public function __construct($filepath) 
    {
        $this->filepath = $filepath;
        $this->openHandle();
    }

    /**
     * open a read handle on the file.
     * @return void
     */
    private function openHandle()
    {
        $this->filehandle = fopen($this->filepath, 'r');
    }

    /**
     * The header stores info on how many records there are,
     * how long they are and where the first one starts in the file.
     * 
     * @return mixed headers
     */
    public function getHeaders()
    {
        if(isset($this->headers)) {
            return $this->headers;
        }

        $this->readHeaders();

        return $this->headers;
    }

    /**
     * @return int the total number of records in the file
     */
    public function getRecordCount()
    {
        return $this->getHeaders()['RecordCount'];
    }

    /**
     * @return int length in bytes
     */
    public function getRecordLength()
    {
        return $this->getHeaders()['RecordLength'];
    }

    /**
     * Fetch the record at index i that corresponds to the .shp file position
     * @param  int $i
     * @return array
     */
    public function getField($i)
    {
        if(empty($this->fields)) {
            $this->readHeaders();
        }
        $position = ($i-1) * $this->headers['RecordLength'] + $this->headers['FirstRecord'] + 1;
        fseek($this->filehandle, $position); // move back to the start of the first record (after the field definitions) 
        $buf = fread($this->filehandle,$this->headers['RecordLength']); 
        $record=unpack($this->unpackString, $buf); 

        return $record;
    }    

    /**
     * Read and unpack all of the headers from  the file.
     * @return void
     */
    private function readHeaders()
    {   
        if(!$this->filehandle) {
            $this->openHandle();
        }

        $buf = fread($this->filehandle,32); 
        $this->headers = unpack( "VRecordCount/vFirstRecord/vRecordLength", substr($buf,4,8)); 
        $this->unpackString ='';

        //read all the field definitions
        while (!feof($this->filehandle)) {
            $buf = fread($this->filehandle,32); 
            if (substr($buf,0,1) == chr(13)) {
                break;
            } 
            else { 
                $field=unpack("a11fieldname/A1fieldtype/Voffset/Cfieldlen/Cfielddec", substr($buf,0,18)); 
                $this->unpackString.="A$field[fieldlen]$field[fieldname]/"; 
                array_push($this->fields, $field);
            }
        }
    }
}