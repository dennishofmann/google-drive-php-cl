<?php

require_once __DIR__ . '/google.php';

$files = $argv;
$parentId = $argv[1];
unset($files[0]);
unset($files[1]);

foreach($files as $file)
{
    try
    {
        $return = upload($client, $service, $parentId, $file);
        unlink($file);
        echo $return->getId(), PHP_EOL;
    }
    catch(Exception $exception)
    {
        file_put_contents('google_upload.log', $file . PHP_EOL, FILE_APPEND);
        exit(1);
    }
}
exit(0);

function upload($client, $service, $parentId, $filepath)
{
    if(!file_exists($filepath))
    {
        return;
    }
    
    $chunkSizeBytes = 1024 * 1024 * 1024;
    $parent = new Google_Service_Drive_ParentReference();
    $file = new Google_Service_Drive_DriveFile();
    $sizeBytes = filesize($filepath);
    
    $parent->setId($parentId);
    
    $file->title = basename($filepath);
    $file->setParents(array($parent));
    
    $client->setDefer(true);
    
    $request = $service->files->insert($file);
    
    $media = new Google_Http_MediaFileUpload($client, $request, '', null, true, $chunkSizeBytes);
    $media->setFileSize($sizeBytes);
    
    $result = false;
    $handle = fopen($filepath, "rb");
    while(!$result && !feof($handle))
    {
        $chunk = fread($handle, $chunkSizeBytes);
        $result = $media->nextChunk($chunk);
    }
    fclose($handle);
    
    if(!$result)
    {
        throw new Exception('upload error');
    }
    
    return $result;
}

?>