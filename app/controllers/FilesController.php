<?php

namespace App\Controllers;

use App\Core\App;
use FilesAndFolders;

class FilesController
{
    protected $filesAndFolders;
    // constructor
    public function __construct()
    {
        //relative to entry point, index.php
        $this->filesAndFolders = new FilesAndFolders('./resources/file.txt'); 
    }

    public function index()
    {
        $this->filesAndFolders->writeFile();

        return view('index', ['message' => 'File write completed']);
    }
    
    public function search()
    {
        $result = [];

       if($_REQUEST){ 
           $search = $_REQUEST['search'];
        }

        if (!empty($search)) {
            // $search = mysqli_real_escape_string($search);
            $query = App::get('database');
            $result = $query->raw("select * from files where name like '%$search%'");
        }
        //we have result
        if (count($result)) {
            $result = $this->filesAndFolders->joinPaths($result); 
        }

        return view('search', ['result' => $result]);
    }
}
