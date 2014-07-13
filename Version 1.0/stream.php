<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2011 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

$_language->read_module('stream');

//////// API DAILYMOTION /////////////
if(!function_exists('audiencedaily')) {
    require_once("API/Dailymotion.php");
    function audiencedaily($idv){
        $apiKey        = '33f23f4f0b22c4442697';
        $apiSecret     = '5da5a639023fbbe682ea294fcf544866ba57a5d8';
        $api = new Dailymotion();
        $api->setGrantType(
        Dailymotion::GRANT_TYPE_CLIENT_CREDENTIALS,
        $apiKey,
        $apiSecret
        );

        $result = $api->get('/video/'.$idv, array('fields' => array('audience','onair')));
        //var_dump($result);
        if($result["onair"]){
            return $result["audience"]; 
        }else{
            return false;
        }
        
    }
}
////////// API TWITCH ////////////////
if(!function_exists('audiencetwitch')) {
    function audiencetwitch($channelName){
        
        $clientId = '9ud5kztz3tucne7fir8yhc333mak33a';
        
        $json_array = json_decode(file_get_contents('https://api.twitch.tv/kraken/streams/'.strtolower($channelName).'?client_id='.$clientId), true);
        if ($json_array['stream'] != NULL) {
            return $json_array['stream']['viewers'];
        } else {
           return false;
        }
         
    }
}
////////// API YOUTUBE //////////////
/*
if(!function_exists('audienceyoutube')) {
    function audienceyoutube(){
            
        require_once 'API/Google/Client.php';
        require_once 'API/Google/Service/YouTube.php';
        session_start();
        
        $OAUTH2_CLIENT_ID = '73830798338-k5c5cgajikbgea49c7mp7o9er4088pu0.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET = 'QZ4x17gLA5PpsMF6cn9GubX8';

        $client = new Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        
        $youtube = new Google_Service_YouTube($client);
        
        if ($client->getAccessToken()) {
          try {
            // Execute an API request that lists broadcasts owned by the user who
            // authorized the request.
            $broadcastsResponse = $youtube->liveBroadcasts->listLiveBroadcasts(
                'id,snippet',
                array(
                    'mine' => 'true',
                ));

            $htmlBody .= "<h3>Live Broadcasts</h3><ul>";
            foreach ($broadcastsResponse['items'] as $broadcastItem) {
              $htmlBody .= sprintf('<li>%s (%s)</li>', $broadcastItem['snippet']['title'],
                  $broadcastItem['id']);
            }
            $htmlBody .= '</ul>';

          } catch (Google_ServiceException $e) {
            $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          } catch (Google_Exception $e) {
            $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
                htmlspecialchars($e->getMessage()));
          }

          $_SESSION['token'] = $client->getAccessToken();
        } else {
          // If the user hasn't authorized the app, initiate the OAuth flow
          $state = mt_rand();
          $client->setState($state);
          $_SESSION['state'] = $state;

          $authUrl = $client->createAuthUrl();
        }
        echo $htmlBody;
    }
}*/
if( isset($_GET['channel']) ){

    eval("\$title_stream = \"".gettemplate("title_stream")."\";");
    echo $title_stream;
    
    $channel=safe_query("SELECT * FROM `".PREFIX."stream` WHERE id ='".$_GET['channel']."'");
    $dc=mysql_num_rows($channel);
    $dcc=mysql_fetch_array($channel);
    $lien = $dcc["lien"];
    switch($dcc["type"]){
        case "1": eval("\$channel = \"".gettemplate("channel-dailymotion")."\";");
            break;
        case "2": eval("\$channel = \"".gettemplate("channel-twitch")."\";");
            break;
        default: eval("\$channel = \"".gettemplate("channel")."\";");
    }
    echo $channel;
}else{
    eval("\$title_stream = \"".gettemplate("title_stream")."\";");
    echo $title_stream;
    
    $channel=safe_query("SELECT * FROM `".PREFIX."stream` ORDER by `sort`");
    $dc=mysql_num_rows($channel);
    echo "<div class='row' style='margin: 15px;'>";
    while($dcc=mysql_fetch_array($channel)){
        $image = $dcc["image"];
        $titre = $dcc["name"];
        $desc = $dcc["desc"];
        $audiance = "Hors Ligne";
        $etat = "danger";
        switch($dcc["type"]){
            case "1":
                $audiance = audiencedaily($dcc["lien"]);
                if($audiance != false){
                    $etat = "success";
                }else{
                    $audiance = "Hors Ligne";
                }
            break;
            
            case "2":
                $audiance = audiencetwitch($dcc["lien"]);
                if($audiance != false){
                    $etat = "success";
                }else{
                    $audiance = "Hors Ligne";
                }
            break;
            
            case "3": //audienceyoutube();
                break;
            
            default :
        }
        $live = "?site=stream&channel=".$dcc["id"];
        eval("\$stream = \"".gettemplate("stream")."\";");
        echo $stream;
    }
    echo "</div>";
}


?>