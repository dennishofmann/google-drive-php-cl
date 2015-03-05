<?php

require_once __DIR__ . '/google.php';

$ids = $argv;
unset($ids[0]);

foreach($ids as $id)
{
    try
    {
        download($client, $service, $id);
    }
    catch(Exception $exception)
    {
        file_put_contents('google_download.log', $id . PHP_EOL, FILE_APPEND);
        exit(1);
    }
}
exit(0);

function download($client, $service, $fileId)
{
    $file = $service->files->get($fileId);
    $downloadUrl = $file->getDownloadUrl();
    
    if($downloadUrl)
    {
        $request = new Google_Http_Request($downloadUrl, 'GET', null, null);
        $httpRequest = $client->getIo()->executeRequest($request);
        $accessToken = json_decode($client->getAccessToken());
        
        system('wget ' . escapeshellarg('--header=Authorization: ' . $accessToken->token_type . ' ' . $accessToken->access_token) . ' ' . escapeshellarg('-O' . time() . ' - ' . $file->getTitle()) . ' ' . escapeshellarg($file->getDownloadUrl()), $exit);
        if($exit)
        {
            throw new Exception('wget error');
        }
    }
}

?>