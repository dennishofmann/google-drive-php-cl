<?php

require_once __DIR__ . '/google.php';

$ids = $argv;
unset($ids[0]);

foreach($ids as $id)
{
    try
    {
        echo implode(PHP_EOL, printFilesInFolder($service, $id)), PHP_EOL;
    }
    catch(Exception $exception)
    {
        file_put_contents('google_list.log', $id . PHP_EOL, FILE_APPEND);
        exit(1);
    }
}
exit(0);

function printFilesInFolder($service, $folderId)
{
    $result = array();
    $pageToken = NULL;

    do
    {
        try
        {
            $parameters = array();
            if ($pageToken)
            {
                $parameters['pageToken'] = $pageToken;
            }
            $children = $service->children->listChildren($folderId, $parameters);
            
            foreach($children->getItems() as $child)
            {
                $result[] = $child->getId();
            }
            $pageToken = $children->getNextPageToken();
        }
        catch(Exception $e)
        {
            print "An error occurred: " . $e->getMessage();
            $pageToken = NULL;
        }
    } while($pageToken);
    
    return $result;
}

?>