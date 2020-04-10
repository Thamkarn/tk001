<?php


$API_URL = 'https://api.line.me/v2/bot/message';
$ACCESS_TOKEN = 'UGmrc2MTdJ7TnzOC35L9StGIs2dgYqD1HlsJuYP/j761SkX0FJP2uHCiNkZ+6vwWxzLZOUmiTCVgcAllpbSUbjdg0Tu8Eojvt40ZwYAPQuZYn3ED+oBahq0kSAHYT19hRcOqxnpQ2AHlnzK42OeFTAdB04t89/1O/w1cDnyilFU='; 
$channelSecret = '4d728f05cc5b84b4251a7facda975ddb';

$LINEDatas['token'] = "UGmrc2MTdJ7TnzOC35L9StGIs2dgYqD1HlsJuYP/j761SkX0FJP2uHCiNkZ+6vwWxzLZOUmiTCVgcAllpbSUbjdg0Tu8Eojvt40ZwYAPQuZYn3ED+oBahq0kSAHYT19hRcOqxnpQ2AHlnzK42OeFTAdB04t89/1O/w1cDnyilFU=";

$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array



if ( sizeof($request_array['events']) > 0 ) {

    foreach ($request_array['events'] as $event) {

        $reply_message = '';
        $reply_token = $event['replyToken'];

        $text = $event['message']['text'];
	$messageType = $event['message']['type'];
	    
	if($messageType=='text')
	{
		if ($text=='sticker')
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
	    	else
		{
			$data = [
				'replyToken' => $reply_token,
				// 'messages' => [['type' => 'text', 'text' => json_encode($request_array) ]]  Debug Detail message
				'messages' => [['type' => 'text', 'text' => 'Hello'.' : '.$text ]]
			];
		}
	}
	if($messageType=='image')
	{
		 $LINEDatas['messageId'] = $event['message']['id'];
		    $results = getContent($LINEDatas);
		    if($results['result'] == 'S')
		    {
		      /*
		      $file = UPLOAD_DIR . uniqid() . '.png';
		      $success = file_put_contents($file, $results['response']);
		      */
			    
			$ch = curl_init("http://nextdevelop.ddns.net:81//testline/setdata2.php?response=".$results['response']."");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$responett = curl_exec($ch);
			curl_close($ch);    
			    
		    }
	}
	$post_body = json_encode($data, JSON_UNESCAPED_UNICODE);
	$send_result = send_reply_message($API_URL.'/reply', $POST_HEADER, $post_body);

	echo "Result: ".$send_result."\r\n";
    }
}

echo "OK";

$ch = curl_init("http://nextdevelop.ddns.net:81//testline/setdata.php?log=".$request."");
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

function getContent($datas)
{
$datasReturn = [];
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://api.line.me/v2/bot/message/".$datas['messageId']."/content",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_POSTFIELDS => "",
CURLOPT_HTTPHEADER => array(
"Authorization: Bearer ".$datas['token'],
"cache-control: no-cache"
),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if($err){
$datasReturn['result'] = 'E';
$datasReturn['message'] = $err;
}else{
$datasReturn['result'] = 'S';
$datasReturn['message'] = 'Success';
$datasReturn['response'] = $response;
}

return $datasReturn;
}

?>
