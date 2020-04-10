<?php


$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'UGmrc2MTdJ7TnzOC35L9StGIs2dgYqD1HlsJuYP/j761SkX0FJP2uHCiNkZ+6vwWxzLZOUmiTCVgcAllpbSUbjdg0Tu8Eojvt40ZwYAPQuZYn3ED+oBahq0kSAHYT19hRcOqxnpQ2AHlnzK42OeFTAdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '4d728f05cc5b84b4251a7facda975ddb';


$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array



if ( sizeof($request_array['events']) > 0 ) {

    foreach ($request_array['events'] as $event) {

        $reply_message = '';
        $reply_token = $event['replyToken'];

        $text = $event['message']['text'];
		if($text=='text')
		{
			$data = [
				'replyToken' => $reply_token,
				// 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
				'messages' => [['type' => 'text', 'text' => 'Hello'.' : '.$text ]]
			];
			$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		else if ($text=='sticker')
		{
			$data = [
				'replyToken' => $reply_token,
				'messages' => [['type' => 'sticker', 'packageId' => 1, 'stickerId' => 2, 'stickerResourceType' => 'STATIC']]
			];
		}
	    	else if ($text=='location')
		{
			$data = [
				'replyToken' => $reply_token,
				'messages' => [['type' => 'location',
						'title' => 'NEXT INDUSTRY ASIA CO., LTD.',
						'address' => '〒20230 Chon Buri, Si Racha District, Thesaban Nakhon Laem Chabang, Soi 14',
						'latitude' => 13.146177,
						'longitude' => 100.962743,
					       ]]
			];
		}
	    	else if ($text=='video')
		{
			$data = [
				'replyToken' => $reply_token,
				'messages' => [['type' => 'video',
						'duration' => 60000,
						'contentProvider' => [['type' => 'external',
									'originalContentUrl' => 'https://entersec.co.th/0001.mp4',
									'previewImageUrl' => 'https://entersec.co.th/0001.jpg',
					       				]]
					       ]]
			];
		}	        
	$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
        $send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

        echo "Result: ".$send_result."\r\n";
    }
}

echo "OK";

$ch = curl_init("http://nextdevelop.ddns.net:81//testline/index.php?log=".$request."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$responett = curl_exec($ch);
curl_close($ch);



function send_reply_message($url, $post_header, $post_body)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

?>
