<?php

require_once __DIR__ . '/google.php';

$names = $argv;
$parentId = $argv[1];
unset($names[0]);
unset($names[1]);

foreach($names as $name)
{
    try
    {
        $return = create($client, $service, $parentId, $name);
        echo $return->getId(), PHP_EOL;
    }
    catch(Exception $exception)
    {
        file_put_contents('google_folder.log', $id . PHP_EOL, FILE_APPEND);
        exit(1);
    }
}
exit(0);

function create($client, $service, $parentId, $name)
{
    $parent = new Google_Service_Drive_ParentReference();
    $file = new Google_Service_Drive_DriveFile();
    
    $parent->setId($parentId);
    
    $file->title = basename($name);
    $file->setParents(array($parent));
    $file->setMimeType('application/vnd.google-apps.folder');
    
    $request = $service->files->insert($file);
    
    if(!$request)
    {
        throw new Exception('create error');
    }
    return $request;
}

?>