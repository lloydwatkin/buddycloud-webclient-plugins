<?php
echo 'Update buddycloud channel status with currently playing track....' . PHP_EOL;
echo '-----------------------------------------------------------------' . PHP_EOL;

if (!$config = parse_ini_file(__DIR__ . '/config.ini', true)) {
    echo 'config.ini does not exist in working directory';
    exit(1);
}
$lastfm     = $config['lastfm'];
$buddycloud = $config['buddycloud'];

if (!isset($lastfm['now-playing-time'])) {
    $lastfm['now-playing-time'] = 60;
}
if (!isset($buddycloud['channel'])) {
    $buddycloud['channel'] = $buddycloud['username'];
}
if (!isset($buddycloud['apiUrl'])) {
    $buddycloud['apiUrl'] = 'https://api.buddycloud.org';
}
if (!isset($buddycloud['template'])) {
    $buddycloud['template'] = '♫ Listening to: %artist% - %track% (%url%) ♫';
}
$url      = 'http://ws.audioscrobbler.com/2.0?method=user.getrecenttracks&user=' 
    . $lastfm['username'] . '&format=json&api_key=' . $lastfm['apiKey'] . '&limit=2';
$response = file_get_contents($url);

if (!$details = json_decode($response)) {
    echo 'Invalid response from last.fm' . PHP_EOL;
    exit(1);
}

echo 'Got response from last.fm' . PHP_EOL;

if (false === is_array($details->recenttracks->track)) {
    echo 'No currently playing track' . PHP_EOL;
    exit(0);
}
$track      = $details->recenttracks->track[0];
$text       = '#text';
$nowPlaying = false;

if (isset($track) && ((time() - $track->date->uts) < $lastfm['now-playing-time'])) {
    $nowPlaying = true;
} 
if (false === $nowPlaying) {   
    echo 'No track currently playing' . PHP_EOL;
    exit(0);
}

$url = $track->url;
// Try and shorten URL
$h = curl_init();
curl_setopt($h, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
curl_setopt($h, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($h, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($h, CURLOPT_HEADER, 0);
curl_setopt($h, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
curl_setopt($h, CURLOPT_POST, 1);
curl_setopt($h, CURLOPT_POSTFIELDS, json_encode(array('longUrl' => $url)));
if (($shorten = curl_exec($h)) && ($json = json_decode($shorten))) {
    $url = $json->id;
}
curl_close($h);

$message = str_replace(
    array('%artist%', '%track%', '%url%'),
    array($track->artist->$text, $track->name, $url),
    $buddycloud['template']
);
// Send this as buddycloud status
$url  = $buddycloud['apiUrl'] . '/' . $buddycloud['channel'] 
    . '/content/status';
$post = '<entry xmlns="http://www.w3.org/2005/Atom"><content>' 
    . $message . '</content></entry>';
echo 'Posting to buddycloud node...' . PHP_EOL;
$h = curl_init();
curl_setopt($h, CURLOPT_URL, $url);
curl_setopt($h, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/xml'));
curl_setopt($h, CURLOPT_USERPWD, $buddycloud['username'] . ':' . $buddycloud['password']);
curl_setopt($h, CURLOPT_RETURNTRANSFER, true);
curl_setopt($h, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($h);
$responseCode = curl_getinfo($h, CURLINFO_HTTP_CODE);
curl_close($h);

if (201 === $responseCode) {
   echo 'Posted to buddycloud successfully' . PHP_EOL;
   exit(0);
}
echo 'Failed to post to buddycloud' . PHP_EOL;
echo $response;
exit(1);
