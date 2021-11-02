<?php

$router->get('', 'FilesController@index');
$router->get('search', 'FilesController@search');
$router->get('search-result', 'FilesController@searchResult');
