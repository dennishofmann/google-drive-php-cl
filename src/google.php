<?php

require_once __DIR__ . '/google-api-php-client/src/Google/Client.php';

$client = new Google_Client();

if(!file_exists(__DIR__ . '/.google.conf'))
{
    throw new Exception('.google.conf missing');
}

$clientInfo = file_get_contents(__DIR__ . '/.google.conf');
$clientInfo = json_decode($clientInfo);

$client->setClientId($clientInfo->installed->client_id);
$client->setClientSecret($clientInfo->installed->client_secret);
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setScopes(array('https://www.googleapis.com/auth/drive'));

$service = init($client);

function init($client)
{
    $service = new Google_Service_Drive($client);

    if(file_exists(dirname(__FILE__) . '/.google.ses'))
    {
        $accessToken = file_get_contents(dirname(__FILE__) . '/.google.ses');
        $client->setAccessToken($accessToken);
    }
    else
    {
        $authUrl = $client->createAuthUrl();

        print "$authUrl\nonline => offline auto => force\n\n";
        $authCode = trim(fgets(STDIN));

        $accessToken = $client->authenticate($authCode);
        if(!file_put_contents('.google.ses', $accessToken, LOCK_EX))
        {
            throw new Exception('.google.ses error');
        }
    }
    
    return $service;
}

?>