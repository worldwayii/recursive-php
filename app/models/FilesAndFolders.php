<?php

use App\Core\App;


class FilesAndFolders
{

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * This function reads .txt file
     * 
     */
    public function writeFile()
    {
        $files = App::get('database')->selectAll('files');
        //if we already have records, don't proceed
        if (count($files) > 0) {
            return $files;
        }

        // read txt file
        $file = fopen($this->getFilePath(), "r") or die("Unable to open file!");

        // Output one line until end-of-file
        while (!feof($file)) {
            $line = fgets($file);
            //delete any spaces
            $line = trim($line);
            //delete some characters
            $line = str_replace("C:\\", "", $line);
            //change backslash to slash
            $line = str_replace("\\", "/", $line);
            //get path parts from the string
            $pathParts = pathinfo("$line");
            //split line by comma
            $dirsAndFiles = explode("/", $pathParts['dirname']);
            $lastID = null;
            $count = 0;
            while (count($dirsAndFiles) > 0) {
                $dirOrFile = array_shift($dirsAndFiles);
                //store the dir or file in the database and return the id of the last inserted row
                //this ID is the parent_id of the next row

                $lastID = $this->storeDirOrFileInDB($dirOrFile, Constants::FOLDER, $lastID, $count);
                $count++;
            }
            if (isset($pathParts['extension'])) {
                // this is a file, cos it has an extension
                $this->storeDirOrFileInDB($pathParts['basename'], Constants::FILE, $lastID, $count);
            }
        }
        fclose($file);
    }

    /**
     * Get the file path
     */
    protected function getFilePath()
    {
        return $this->filePath;
    }
    
    /**
     * Stores the directory or file to DB
     * 
     * @return int
     */
    public function storeDirOrFileInDB($dirOrFile, $type, $lastID = null, $level = 0)
    {
        //row if table has record
        $row = App::get('database')
                ->raw("select * FROM files WHERE name = '$dirOrFile' and level = '$level'");
        //check
        if (count($row) > 0) {
            return $row[0]->id;
        }

        $lastID = App::get('database')->insert('files', [
            'name' => $dirOrFile,
            'type' => $type,
            'parent_id' => $lastID,
            'level' => $level
        ]);
        return $lastID;
    }


    public function joinPaths($arrayOfObjects)
    {
        $paths = [];
        foreach ($arrayOfObjects as $object) {
            $path = 'C:';
            $path .= $this->recursiveJoinPath($object, $path);
            $paths[] = $path . '\\' . $object->name;
        }

        return $paths;
    }
   
    protected function recursiveJoinPath($object, $path)
    {
        //checks for parent_id is null
        if (is_null($object->parent_id)) {
            return '';
        }

        $row = App::get('database')
            ->raw("select * FROM files WHERE id = '$object->parent_id'")[0];

        return $this->recursiveJoinPath($row, $path) . '\\' . $row->name;
    }
   
}
