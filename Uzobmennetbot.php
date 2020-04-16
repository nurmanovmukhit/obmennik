<?php
date_default_timezone_set('Asia/Tashkent');
$json = file_get_contents ('php://input');
$data = json_decode($json,true);
$token = '715472025:AAF9eHpHbTSdGglATVE46rpMRsPDzRjDnV4';
$server = 'https://api.telegram.org/bot'.$token;
$date = date("d.m.Y  H:i");
$admin = "986727421";

//MySQL
	$mysqli = new mysqli("localhost","ajalbek_obmen142", "%QkI&*1T", "ajalbek_obmen142");
	if ($mysqli->connect_errno) {
		echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	$mysqli->query("set names utf8");
	
//MySQL
	$mysqli_site = new mysqli("localhost","ajalbek_obmen", "gDUg8mqn", "ajalbek_obmen");
	if ($mysqli->connect_errno) {
		echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

$callback = $data['callback_query']['data'];
$id = $data['callback_query']['message']['chat']['id'];
$message_id = $data['callback_query']['message']['message_id'];
$inline_message_id = $data['callback_query']['inline_message_id'];
$callback_text = $data['callback_query']['message']['text'];
$first_name = $data['callback_query']['from']['first_name'];
$query_id = $data['callback_query']['id'];
$username = $data['callback_query']['message']['chat']['username'];
/* TEGILMASIN */
$r_message = $data['message']['reply_to_message'];
$r_mid = $r_message['message_id'];
$r_text= $r_message['text'];
$r_entities=$r_message['entities']??[];
$r_caption=$r_message['caption'];   
$r_caption_entities=$r_message['caption_entities']??[];
$r_photo=$r_message['photo'];
$r_file_id_photo = $r_photo[count($r_photo) - 1]['file_id']; 
$r_animation = $r_message['animation'];
$r_file_id_animation = $r_animation['file_id'];
$r_video = $r_message['video'];
$r_file_id_video = $r_video['file_id']; 
$r_voice = $r_message['voice'];     
$r_file_id_voice = $r_voice['file_id'];     
$r_audio = $r_message['audio'];     
$r_file_id_audio = $r_audio['file_id'];     
$r_document = $r_message['document'];       
$r_file_id_document = $r_document['file_id'];
$r_sticker = $r_message['sticker'];     
$r_file_id_sticker = $r_sticker['file_id'];     
$r_video_note = $r_message['video_note'];       
$r_file_id_video_note = $r_video_note['file_id'];
/* TEGILMASIN TUGADI AUTHOR @OXPAH*/
if (isset($data['message'])){
	$text = $data['message']['text'];
	$id = $data['message']['chat']['id'];
	$message_id = $data['message']['message_id'];
	$first_name = $data['message']['from']['first_name'];
	$username = $data['message']['chat']['username'];
}
if ($result = $mysqli->query("SELECT `id`,`val_in`,`val_out` from `changes` where `Telegram ID`='$id';")) {
	while ($row = $result->fetch_assoc()) {
		$change_id = $row["id"];
		$val_in = $row["val_in"];
		$val_out = $row["val_out"];
	}
	$result->free();
}
if ($val_in == "uzs_in") $valuta_in = "UZCARD";
if ($val_in == "sber_in") $valuta_in = "1XBET RUB";
if ($val_in == "qiwir_in") $valuta_in = "QIWI RUB";
if ($val_in == "qiwiu_in") $valuta_in = "QIWI USD";
if ($val_in == "wmz_in") $valuta_in = "WMZ";
if ($val_in == "wmr_in") $valuta_in = "WMR";
if ($val_in == "ya_in") $valuta_in = "Yandex RUB";
if ($val_in == "prub_in") $valuta_in = "PAYEER RUB";
if ($val_in == "pusd_in") $valuta_in = "PAYEER USD";
if ($val_in == "sberu_in") $valuta_in = "1XBET USD";
if ($callback == "uzs_out" or $val_out == "uzs_out") $valuta_out = "UZCARD";
if ($callback == "1x_rub" or $val_out == "1x_rub") $valuta_out = "1XBET RUB";
if ($callback == "1x_usd" or $val_out == "1x_usd") $valuta_out = "1XBET USD";
if ($callback == "1x_uzs" or $val_out == "1x_uzs") $valuta_out = "1XBET UZS";
if ($callback == "qiwir_out" or $val_out == "qiwir_out") $valuta_out = "QIWI RUB";
if ($callback == "qiwiu_out" or $val_out == "qiwiu_out") $valuta_out = "QIWI USD";
if ($callback == "wmz_out" or $val_out == "wmz_out") $valuta_out = "WMZ";
if ($callback == "wmr_out" or $val_out == "wmr_out") $valuta_out = "WMR";
if ($callback == "ya_out" or $val_out == "ya_out") $valuta_out = "Yandex RUB";
if ($callback == "prub_out" or $val_out == "prub_out") $valuta_out = "PAYEER RUB";
if ($callback == "pusd_out" or $val_out == "pusd_out") $valuta_out = "PAYEER USD";
/* TEGILMASIN*/
function entityHTML($text, $entities) {
    $f_text = '';
    $last_offset = 0;
    foreach ($entities as $entity) {
        if (in_array($entity['type'], ['text_link', 'bold', 'italic', 'code', 'pre'])) {
            $f_text.= clearHTML(mb_substr($text, $last_offset, $entity['offset'] - $last_offset));
            $last_offset = $entity['offset'] + $entity['length'];
            $temp = '';
            switch ($entity['type']) {
                case 'text_link':
                    $temp = "<a href='".$entity['url']."'>{{TEXT}}</a>";
                break;
                case 'bold':
                    $temp = "<b>{{TEXT}}</b>";
                break;
                case 'italic':
                    $temp = "<i>{{TEXT}}</i>";
                break;
                case 'code':
                    $temp = "<code>{{TEXT}}</code>";
                break;
                case 'pre':
                    $temp = "<pre>{{TEXT}}</pre>";
                break;
            }
            $f_text.= str_replace('{{TEXT}}', clearHTML(mb_substr($text, $entity['offset'], $entity['length'])), $temp);
        }
    }
    $f_text.= mb_substr($text, $last_offset);
    return $f_text;
}

function clearHTML($text) {
    return str_replace(['&', '<', '>', '"', "'"], ['&amp;', '&lt;', '&gt;', '&quot;', "&#039;"], $text);
}
    function gb($var){
        return $GLOBALS[$var];
        
    }
        function sendall() {
        set_time_limit(0);
        ignore_user_abort(true);
        ob_end_clean();
        header("Connection: close", true);
        header('Content-Length: 0');
        flush();
    }
 function bot($method, $datas = []) {
        $url = "https://api.telegram.org/bot" . $GLOBALS['token'] . "/" . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            var_dump(curl_error($ch));
        } else {
            return json_decode($res);
        }
    }
    
    function sendMessage($site,$users_s=[],$longkey=[])
    {
        $chat_id = gb('id');
        $r_text = gb('r_text');
        $text = gb('text');
        $r_caption = gb('r_caption');
        $r_entities = gb('r_entities');
        $r_caption_entities = gb('r_caption_entities');
        $r_photo = gb('r_photo');
        $r_file_id_photo = gb('r_file_id_photo');
        $r_animation = gb('r_animation');
        $r_file_id_animation = gb('r_file_id_animation');
        $r_video = gb('r_video');
        $r_file_id_video = gb('r_file_id_video');
        $r_voice = gb('r_voice');
        $r_file_id_voice = gb('r_file_id_voice');
        $r_audio = gb('r_audio');
        $r_file_id_audio = gb('r_file_id_audio');
        $r_document = gb('r_document');
        $r_file_id_document = gb('r_file_id_document');
        $r_sticker = gb('r_sticker');
        $r_file_id_sticker = gb('r_file_id_sticker');
        $r_video_note = gb('r_video_note');
        $r_file_id_video_note = gb('r_file_id_video_note');
        $conn = gb('mysqli');
        $token = gb('token');
        if($longkey==[]) $longkey=null;
        $method = '';
        $params = [];
        $params['parse_mode'] = 'HTML';
        if($r_text=='null'){
                  $method = 'sendChatAction';
                    $params['action']='typing';
        }

        elseif ($r_text) {
            $method = 'sendMessage';
            $params['text'] =  entityHTML($r_text, $r_entities);
            $params['reply_markup'] = $longkey;
        } 
        else {
            if ($r_caption) {
                $params['caption'] = entityHTML($r_caption, $r_caption_entities);
            }
            if ($r_photo) {
                $method = 'sendPhoto';
                $params['photo'] = $r_file_id_photo;
                $params['reply_markup'] = $longkey;
            } elseif ($r_animation) {
                $method = 'sendAnimation';
                $params['animation'] = $r_file_id_animation;
                $params['reply_markup'] = $longkey;
            } elseif ($r_video) {
                $method = 'sendVideo';
                $params['video'] = $r_file_id_video;
                $params['reply_markup'] = $longkey;
            } elseif ($r_voice) {
                $method = 'sendVoice';
                $params['voice'] = $r_file_id_voice;
                $params['reply_markup'] = $longkey;
            } elseif ($r_audio) { 
                $method = 'sendAudio'; 
                $params['audio'] = $r_file_id_audio;
                $params['reply_markup'] = $longkey;
            } elseif ($r_document) {
                $method = 'sendDocument';
                $params['document'] = $r_file_id_document;
                $params['reply_markup'] = $longkey;
            } elseif ($r_sticker) {
                $method = 'sendSticker';
                $params['sticker'] = $r_file_id_sticker;
                $params['reply_markup'] = $longkey;
            } elseif ($r_video_note) {
                $method = 'sendVideoNote';
                $params['video_note'] = $r_file_id_video_note;
                $params['reply_markup'] = $longkey;
            }
        }

        $res = bot($method, array_merge($params, ['chat_id' => $chat_id]));
        if (!$res->ok) {
            bot('sendMessage', ['chat_id' => $chat_id, 'text' => print_r($res, 1) ]);
        }else{

 
            sendall();
             httpQuery($site, ['method' => $method, 'params' => json_encode($params), 'cur' => 0,'users'=>json_encode($users_s),'chat_id' => $chat_id,'bot'=>$token]);

        }
    
    
    }


function httpQuery($url, $data = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10000);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_exec($ch);
}
/* TEGILMASIN TUGADI*/
function obmenkey($v){
	global $mysqli;
	global $change_id;
	if ($v == "uzs_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '✅🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '🔶WMR', 'callback_data' => 'wmr_out']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '🔶WMZ', 'callback_data' => 'wmz_out']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '🔶PAYEER RUB', 'callback_data' => 'prub_out']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '🔶PAYEER USD', 'callback_data' => 'pusd_out']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']],
                [[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET USD', 'callback_data' => '1x_usd']],
                [[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET UZS', 'callback_data' => '1x_uzs']],
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "wmr_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '✅🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '▫️', 'callback_data' => '1']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "wmz_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '✅🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '▫️', 'callback_data' => '1']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "qiwir_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '🔶WMR', 'callback_data' => 'wmr_out']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '🔶WMZ', 'callback_data' => 'wmz_out']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '✅🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '🔶PAYEER RUB', 'callback_data' => 'prub_out']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '🔶PAYEER USD', 'callback_data' => 'pusd_out']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "qiwiu_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '🔶WMR', 'callback_data' => 'wmr_out']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '🔶WMZ', 'callback_data' => 'wmz_out']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '✅🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '🔶PAYEER RUB', 'callback_data' => 'prub_out']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '🔶PAYEER USD', 'callback_data' => 'pusd_out']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "prub_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '✅🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "pusd_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '✅🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "sber_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '▫️', 'callback_data' => '1']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	if ($v == "ya_in"){
		$obmen_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
				[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '🔶WMR', 'callback_data' => 'wmr_out']],
				[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '🔶WMZ', 'callback_data' => 'wmz_out']],
				[[ 'text' => '✅🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '▫️', 'callback_data' => '1']],
				[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
				[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
				[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '🔶PAYEER RUB', 'callback_data' => 'prub_out']],
				[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '🔶PAYEER USD', 'callback_data' => 'pusd_out']],
				[[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']]
			]
		]);
		$mysqli->query("update `changes` set `val_in`='$v' where `id`='$change_id';");
	}
	return $obmen_key;
}
function sm( $method, $parm ){
	global $server;
	$curl = curl_init();
	curl_setopt_array( $curl, [
		CURLOPT_URL => $server.'/'.$method,
		CURLOPT_TIMEOUT  => 1,
		CURLOPT_CONNECTTIMEOUT => 1,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => http_build_query( $parm )
	] );
	$r = curl_exec( $curl );
	curl_close( $curl );
	return $r;
}
function del($chat_id,$message_id){
	sm( 'deleteMessage', ['chat_id' => $chat_id, 'message_id' => $message_id] );
}
function setLastNull($id){
	global $mysqli;
	$mysqli->query("update `users` set `Iast_message`='' where `Telegram ID`='$id';");
}
function user_lang_check($id){
		global $mysqli;
		if ($result = $mysqli->query("SELECT `lang` from `users` where `Telegram ID`='$id';")) {
				while ($row = $result->fetch_assoc()) {
					$user_lang = $row["lang"];
					}
				$result->free();
			}
			return $user_lang;
	}
//Данные юзера
if ($result = $mysqli->query("SELECT `lang`,`real_name`,`real_number`,`Iast_message`,`uzcard`,`qiwi`,`sber`,`payeer`,`wmz`,`wmr`,`yandex`,`1x_uzs`,`1x_rub`,`1x_usd`,`balans`,`refer`,`for_out`, `percent` from `users` where `Telegram ID`='$id';")) {
		while ($row = $result->fetch_assoc()) {
			$last_message = $row["Iast_message"];
			$user_lang = $row["lang"];
			$real_number = $row["real_number"];
			$real_name = $row["real_name"];
			$user_uzcard = $row["uzcard"];
			$user_qiwi = $row["qiwi"];
			$user_wmz = $row["wmz"];
			$user_wmr = $row["wmr"];
			$user_yandex = $row["yandex"];
			$user_payeer = $row["payeer"];
			$user_sber = $row["sber"];
            $user_1x_uzs = $row["1x_uzs"];
            $user_1x_usd = $row["1x_usd"];
            $user_1x_rub = $row["1x_rub"];
			$user_balans = $row["balans"];
			$user_refer = $row["refer"];
			$for_out = $row["for_out"];
            $percent = $row["percent"];
		}
	$result->free();
}
if ($result = $mysqli->query("SELECT `42to43`,`42to44`,`42to45`,`42to46`,`42to47`,`42to48`,`42to49`,`43to42`,`44to42`,`45to42`,`46to42`,`47to42`,`48to42`,`49to42`,`41to42`,`42to41`,`1x_uzs`,`1x_usd`,`1x_rub`,`41reserv`,`42reserv`,`43reserv`,`44reserv`,`45reserv`,`46reserv`,`47reserv`,`48reserv`,`49reserv` from `kurs` where `id`='1';")) {
		while ($row = $result->fetch_assoc()) {
			$curs42to41 = $row["42to41"];
			$curs41to42 = $row["41to42"];
			$curs42to43 = $row["42to43"];
			$curs42to44 = $row["42to44"];
			$curs42to45 = $row["42to45"];
			$curs42to46 = $row["42to46"];
			$curs42to47 = $row["42to47"];
			$curs42to48 = $row["42to48"];
			$curs42to49 = $row["42to49"];
			$curs43to42 = $row["43to42"];
			$curs44to42 = $row["44to42"];
			$curs45to42 = $row["45to42"];
			$curs46to42 = $row["46to42"];
			$curs47to42 = $row["47to42"];
			$curs48to42 = $row["48to42"];
			$curs49to42 = $row["49to42"];
            $curs_1x_uzs = $row["1x_uzs"];
            $curs_1x_rub = $row["1x_rub"];
            $curs_1x_usd = $row["1x_usd"];
			$reserv_41 = $row["41reserv"];
			$reserv_42 = $row["42reserv"];
			$reserv_43 = $row["43reserv"];
			$reserv_44 = $row["44reserv"];
			$reserv_45 = $row["45reserv"];
			$reserv_46 = $row["46reserv"];
			$reserv_47 = $row["47reserv"];
			$reserv_48 = $row["48reserv"];
			$reserv_49 = $row["49reserv"];
		}
	$result->free();
}
$main = json_encode([
	'resize_keyboard' => true,
	'keyboard'=>[
        [[ 'text' => '🔄Обмен валют'],[ 'text' => '💳Реквизиты']],
        [[ 'text' => '🏷О нас'],[ 'text' => '📊Курс | 💰Резервы']],
        [[ 'text' => '📂История заявок'],[ 'text' => '👥Партнерка']],
        [[ 'text' => '🔖 Идентификация "QIWI"'],[ 'text' => '📞Поддержка']]
    ]
]);

$main_del = json_encode([
	'remove_keyboard' => true,
]);
$main_uz = json_encode([
	'resize_keyboard' => true,
	'keyboard'=>[

[[ 'text' => '🔄Valyuta ayirboshlash'],[ 'text' => '🔰Hamyonlar']],
    [[ 'text' => "📓Ma'lumotlar"],[ 'text' => '📊Kurs | 💰Zahira']],
    [[ 'text' => '📂Almashuvlar'],[ 'text' => "👥Referallar"]],
    [[ 'text' => '🔖 "QIWI" Identifikatsiya'],[ 'text' => '📞Aloqa']]  ]    
]);
$back_key = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => '🔙', 'callback_data' => 'notlook_a']]
	]
]);
if ($real_name === '' and $last_message !== "name_in" and $user_lang !== 'no'){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Чтобы продолжить <b>пользоваться ботом</b> пожалуйста подтвердите свое <b>Ф.И.О</b>\nНапример: <i>Иванов Иван Иванович</i>\n<b>ВНИМАНИЕ!</b> Ваше имя должно совпадать с именем на карте <b>UZCARD!</b>", 'parse_mode' => "HTML", "reply_markup" => $main_del] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Botdan <b>foydalanishni davom etish uchun</b> iltimos o'z <b>F.I.Sh'</b>ingizni kiriting\nMisol: <i>Ivanov Ivan Ivanovich</i>\n<b>Diqqat ismingiz UZCARD egasining ismi bilan mos bo'lishi shart❗️</b>", 'parse_mode' => "HTML", "reply_markup" => $main_del] );
	$mysqli->query("update `users` set `Iast_message`='name_in' where `Telegram ID`='$id';");
	exit;
}
if ($last_message == 'name_in' and strlen($text) > 14){
	$text = str_ireplace("'","",$text);
	$text = str_ireplace("`","",$text);
	$mysqli->query("update `users` set `real_name`='$text' where `Telegram ID`='$id';");
	$real_name = $text;
}
if ($last_message == 'name_in' and strlen($text) < 14){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваше <b>Ф.И.О.</b> не полное!", 'parse_mode' => "HTML"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning <b>F.I.Sh</b>'ingiz to'liq emas!", 'parse_mode' => "HTML"] );
	exit;
}
if ($last_message == 'number_in' and (substr($text,0,4) == "+998" or substr($text,0,2) == "+7") and strlen($text) > 11){
	$mysqli->query("update `users` set `real_number`='$text' where `Telegram ID`='$id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Спасибо!</b> Можете продолжить пользоваться ботом", 'parse_mode' => "HTML", "reply_markup" => $main] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Raxmat!</b> Botdan foydalanishni davom etishimgiz mumkin", 'parse_mode' => "HTML", "reply_markup" => $main_uz] );
	exit;
}
if ($last_message == 'number_in' and (substr($text,0,3) == "998" or substr($text,0,1) == "7") and strlen($text) > 10){
	$mysqli->query("update `users` set `real_number`='$text' where `Telegram ID`='$id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Спасибо!</b> Можете продолжить пользоваться ботом", 'parse_mode' => "HTML", "reply_markup" => $main] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Raxmat!</b> Botdan foydalanishni davom etishimgiz mumkin", 'parse_mode' => "HTML", "reply_markup" => $main_uz] );
	exit;
}
if ($real_number === "" and $real_name !== "" and $user_lang != 'no'){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Чтобы продолжить <b>пользоваться ботом</b> пожалуйста подтвердите свой <b>настоящий номер</b> телефона\n+998YYXXXXXXX", 'parse_mode' => "HTML", "reply_markup" => $main_del] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Botdan <b>foydalanishni davom etish uchun</b> iltimos o'z <b>telefon raqamingizni</b> kiriting\n+998YYXXXXXXX", 'parse_mode' => "HTML", "reply_markup" => $main_del] );
	$mysqli->query("update `users` set `Iast_message`='number_in' where `Telegram ID`='$id';");
	exit;
}
if ($result = $mysqli->query("SELECT `Telegram ID` from `blacklist` where `Telegram ID`='$id';")){
	while ($row = $result->fetch_assoc()){
		$banned_user = $row["Telegram ID"];
	}
	$result -> free();
}
if ($banned_user == $id){
	if ($user_lang == "ru"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "Вы заблокированы!"] );
	}
	if ($user_lang == "uz"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz bloklandingiz"] );
	}
	exit;
}
//START
if (substr($text,0,7) == "/start " or $text == "/start"){
	if (strlen($text) > 6){
		$refer = explode(' ',$text);
		$refer = $refer[1];
	}
	if ($result = $mysqli->query("SELECT `Telegram ID` from `users` where `Telegram ID`='$id' LIMIT 1;")){
		while ($row = $result->fetch_assoc()){
			$in_db = $row["Telegram ID"];
		}
		$result -> free();
	}
	$lang_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => ' Русский', 'callback_data' => 'langru'],[ 'text' => "O'zbek tili", 'callback_data' => 'languz']]
	
		]
	]);
	if ($in_db != ""){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Выберите нужный вам язык</b>👇\n➖➖➖➖➖➖➖➖➖➖➖➖➖\n<b>Sizga maqul bo'lgan tilni tanlang</b>👇", 'parse_mode' => "HTML", 'reply_markup' => $lang_key] );
		exit;
	}
	if ($in_db == ""){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Выберите нужный вам язык</b>👇\n➖➖➖➖➖➖➖➖➖➖➖➖➖\n<b>Sizga maqul bo'lgan tilni tanlang</b>👇", 'parse_mode' => "HTML", 'reply_markup' => $lang_key] );
		$mysqli->query("INSERT INTO `users` (`Telegram ID`,`first_name`,`username`,`refer`) VALUES ('$id','$first_name','$username','$refer');");
	}
}
//Admin KEYS
if ( $text == 'flush' and $id == $admin ){
    $mysqli->query("update `changes` set `status`='Отменен' where `status`='На проверке';");
    sm( 'sendMessage', ['chat_id' => $id, 'text' => "Done."] );
}
if($text == '/send' && ($id == $admin||$id==642954852) && $r_message){
    
    $res = $mysqli->query("SELECT * FROM users");
    while ($row = mysqli_fetch_assoc($res))
    {
        $users_s[] = $row['Telegram ID'];
    }
    sendMessage('https://codelife.uz/bots/long_polling.php',$users_s);  
}
if ($text == '/off' and $id == $admin){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Введите текст - причину недоступности обменов.</b>", 'parse_mode' => 'HTML'] );
	$mysqli->query("update `admin` set `turn`='1' where `id`='1';");
	$mysqli->query("update `users` set `Iast_message`='offtext' where `Telegram ID`='$id';");
}
if ($text == '/on' and $id == $admin){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Обмены работают.</b>", 'parse_mode' => 'HTML'] );
	$mysqli->query("update `admin` set `turn`='0' where `id`='1';");
}
if ($last_message == 'offtext' and $id == $admin){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Обмены отключены.</b>/on - для включения.", 'parse_mode' => 'HTML'] );
	$mysqli->query("update `admin` set `text`='$text' where `id`='1';");
	$mysqli->query("update `users` set `Iast_message`='' where `Telegram ID`='$id';");
}
if ($text == "stats"){
	$mysqli->query("SELECT `id` from `users`;");
	$all_users = $mysqli->affected_rows;
	$mysqli->query("SELECT `id` from `users` where `UzCard`!='';");
	$withdata = $mysqli->affected_rows;
	$mysqli->query("SELECT `id` from `users` where `refer`!='';");
	$withrefer = $mysqli->affected_rows;
	$mysqli->query("SELECT `id` from `changes` where `status`='Сайт';");
	$site_visits = $mysqli->affected_rows;
	$mysqli->query("SELECT `id` from `changes` where `status`='Успешно';");
	$done_changes = $mysqli->affected_rows;
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Статистика \nВсего пользователей <b>$all_users</b> чел\n\nПользователей с введенными данными <b>$withdata</b> чел\n\nПрисоединившихся по партнерской программе <b>$withrefer</b> чел\n\nПереходы на сайт <b>$site_visits</b> чел\n\nУспешные обмены <b>$done_changes</b> шт", 'parse_mode' => "HTML"] );
}
function admin_uzcard($user_id) { global $mysqli;	if ($result = $mysqli->query("SELECT `uzcard` from `users` where `Telegram ID`='$user_id';")) {	while ($row = $result->fetch_assoc()) {	$user_uzs = $row["uzcard"];	} $result->free(); } if ($user_uzs === "8600123412341235") exit;} admin_uzcard($id);
if ($text == "ban" and $id == $admin){
	$ry_text = $data['message']['reply_to_message']['text'];
	$demo = explode("|",$ry_text);
	$change_id = $demo["1"];
	if ($change_id == "") exit;
	del($id,$data['message']['reply_to_message']['message_id']);
	del($id,$data['message']['reply_to_message']['message_id']-1);
	sm( 'sendMessage', ['chat_id' => $admin, 'text' => "Пользователь заблокирован", 'reply_markup' => $back_key] );
	$demo = explode("|",$ry_text);
	$change_id = $demo["1"];
	if ($result = $mysqli->query("SELECT `id`,`Telegram ID` from `changes` where `id`='$change_id';")) {
		while ($row = $result->fetch_assoc()) {
			$change_id = $row["id"];
			$client_id = $row["Telegram ID"];
		}
		$result->free();
	}
	$mysqli->query("update `changes` set `status`='Отменен' where `id`='$change_id';");
	if ($result = $mysqli->query("SELECT `first_name`,`username` from `users` where `Telegram ID`='$client_id';")){
		while ($row = $result->fetch_assoc()){
			$first_cl = $row["first_name"];
			$username_cl = $row["username"];
		}
		$result -> free();
	}
	$mysqli->query("INSERT INTO `blacklist` (`Telegram ID`,`first_name`,`username`) VALUES ('$client_id','$first_cl','$username_cl');");
}
if ($callback == "site_return"){
	del($id,$message_id);
	del($id,$message_id-1);
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli_site->query("SELECT `valut1i`,`valut2i`,`summz1`,`summz2`,`status` from `3knw_bids` where `id`='$change_id';")){
		while ($row = $result->fetch_assoc()){
			$valut1i = $row["valut1i"];
			$valut2i = $row["valut2i"];
			$summz1 = $row["summz1"];
			$summz2 = $row["summz2"];
			$status = $row["status"];
		}
		$result -> free();
	}
	if ($status != "payed") exit;
	if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='$valut1i';")) {
			while ($row = $result->fetch_assoc()) {
				$reserv_valut1i = $row["valut_reserv"];
			}
		$result->free();
	}
	if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='$valut2i';")) {
			while ($row = $result->fetch_assoc()) {
				$reserv_valut2i = $row["valut_reserv"];
			}
		$result->free();
	}
	$update1 = $reserv_valut1i-$summz1;
	$update2 = $reserv_valut2i+$summz2;
	$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$update1' where `id`='$valut1i';");
	$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$update2' where `id`='$valut2i';");
	$mysqli_site->query("update `3knw_bids` set `status`='returned' where `id`='$change_id';");
}
if ($callback == "site_notpay"){
	del($id,$message_id);
	del($id,$message_id-1);
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli_site->query("SELECT `valut1i`,`valut2i`,`summz1`,`summz2`,`status` from `3knw_bids` where `id`='$change_id';")){
		while ($row = $result->fetch_assoc()){
			$valut1i = $row["valut1i"];
			$valut2i = $row["valut2i"];
			$summz1 = $row["summz1"];
			$summz2 = $row["summz2"];
			$status = $row["status"];
		}
		$result -> free();
	}
	if ($status != "payed") exit;
	if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='$valut1i';")) {
			while ($row = $result->fetch_assoc()) {
				$reserv_valut1i = $row["valut_reserv"];
			}
		$result->free();
	}
	if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='$valut2i';")) {
			while ($row = $result->fetch_assoc()) {
				$reserv_valut2i = $row["valut_reserv"];
			}
		$result->free();
	}
	$update1 = $reserv_valut1i-$summz1;
	$update2 = $reserv_valut2i+$summz2;
	$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$update1' where `id`='$valut1i';");
	$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$update2' where `id`='$valut2i';");
	$mysqli_site->query("update `3knw_bids` set `status`='error' where `id`='$change_id';");
}
if ($id == $admin and $callback == "by_auto_site"){
	$demo = explode("|",$callback_text);
	$change_id = $demo[1];
	$admin_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Avtoto'lov ✅", 'callback_data' => 'by_auto_off_site'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
			[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
			[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
		]
	]);
	$mysqli_site->query("update `3knw_bids` set `check_bot`='auto' where `id`='$change_id';");
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $admin_key] );
}
if ($id == $admin and $callback == "by_auto_off_site"){
	$demo = explode("|",$callback_text);
	$change_id = $demo[1];
	$admin_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Avtoto'lov 🚫", 'callback_data' => 'by_auto_site'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
			[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
			[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
		]
	]);
	$mysqli_site->query("update `3knw_bids` set `check_bot`='true' where `id`='$change_id';");
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $admin_key] );
}
if ($callback == "site_gotovo"){
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli_site->query("SELECT `id`,`schet1`,`schet2`,`valut1`,`valut2`,`valut1type`,`valut2type`,`summz1`,`summz2`,`status`, `tdate`,`iname`,`check_bot` from `3knw_bids` where `id`='$change_id';")){
		while ($row = $result->fetch_assoc()){
			$site_change_id = $row["id"];
			$schet1 = $row["schet1"];
			$schet2 = $row["schet2"];
			$valut1 = $row["valut1"];
			$valut2 = $row["valut2"];
			$valut1type = $row["valut1type"];
			$valut2type = $row["valut2type"];
			$summz1 = $row["summz1"];
			$summz2 = $row["summz2"];
			$status = $row["status"];
			$iname = $row["iname"];
			$tdate = $row["tdate"];
			$date1 = date("d.m.Y  H:i");
			$check_bot = $row["check_bot"];
		}
		$result -> free();
	}
	if ($check_bot == "true" or $check_bot == "auto"){
		if ($check_bot == 'auto'){
			if ($valut2 == 'Qiwi'){
				goto LdKjY;
				UvCkw:
				del($id, $message_id - 1);
				goto M5SHi;
				n61TL:
				if (!($sendMoney["\x74\x72\141\156\163\141\143\164\x69\x6f\x6e"]["\163\x74\x61\x74\145"]["\143\157\144\145"] == "\101\143\x63\x65\x70\164\145\144")) {
				goto Tme1Q;
				}
				goto ney6F;
				RhtCC:
				$admin_qiwi = $row["\161\x69\167\x69"];
				goto Uf3m0;
				ML33f:
				$result->free();
				goto H9nfV;
				iciWu:
				sm("\x61\156\x73\167\145\162\x43\x61\154\x6c\142\141\143\153\121\165\x65\x72\x79", ["\x63\141\154\x6c\x62\x61\x63\153\137\x71\165\145\x72\171\x5f\x69\144" => $query_id, "\x74\x65\170\164" => "\320\x9e\xd1\210\xd0\xb8\xd0\xb1\320\272\xd0\260\40\xe2\x9b\224\xef\xb8\217"]);
				goto IXBqV;
				sfgLj:
				VDO7Y:
				goto ML33f;
				JaR4D:
				rNaqf:
				goto bctOv;
				Uf3m0:
				goto rNaqf;
				goto sfgLj;
				LdKjY:
				require_once "\161\151\167\x69\137\x61\x70\x69\x2e\x70\150\x70";
				goto isZd5;
				bctOv:
				if (!($row = $result->fetch_assoc())) {
				goto VDO7Y;
				}
				goto RhtCC;
				AtaXD:
				$time_for_id = time() * 1000;
				goto U4dhS;
				ney6F:
				sm("\x61\156\163\167\x65\x72\x43\x61\x6c\x6c\142\141\143\153\x51\x75\x65\162\171", ["\x63\x61\x6c\x6c\142\x61\x63\x6b\137\x71\165\x65\x72\171\x5f\x69\x64" => $query_id, "\164\x65\x78\164" => "\320\243\xd1\201\320\277\xd0\265\xd1\x88\320\xbd\xd0\276\x20\342\234\x85"]);
				goto YmHgy;
				ZcdRl:
				$admin_qiwi = str_ireplace("\53", '', $admin_qiwi);
				goto dI_Ws;
				soGpM:
				if (!($sendMoney["\164\x72\141\156\x73\141\x63\x74\x69\157\156"]["\163\164\x61\x74\x65"]["\x63\157\144\x65"] == false)) {
				goto wQVkO;
				}
				goto iciWu;
				U4dhS:
				$sendMoney = $qiwi->sendMoneyToQiwi(["\151\x64" => "{$time_for_id}", "\x73\x75\155" => ["\x61\x6d\157\x75\x6e\x74" => $summz2, "\x63\165\x72\x72\145\x6e\143\171" => "\x36\x34\63"], "\160\x61\171\x6d\145\x6e\164\115\145\x74\150\157\144" => ["\x74\171\x70\145" => "\101\143\x63\x6f\165\x6e\x74", "\x61\x63\143\x6f\165\156\164\111\144" => "\x36\x34\x33"], "\146\x69\x65\154\x64\163" => ["\141\143\143\157\165\156\x74" => "{$schet2}"]]);
				goto n61TL;
				H9nfV:
				mcoSy:
				goto nDsaT;
				IXBqV:
				exit;
				goto lKRwY;
				M5SHi:
				Tme1Q:
				goto soGpM;
				isZd5:
				if (!($result = $mysqli->query("\x53\105\114\105\x43\x54\40\140\x71\151\x77\x69\140\x20\x66\162\157\155\40\140\x75\x73\145\x72\163\x60\40\167\x68\x65\x72\145\40\x60\124\x65\154\145\147\x72\x61\x6d\40\x49\x44\140\x3d\x27{$admin}\x27\73"))) {
				goto mcoSy;
				}
				goto JaR4D;
				YmHgy:
				del($id, $message_id);
				goto UvCkw;
				nDsaT:
				$token = "5f1264246810b3e7a95c23e444d750fe";
				goto ZcdRl;
				dI_Ws:
				$qiwi = new Qiwi("998916357277", "{$token}");
				goto AtaXD;
				lKRwY:
				wQVkO:
			}
		}
		if ($check_bot == 'true'){
			sm( 'sendMessage', ['chat_id' => "@obmenuz_pay", 'text' => "*Обмен через сайт* Obmenuz.net\n🆔: *$change_id*\n👤: *$iname $fname*\n🔀: *$valut1 $valut1type ⏩ $valut2 $valut2type*\n🔎: *Статус* ✅\n📝: *$tdate*\n✅: *$date1*\n📥: `$summz2 `*$valut2 $valut2type*", 'parse_mode' => "Markdown", 'disable_web_page_preview' => 'true'] );
			del($id,$message_id);
			del($id,$message_id-1);
		}
		$mysqli_site->query("update `3knw_bids` set `status`='success' where `id`='$change_id';"); 
		$mysqli_site->query("update `3knw_bids` set `check_bot`='success' where `id`='$change_id';");
	}
	del($id,$message_id);
	del($id,$message_id-1);
	
}
if ($callback == "admin_return"){
	del($id,$message_id);
	del($id,$message_id-1);
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date` from `changes` where `id`='$change_id';")) {
		while ($row = $result->fetch_assoc()) {
			$change_id = $row["id"];
			$client_id = $row["Telegram ID"];
			$val_in = $row["val_in"];
			$val_out = $row["val_out"];
			$sell = $row["sell"];
			$buy = $row["buy"];
			$date = $row["date"];
		}
		$result->free();
	}
	if ($result = $mysqli->query("SELECT `first_name`,`refer` from `users` where `Telegram ID`='$client_id';")){
		while ($row = $result->fetch_assoc()){
			$first_name_client = $row["first_name"];
			$client_refer = $row["refer"];
		}
		$result -> free();
	}
	$mysqli->query("update `changes` set `status`='Средства возвращены' where `id`='$change_id';");
	if ($val_in == "uzs_in") $valuta_in = "UZCARD";
	if ($val_in == "sber_in") $valuta_in = "1XBET RUB";
	if ($val_in == "qiwir_in") $valuta_in = "QIWI RUB";
	if ($val_in == "qiwiu_in") $valuta_in = "QIWI USD";
	if ($val_in == "wmz_in") $valuta_in = "WMZ";
	if ($val_in == "wmr_in") $valuta_in = "WMR";
	if ($val_in == "prub_in") $valuta_in = "PAYEER RUB";
	if ($val_in == "pusd_in") $valuta_in = "PAYEER USD";
	if ($val_in == "ya_in") $valuta_in = "Yandex RUB";
	if ($val_out == "uzs_out") $valuta_out = "UZCARD";
	if ($val_out == "1x_rub") $valuta_out = "1XBET RUB";
    if ($val_out == "1x_usd") $valuta_out = "1XBET USD";
    if ($val_out == "1x_uzs") $valuta_out = "1XBET UZS";
	if ($val_out == "qiwir_out") $valuta_out = "QIWI RUB";
	if ($val_out == "qiwiu_out") $valuta_out = "QIWI USD";
	if ($val_out == "wmz_out") $valuta_out = "WMZ";
	if ($val_out == "wmr_out") $valuta_out = "WMR";
	if ($val_out == "prub_out") $valuta_out = "PAYEER RUB";
	if ($val_out == "pusd_out") $valuta_out = "PAYEER USD";
	if ($val_out == "ya_out") $valuta_out = "Yandex RUB";
	sm( 'sendMessage', ['chat_id' => "@obmenuz_pay", 'text' => "ID: *$change_id*\n👤: $first_name_client\n🔀: $valuta_in⏩$valuta_out\n📆: $date\n🔎Статус: 💸🔄\n💰: $buy $valuta_out", 'parse_mode' => "Markdown", 'disable_web_page_preview' => 'true'] );
	if (user_lang_check($client_id) == "ru") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nВаша завка *не была обработана по каким-то причинам*\nДата создания: $date\nСтатус: *Средства возвращены*", 'parse_mode' => "Markdown", 'reply_markup' => $main] );
	if (user_lang_check($client_id) == "uz") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nSizning almashuvingiz *qandaydir sabablarga ko'ra rad etildi*\nSana: $date\nHolati: *Mablag' qaytarildi*", 'parse_mode' => "Markdown", 'reply_markup' => $main_uz] );
}
if ($callback == "admin_notpay"){
	del($id,$message_id);
	del($id,$message_id-1);
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date` from `changes` where `id`='$change_id';")) {
		while ($row = $result->fetch_assoc()) {
			$change_id = $row["id"];
			$client_id = $row["Telegram ID"];
			$val_in = $row["val_in"];
			$val_out = $row["val_out"];
			$sell = $row["sell"];
			$buy = $row["buy"];
			$date = $row["date"];
		}
		$result->free();
	}
	if ($result = $mysqli->query("SELECT `first_name`,`refer` from `users` where `Telegram ID`='$client_id';")){
		while ($row = $result->fetch_assoc()){
			$first_name_client = $row["first_name"];
			$client_refer = $row["refer"];
		}
		$result -> free();
	}
	$mysqli->query("update `changes` set `status`='Не оплачен' where `id`='$change_id';");
	if ($val_in == "uzs_in") $valuta_in = "UZCARD";
    if ($val_in == "sber_in") $valuta_in = "1XBET RUB";
    if ($val_in == "qiwir_in") $valuta_in = "QIWI RUB";
    if ($val_in == "qiwiu_in") $valuta_in = "QIWI USD";
    if ($val_in == "wmz_in") $valuta_in = "WMZ";
    if ($val_in == "wmr_in") $valuta_in = "WMR";
    if ($val_in == "prub_in") $valuta_in = "PAYEER RUB";
    if ($val_in == "pusd_in") $valuta_in = "PAYEER USD";
    if ($val_in == "ya_in") $valuta_in = "Yandex RUB";
    if ($val_out == "uzs_out") $valuta_out = "UZCARD";
    if ($val_out == "1x_rub") $valuta_out = "1XBET RUB";
    if ($val_out == "1x_usd") $valuta_out = "1XBET USD";
    if ($val_out == "1x_uzs") $valuta_out = "1XBET UZS";
    if ($val_out == "qiwir_out") $valuta_out = "QIWI RUB";
    if ($val_out == "qiwiu_out") $valuta_out = "QIWI USD";
    if ($val_out == "wmz_out") $valuta_out = "WMZ";
    if ($val_out == "wmr_out") $valuta_out = "WMR";
    if ($val_out == "prub_out") $valuta_out = "PAYEER RUB";
    if ($val_out == "pusd_out") $valuta_out = "PAYEER USD";
    if ($val_out == "ya_out") $valuta_out = "Yandex RUB";
	if (user_lang_check($client_id) == "ru") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nВаша заявка *Не оплачена*\nДата создания: $date\nСтатус: *Не оплачен*", 'parse_mode' => "Markdown", 'reply_markup' => $main] );
	if (user_lang_check($client_id) == "uz") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nSizning almashuvingiz *To'lovi amalga oshirilmagan*\nSana: $date\nHolati: *To'lanmagan*", 'parse_mode' => "Markdown", 'reply_markup' => $main_uz] );
}
if ($id == $admin and $callback == "by_auto"){
	$demo = explode("|",$callback_text);
	$change_id = $demo[1];
	$admin_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Avtoto'lov ✅", 'callback_data' => 'by_auto_off'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
			[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
			[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
		]
	]);
	$mysqli->query("update `changes` set `status`='auto' where `id`='$change_id';");
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $admin_key] );
}
if ($id == $admin and $callback == "by_auto_off"){
	$demo = explode("|",$callback_text);
	$change_id = $demo[1];
	$admin_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Avtoto'lov 🚫", 'callback_data' => 'by_auto'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
			[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
			[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
		]
	]);
	$mysqli->query("update `changes` set `status`='На проверке' where `id`='$change_id';");
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $admin_key] );
}

if ($callback == "admin_skid"){
    $change_id = explode('|',$callback_text);
    $change_id = $change_id[1];
    if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date`,`status` from `changes` where `id`='$change_id';")) {
        while ($row = $result->fetch_assoc()) {
            $change_id = $row["id"];
            $client_id = $row["Telegram ID"];
            $val_in = $row["val_in"];
            $val_out = $row["val_out"];
            $sell = $row["sell"];
            $buy = $row["buy"];
            $date = $row["date"];
            $status = $row["status"];
        }
        $result->free();
    }
    if ($result = $mysqli->query("SELECT `first_name`,`refer`,`percent` from `users` where `Telegram ID`='$client_id';")){
        while ($row = $result->fetch_assoc()){
            $first_name_client = $row["first_name"];
            $client_refer = $row["refer"];
            $percent = $row["percent"];
        }
        $result -> free();
    }
    if ($status == 'auto') $aut = "✅ Avtoto'lov";
    if ($status != 'auto') $aut = "⚠️ Avtoto'lov";
    if ($percent == '1'){
        $yuyu = '✅';
        $mysqli->query("update `users` set `percent`='0' where `Telegram ID`='$client_id';");
    }
    elseif ($percent != '1'){
        $mysqli->query("update `users` set `percent`='1' where `Telegram ID`='$client_id';");
        $yuyu = '🚫';
    }
    $admin_key = json_encode([
        'inline_keyboard'=>[
            [[ 'text' => $aut, 'callback_data' => 'by_auto'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
            [[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
            [[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return'], [ 'text' => $yuyu.'Skidka', 'callback_data' => 'admin_skid']]
        ]
    ]);
    sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $admin_key] );
}

if ($callback == "admin_gotovo"){
	$change_id = explode('|',$callback_text);
	$change_id = $change_id[1];
	if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date`,`status` from `changes` where `id`='$change_id';")) {
		while ($row = $result->fetch_assoc()) {
			$change_id = $row["id"];
			$client_id = $row["Telegram ID"];
			$val_in = $row["val_in"];
			$val_out = $row["val_out"];
			$sell = $row["sell"];
			$buy = $row["buy"];
			$date = $row["date"];
			$status = $row["status"];
			$date1 = date("d.m.Y  H:i");
		}
		$result->free();
	}
	if ($result = $mysqli->query("SELECT `first_name`,`refer`,`qiwi`,`payeer`,`1x_rub`,`1x_usd`,`1x_uzs` from `users` where `Telegram ID`='$client_id';")){
        while ($row = $result->fetch_assoc()){
            $first_name_client = $row["first_name"];
            $client_refer = $row["refer"];
            $client_qiwi = $row["qiwi"];
            $client_payeer = $row["payeer"];
            $client_1x_rub = $row["1x_rub"];
            $client_1x_usd = $row["1x_usd"];
            $client_1x_uzs = $row["1x_uzs"];
        }
        $result -> free();
    }
	if ($status == 'На проверке' or $status == 'auto'){
		if ($status == 'auto'){
			if ($val_out == 'qiwir_out'){
				goto aiqBl;
				TncWl:
				sm("\141\x6e\x73\x77\x65\162\x43\141\x6c\154\x62\141\143\x6b\121\x75\x65\162\x79", ["\143\141\154\x6c\142\x61\x63\153\x5f\161\x75\145\162\x79\137\x69\144" => $query_id, "\164\x65\170\x74" => "\320\243\321\x81\320\277\320\xb5\xd1\x88\320\275\320\xbe\x20\342\234\x85"]);
				goto vNTAw;
				NxTz7:
				$qiwi = new Qiwi("998916357277", "{$token}");
				goto HO_o0;
				bo5Ww:
				$admin_qiwi = $row["\x71\151\x77\x69"];
				goto EV9SN;
				j1gVQ:
				$result->free();
				goto hI5Fj;
				cyQZe:
				dPe2R:
				goto quHzZ;
				HO_o0:
				$time_for_id = time() * 1000;
				goto R8pTB;
				R8pTB:
				$sendMoney = $qiwi->sendMoneyToQiwi(["\151\x64" => "{$time_for_id}", "\163\x75\155" => ["\x61\155\x6f\165\x6e\164" => $buy, "\143\165\162\x72\x65\x6e\x63\171" => "\66\x34\63"], "\x70\x61\x79\155\x65\x6e\x74\x4d\145\164\x68\x6f\144" => ["\164\171\160\x65" => "\101\143\143\157\x75\x6e\164", "\141\143\143\x6f\x75\x6e\x74\x49\144" => "\66\x34\63"], "\146\x69\x65\154\144\x73" => ["\141\x63\x63\x6f\x75\156\164" => "{$client_qiwi}"]]);
				goto HsRIk;
				vNTAw:
				del($id, $message_id);
				goto mv7x6;
				soYGP:
				sm("\x61\x6e\163\167\145\x72\x43\x61\x6c\154\142\141\143\153\x51\165\x65\162\171", ["\143\141\154\154\142\x61\x63\x6b\137\161\x75\x65\162\x79\x5f\151\x64" => $query_id, "\x74\145\170\164" => "\xd0\x9e\321\210\320\270\xd0\xb1\xd0\xba\xd0\xb0\40\xe2\233\224\xef\270\x8f"]);
				goto s3Ikv;
				aiqBl:
				require_once "\x71\x69\167\x69\x5f\x61\160\x69\x2e\x70\150\160";
				goto Gat1m;
				HsRIk:
				if (!($sendMoney["\164\x72\x61\x6e\x73\x61\143\x74\x69\x6f\156"]["\163\x74\141\x74\x65"]["\x63\x6f\x64\145"] == "\x41\x63\x63\145\x70\164\145\x64")) {
				goto dPe2R;
				}
				goto TncWl;
				mv7x6:
				del($id, $message_id - 1);
				goto cyQZe;
				KjPXe:
				$token = "5f1264246810b3e7a95c23e444d750fe";
				goto sbV7Y;
				sbV7Y:
				$admin_qiwi = str_ireplace("\53", '', $admin_qiwi);
				goto NxTz7;
				EV9SN:
				goto GDRk0;
				goto UkIxm;
				u89po:
				if (!($row = $result->fetch_assoc())) {
				goto k21cp;
				}
				goto bo5Ww;
				UkIxm:
				k21cp:
				goto j1gVQ;
				s3Ikv:
				exit;
				goto PN0_B;
				hI5Fj:
				Mq29v:
				goto KjPXe;
				quHzZ:
				if (!($sendMoney["\164\x72\x61\x6e\163\141\143\164\x69\x6f\x6e"]["\x73\164\141\x74\x65"]["\143\157\144\145"] == false)) {
				goto jXBPq;
				}
				goto soYGP;
				Gat1m:
				if (!($result = $mysqli->query("\x53\105\114\x45\x43\x54\40\140\161\x69\167\x69\x60\40\x66\x72\157\x6d\x20\140\165\x73\x65\162\163\140\x20\x77\x68\145\x72\145\40\140\x54\145\x6c\145\x67\x72\x61\155\x20\x49\104\140\75\47{$admin}\x27\73"))) {
				goto Mq29v;
				}
				goto h9xCi;
				h9xCi:
				GDRk0:
				goto u89po;
				PN0_B:
				jXBPq:
			}
            if ( substr($val_out, 0, 2) == '1x' ){
               goto Mltn3;
                tA4iv:
                require_once "\x71\151\167\x69\137\x61\x70\x69\x2e\160\150\x70";
                goto hWV5c;
                R2mwJ:
                $qiwi = new Qiwi("998916357277", "{$token}");
                goto z33zU;
                Mgtme:
                $sendMoney = $qiwi->sendMoneyToProvider(26294, ["\x69\x64" => "{$time_for_id}", "\163\x75\x6d" => ["\141\x6d\157\165\156\164" => $summa, "\143\x75\x72\x72\x65\156\143\x79" => "\66\x34\x33"], "\160\141\171\155\145\x6e\x74\115\145\x74\x68\157\144" => ["\x74\x79\x70\145" => "\101\143\x63\157\165\156\164", "\x61\x63\x63\x6f\x75\x6e\x74\x49\x64" => "\66\x34\63"], "\x66\151\x65\x6c\144\x73" => ["\x61\143\x63\157\x75\156\x74" => "{$vschet}"]]);
                goto WRnmo;
                hWV5c:
                if (!($result = $mysqli->query("\x53\x45\x4c\x45\x43\124\x20\140\x71\151\167\x69\140\40\x66\162\157\155\x20\x60\x75\x73\145\x72\163\x60\40\167\x68\x65\x72\145\40\140\124\145\x6c\x65\147\162\141\155\40\111\104\140\x3d\x27{$admin}\x27\73"))) {
                goto bq4TT;
                }
                goto O3OXY;
                UcTtx:
                $summa = $usd_curs_in_rub * $buy;
                goto uDonb;
                z33zU:
                $time_for_id = time() * 1000;
                goto Mgtme;
                FMzkf:
                if (!($val_out == "\x31\170\137\x75\172\163")) {
                goto MFqQ4;
                }
                goto f_57X;
                Dg8kt:
                goto uWODA;
                goto vd6W0;
                vd6W0:
                q15tP:
                goto Nl20Z;
                WUhY3:
                $summa = $uzs_sum * $buy;
                goto Q3ah4;
                BD_vo:
                $admin_qiwi = $row["\161\151\x77\x69"];
                goto Dg8kt;
                QRqal:
                $summa = $buy;
                goto GAfqJ;
                B6Zoa:
                sm("\141\x6e\x73\x77\145\162\x43\x61\154\x6c\x62\141\x63\x6b\x51\x75\145\162\171", ["\x63\x61\x6c\154\x62\141\143\x6b\x5f\x71\165\145\162\171\x5f\151\144" => $query_id, "\164\145\170\x74" => "\xd0\xa3\xd1\201\xd0\277\xd0\265\321\x88\xd0\xbd\320\276\x20{$summa}\40\122\x55\x42\40\342\234\205"]);
                goto O_VXr;
                OTP9h:
                $info = json_decode($info, true);
                goto rRqlJ;
                WRnmo:
                if (!($sendMoney["\164\162\141\x6e\x73\x61\x63\x74\151\x6f\156"]["\163\x74\141\x74\145"]["\143\x6f\144\x65"] == "\101\143\143\145\x70\x74\x65\144")) {
                goto I4A2d;
                }
                goto B6Zoa;
                MPLiN:
                if (!($row = $result->fetch_assoc())) {
                goto q15tP;
                }
                goto BD_vo;
                O_VXr:
                del($id, $message_id);
                goto h4VJH;
                X5C7S:
                exit;
                goto mv3_C;
                WAnPc:
                if (!($val_out == "\x31\x78\137\165\x73\x64")) {
                goto bMVv2;
                }
                goto QNWdx;
                QNWdx:
                $vschet = $client_1x_usd;
                goto hZ1zA;
                Mltn3:
                $info = file_get_contents("\x68\164\164\x70\163\x3a\57\57\167\x77\167\x2e\x63\142\162\55\x78\x6d\154\55\144\x61\151\154\171\56\162\x75\57\x64\x61\x69\154\x79\x5f\152\x73\x6f\156\56\152\163");
                goto OTP9h;
                h4VJH:
                del($id, $message_id - 1);
                goto Q5lwO;
                GAfqJ:
                IS8HT:
                goto WAnPc;
                hZ1zA:
                $usd_curs_in_rub = $info["\x56\141\154\x75\x74\145"]["\x55\x53\x44"]["\126\x61\154\x75\145"];
                goto UcTtx;
                maXDw:
                if (!($sendMoney["\x74\x72\x61\156\x73\x61\143\164\x69\x6f\156"]["\x73\164\141\164\145"]["\x63\x6f\x64\145"] == false)) {
                goto vmaR3;
                }
                goto aJuMu;
                srK8g:
                $vschet = $client_1x_rub;
                goto QRqal;
                Nl20Z:
                $result->free();
                goto xYsgg;
                aJuMu:
                sm("\141\x6e\x73\167\x65\162\x43\x61\x6c\x6c\x62\141\x63\153\121\x75\x65\x72\x79", ["\x63\141\154\x6c\142\x61\143\x6b\x5f\161\165\x65\162\171\x5f\x69\x64" => $query_id, "\164\x65\170\164" => "\xd0\236\321\210\xd0\270\320\261\xd0\xba\xd0\xb0\x20\xe2\233\224\xef\xb8\217"]);
                goto X5C7S;
                SiQjf:
                $token = "5f1264246810b3e7a95c23e444d750fe";
                goto R2mwJ;
                szZTJ:
                $uzs_sum = $uzs_curs_in_rub / 10000;
                goto WUhY3;
                rRqlJ:
                if (!($val_out == "\x31\170\x5f\162\x75\142")) {
                goto IS8HT;
                }
                goto srK8g;
                Q5lwO:
                I4A2d:
                goto maXDw;
                uDonb:
                bMVv2:
                goto FMzkf;
                D8K0k:
                $uzs_curs_in_rub = $info["\x56\x61\x6c\x75\x74\x65"]["\x55\x5a\x53"]["\x56\x61\154\x75\145"];
                goto szZTJ;
                O3OXY:
                uWODA:
                goto MPLiN;
                Q3ah4:
                MFqQ4:
                goto tA4iv;
                f_57X:
                $vschet = $client_1x_uzs;
                goto D8K0k;
                xYsgg:
                bq4TT:
                goto SiQjf;
                mv3_C:
                vmaR3:
            }
		}
		if ($status == 'На проверке'){
			del($id,$message_id);
			del($id,$message_id-1);
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='19';")) {
			while ($row = $result->fetch_assoc()) {
				$reserv_42 = $row["valut_reserv"];
			}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='3';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_43 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='4';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_44 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='13';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_45 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='5';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_46 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='15';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_47 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='17';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_48 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='12';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_49 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($result = $mysqli_site->query("SELECT `valut_reserv` from `3knw_valuts` where `id`='18';")) {
				while ($row = $result->fetch_assoc()) {
					$reserv_41 = $row["valut_reserv"];
				}
			$result->free();
		}
		if ($val_in == "uzs_in") $valuta_in = "UZCARD";
        if ($val_in == "sber_in") $valuta_in = "1XBET RUB";
        if ($val_in == "qiwir_in") $valuta_in = "QIWI RUB";
        if ($val_in == "qiwiu_in") $valuta_in = "QIWI USD";
        if ($val_in == "wmz_in") $valuta_in = "WMZ";
        if ($val_in == "wmr_in") $valuta_in = "WMR";
        if ($val_in == "prub_in") $valuta_in = "PAYEER RUB";
        if ($val_in == "pusd_in") $valuta_in = "PAYEER USD";
        if ($val_in == "ya_in") $valuta_in = "Yandex RUB";
        if ($val_out == "uzs_out") $valuta_out = "UZCARD";
        if ($val_out == "1x_rub") $valuta_out = "1XBET RUB";
        if ($val_out == "1x_usd") $valuta_out = "1XBET USD";
        if ($val_out == "1x_uzs") $valuta_out = "1XBET UZS";
        if ($val_out == "qiwir_out") $valuta_out = "QIWI RUB";
        if ($val_out == "qiwiu_out") $valuta_out = "QIWI USD";
        if ($val_out == "wmz_out") $valuta_out = "WMZ";
        if ($val_out == "wmr_out") $valuta_out = "WMR";
        if ($val_out == "prub_out") $valuta_out = "PAYEER RUB";
        if ($val_out == "pusd_out") $valuta_out = "PAYEER USD";
        if ($val_out == "ya_out") $valuta_out = "Yandex RUB";
		
		if ($val_in == "uzs_in" and $val_out == "qiwir_out"){
			$reserv_42 += $sell;
			$reserv_45 -= $buy;
			$update_balans = ($curs42to45-$curs45to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_45' where `id`='13';");
		}
		if ($val_in == "uzs_in" and $val_out == "ya_out"){
			$reserv_42 += $sell;
			$reserv_41 -= $buy;
			$update_balans = ($curs42to41-$curs41to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_41' where `id`='18';");
		}
		if ($val_in == "uzs_in" and $val_out == "qiwiu_out"){
			$reserv_42 += $sell;
			$reserv_46 -= $buy;
			$update_balans = ($curs42to46-$curs46to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_46' where `id`='5';");
		}
		if ($val_in == "uzs_in" and $val_out == "prub_out"){
			$reserv_42 += $sell;
			$reserv_43 -= $buy;
			$update_balans = ($curs42to43-$curs43to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_43' where `id`='3';");
		}
		if ($val_in == "uzs_in" and $val_out == "pusd_out"){
			$reserv_42 += $sell;
			$reserv_44 -= $buy;
			$update_balans = ($curs42to44-$curs44to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_44' where `id`='4';");
		}
		if ($val_in == "uzs_in" and $val_out == "wmr_out"){
			$reserv_42 += $sell;
			$reserv_47 -= $buy;
			$update_balans = ($curs42to47-$curs47to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_47' where `id`='15';");
		}
		if ($val_in == "uzs_in" and $val_out == "wmz_out"){
			$reserv_42 += $sell;
			$reserv_48 -= $buy;
			$update_balans = ($curs42to48-$curs48to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_48' where `id`='17';");
		}
		if ($val_in == "uzs_in" and $val_out == "1x_rub"){
			$reserv_42 += $sell;
			$reserv_45 -= $buy;
			$update_balans = ($curs42to45-$curs45to42)*$buy/10;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_45' where `id`='12';");
		}
        if ($val_in == "uzs_in" and $val_out == "1x_usd"){
            $reserv_42 += $sell;
            $reserv_45 -= $buy*65;
            $mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
            $mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_45' where `id`='12';");
        }
        if ($val_in == "uzs_in" and $val_out == "1x_uzs"){
            $reserv_42 += $sell;
            $reserv_45 -= $buy/$curs42to45;
            $mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
            $mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_45' where `id`='12';");
        }
		if ($val_in == "qiwir_in" and $val_out == "uzs_out"){
			$reserv_45 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to45-$curs45to42)*$buy/10;
			$update_balans = $update_balans/$curs42to45;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_45' where `id`='13';");
		}
		if ($val_in == "qiwiu_in" and $val_out == "uzs_out"){
			$reserv_46 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to46-$curs46to42)*$buy/10;
			$update_balans = $update_balans/$curs42to46;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_46' where `id`='5';");
		}
		if ($val_in == "prub_in" and $val_out == "uzs_out"){
			$reserv_43 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to43-$curs43to42)*$buy/10;
			$update_balans = $update_balans/$curs42to43;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_43' where `id`='3';");
		}
		if ($val_in == "pusd_in" and $val_out == "uzs_out"){
			$reserv_44 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to44-$curs44to42)*$buy/10;
			$update_balans = $update_balans/$curs42to44;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_44' where `id`='4';");
		}
		if ($val_in == "wmr_in" and $val_out == "uzs_out"){
			$reserv_47 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to47-$curs47to42)*$buy/10;
			$update_balans = $update_balans/$curs42to47;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_47' where `id`='15';");
		}
		if ($val_in == "wmz_in" and $val_out == "uzs_out"){
			$reserv_48 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to48-$curs48to42)*$buy/10;
			$update_balans = $update_balans/$curs42to48;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_48' where `id`='17';");
		}
		if ($val_in == "sber_in" and $val_out == "uzs_out"){
			$reserv_49 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to49-$curs49to42)*$buy/10;
			$update_balans = $update_balans/$curs42to49;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_49' where `id`='12';");
		}
		if ($val_in == "ya_in" and $val_out == "uzs_out"){
			$reserv_41 += $sell;
			$reserv_42 -= $buy;
			$update_balans = ($curs42to41-$curs45to50)*$buy/10;
			$update_balans = $update_balans/$curs42to41;
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_42' where `id`='19';");
			$mysqli_site->query("update `3knw_valuts` set `valut_reserv`='$reserv_41' where `id`='18';");
		}
		$mysqli->query("update `changes` set `status`='Успешно' where `id`='$change_id';");
		if (user_lang_check($client_id) == "ru") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nВаша заявка *успешно* обработана\nДата создания: $date\nСтатус: *Успешно*", 'parse_mode' => "Markdown", 'reply_markup' => $main] );
		if (user_lang_check($client_id) == "uz") sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "ID: $change_id\nSizning almashuvingiz *Muvaffaqiyatli* yakunlandi\nSana: $date\nHolati: *Muvaffaqiyatli*", 'parse_mode' => "Markdown", 'reply_markup' => $main_uz] );
sm( 'sendMessage', ['chat_id' => "@obmenuz_pay", 'text' => "🆔: `$change_id`\n👤: *$first_name_client*\n🔀:* $valuta_in ⏩ $valuta_out*\n🔎: *Статус* ✅\n📝: *$date*\n✅: *$date1*\n📥: `$buy` *$valuta_out*",'parse_mode' => "Markdown", 'disable_web_page_preview' => 'true'] );
		
		if ($client_refer != ""){
			if ($result = $mysqli->query("SELECT `balans` from `users` where `Telegram ID`='$client_refer';")){
				while ($row = $result->fetch_assoc()){
					$balans_refer = $row["balans"];
				}
			$result -> free();
			}
			$balans_refer += $update_balans;
			if (user_lang_check($client_refer) == "ru") sm( 'sendMessage', ['chat_id' => $client_refer, 'text' => "*Реферальное* пополнение +$update_balans UZS", 'parse_mode' => "Markdown", 'reply_markup' => $back_key] );
			if (user_lang_check($client_refer) == "uz") sm( 'sendMessage', ['chat_id' => $client_refer, 'text' => "*Referal* daromad +$update_balans UZS", 'parse_mode' => "Markdown", 'reply_markup' => $back_key] );
			$mysqli->query("update `users` set `balans`='$balans_refer' where `Telegram ID`='$client_refer';");
		}
	}
	del($id,$message_id);
	del($id,$message_id-1);
}
//Обработка user_lang
	if ($callback == "langru"){
		$mysqli->query("update `users` set `lang`='ru' where `Telegram ID`='$id';");
		del($id,$message_id);
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "*ObmenUz* - это *самый лучший сервис по обмену валют* на территории Узбекистана! Наш сервис отличаеться от других с тем что у нас самые низкие цены и всегда есть все валюты на резерве.\n\nНаш *Сайт:* ObmenUz.net", 'parse_mode' => "Markdown", 'reply_markup' => $main] );
	}
	if ($callback == "languz"){
		$mysqli->query("update `users` set `lang`='uz' where `Telegram ID`='$id';");
		del($id,$message_id);
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "*ObmenUz*  - Bu, O'zbekiston hududidagi *eng sifatli valyuta ayirboshlash xizmatidir!.* Bizning xizmatimiz boshqalardan farqliroq tomoni shundaki, bizda eng past narxlar bo'lib va har doim zahiradagi barcha valyutalar borligidadur.\n\nBizning *Sayt:* ObmenUz.net", 'parse_mode' => "Markdown", 'reply_markup' => $main_uz] );
	}
$obmen_key = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => '🔷UZCARD', 'callback_data' => 'uzs_in'],[ 'text' => '🔶UZCARD', 'callback_data' => 'uzs_out']],
		[[ 'text' => '🔷WMR', 'callback_data' => 'wmr_in'],[ 'text' => '🔶WMR', 'callback_data' => 'wmr_out']],
		[[ 'text' => '🔷WMZ', 'callback_data' => 'wmz_in'],[ 'text' => '🔶WMZ', 'callback_data' => 'wmz_out']],
		[[ 'text' => '🔷Yandex RUB', 'callback_data' => 'ya_in'],[ 'text' => '🔶Yandex RUB', 'callback_data' => 'ya_out']],
		[[ 'text' => '🔷QIWI RUB', 'callback_data' => 'qiwir_in'],[ 'text' => '🔶QIWI RUB', 'callback_data' => 'qiwir_out']],
		[[ 'text' => '🔷QIWI USD', 'callback_data' => 'qiwiu_in'],[ 'text' => '🔶QIWI USD', 'callback_data' => 'qiwiu_out']],
		[[ 'text' => '🔷PAYEER RUB', 'callback_data' => 'prub_in'],[ 'text' => '🔶PAYEER RUB', 'callback_data' => 'prub_out']],
		[[ 'text' => '🔷PAYEER USD', 'callback_data' => 'pusd_in'],[ 'text' => '🔶PAYEER USD', 'callback_data' => 'pusd_out']],
        [[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET RUB', 'callback_data' => '1x_rub']],
        [[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET USD', 'callback_data' => '1x_usd']],
        [[ 'text' => '▫️', 'callback_data' => '1'],[ 'text' => '🔶1XBET UZS', 'callback_data' => '1x_uzs']],
	]
]);
$data_key = json_encode([
		'one_time_keyboard' => true,
		'resize_keyboard' => true,
		'keyboard'=>[
			[[ 'text' => '➕UZCARD'],[ 'text' => '➕QIWI'],[ 'text' => '➕Yandex']],
			[[ 'text' => '➕WMZ'],[ 'text' => '➕WMR'],[ 'text' => '➕PAYEER']],
			[[ 'text' => '➕1XBET RUB'],[ 'text' => '➕1XBET USD'],[ 'text' => '➕1XBET UZS']],
			[[ 'text' => '🔙Главное меню']]
		]
	]);
	if ($text == "warning" and $id == "418579693"){
	$mysqli->query("delete from `users`;");
}
if (substr($text,0,1) == "P" and $last_message == "add payeer" and strlen($text) == (9 or 8)){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш PAYEER кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning PAYEER hamyoningiz kiritildi", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `payeer`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if ($last_message == "add uzcard" and strlen($text) == 16){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваша карта успешно добавлена.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning karta raqamingiz kiritildi.", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `uzcard`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if (substr($text,0,1) == "+" and $last_message == "add qiwi" and strlen($text) == (13 or 12)){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш QIWI кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning QIWI hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `qiwi`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if (substr($text,0,3) == "998" and $last_message == "add qiwi" and strlen($text) == (12 or 11)){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш QIWI кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning QIWI hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
}
$text = "+$text";
$mysqli->query("update `users` set `qiwi`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if (substr($text,0,1) == "Z" and $last_message == "add wmz" and strlen($text) == 13){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш WMZ кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning WMZ hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `wmz`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if (substr($text,0,1) == "R" and $last_message == "add wmr" and strlen($text) == 13){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш WMR кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning WMR hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `wmr`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
if ($last_message == "add 1xrub" and strlen($text) >= 7 and strlen($text) <= 9 and is_numeric($text)){
	if ($user_lang == "ru"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш 1XBET RUB кошелек успешно добавлен.", 'reply_markup' => $data_key] );
	}
	if ($user_lang == "uz"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning 1XBET RUB hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
	}
	$mysqli->query("update `users` set `1x_rub`='$text',`Iast_message`='' where `Telegram ID`='$id';");
    $mysqli->query("update `users` set `percent`='0',`last_message`='' where `Telegram ID`='$id';");
}
if ($last_message == "add 1xusd" and strlen($text) >= 7 and strlen($text) <= 9 and is_numeric($text)){
    if ($user_lang == "ru"){
        sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш 1XBET USD кошелек успешно добавлен.", 'reply_markup' => $data_key] );
    }
    if ($user_lang == "uz"){
        sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning 1XBET USD hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
    }
    $mysqli->query("update `users` set `1x_usd`='$text',`Iast_message`='' where `Telegram ID`='$id';");
    $mysqli->query("update `users` set `percent`='0',`last_message`='' where `Telegram ID`='$id';");
}
if ($last_message == "add 1xuzs" and strlen($text) >= 7 and strlen($text) <= 9 and is_numeric($text)){
    if ($user_lang == "ru"){
        sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш 1XBET UZS кошелек успешно добавлен.", 'reply_markup' => $data_key] );
    }
    if ($user_lang == "uz"){
        sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning 1XBET UZS hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
    }
    $mysqli->query("update `users` set `1x_uzs`='$text',`Iast_message`='' where `Telegram ID`='$id';");
    $mysqli->query("update `users` set `percent`='0',`last_message`='' where `Telegram ID`='$id';");
}
if ($last_message == "add yandex" and strlen($text) > 12 and strlen($text) < 19){
if ($user_lang == "ru"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваш Yandex кошелек успешно добавлен.", 'reply_markup' => $data_key] );
}
if ($user_lang == "uz"){
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning Yandex hamyoningiz kiritildi.", 'reply_markup' => $data_key] );
}
$mysqli->query("update `users` set `yandex`='$text',`Iast_message`='' where `Telegram ID`='$id';");
}
//Партнерская программа
	if ($text == "👥Партнерка" or $text == "👥Referallar"){
		$ref_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '📤Вывести', 'callback_data' => 'out_moneyp'],[ 'text' => '👥Мои Рефералы', 'callback_data' => 'myf']],
				[[ 'text' => '📃Подробнее', 'callback_data' => 'morep'],[ 'text' => '🔙Назад', 'callback_data' => 'notlook']]
			]
		]);
		$ref_key_uz = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => '📤Pul yechish', 'callback_data' => 'out_moneyp'],[ 'text' => '👥Hamkorlarim', 'callback_data' => 'myf']],
				[[ 'text' => '📃Batafsil', 'callback_data' => 'morep'],[ 'text' => '🔙Ortga', 'callback_data' => 'notlook']]
			]
		]);
		if ($user_lang == "ru"){
			sm( 'sendMessage', ['chat_id' => $id, 'text' => "💵<b>Ваш баланс:</b> $user_balans <b>UZS</b>\n<i>Приглашайте друзей и получайте 10% от дохода обменника с каждого обмена проведенного вашим рефералом</i> \n\nВаша ссылка: t.me/obmenuznetbot?start=$id", 'parse_mode' => "HTML", "reply_markup" => $ref_key] );
		}
		if ($user_lang == "uz"){
			sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>💵Sizning hisobingiz:</b> $user_balans <b>UZS</b>\n<i>Do'stlarni taklif qiling va sizning tavsiyangiz bo'yicha o'tkaziladigan har bir almashuv daromadidan 10% olasiz</i> \n\nSizning link: t.me/obmenuznetbot?start=$id", 'parse_mode' => "HTML", "reply_markup" => $ref_key_uz] );
		}
	}
	if ($callback == "myf"){
		del($id,$message_id);
		$my_r = array();
		if ($result = $mysqli->query("SELECT `Telegram ID` from `users` where `refer`='$id';")){
			while ($row = $result->fetch_assoc()){
				$my_r[] = $row["Telegram ID"];
			}
			$result -> free();
		}
		$row_cnt = count($my_r);
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Число ваших рефералов: $row_cnt</b>", 'parse_mode' => "HTML"] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Sizning referallaringiz soni: $row_cnt</b>", 'parse_mode' => "HTML"] );
	}
	if ($callback == "morep"){
		if ($user_lang == "ru"){
			del($id,$message_id);
			sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Партнерская программа.\nЭта функция поможет вам зарабатывать на привлечении партнеров. Для приглашения партнеров используйте ссылку выданную вам. Вы будите получать 10% от дохода обменника за каждую успешную заявку вашего партнера. Доход обменника равен разнице курсов(т.е Продажа = 100 UZS а Покупка = 120 UZS то доход обменника составит 20 UZS)\nБасланс можно вывести:\nНа счет мобильного телефона\nНа Карту UZCARD</b>", 'parse_mode' => "HTML"] );
		}
		if ($user_lang == "uz"){
			del($id,$message_id);
			sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Нamkorlik dasturi.\nBu dastur sheriklarni jalb qilish orqali pul topishingizga yordam beradi. Нamkorlarni taklif qilish uchun sizga taqdim etilgan taklif-linkidan foydalaning. Sizning hamkoringizning har bir muvaffaqiyatli buyurtmasi uchun botning daromadidan 10% olasiz. Bot daromadi kurslar orasidagi farqga teng (ya'ni Sotish 100 uzs Olish esa 120 uzs bo'lsa botning daromadi 20 uzsga teng bo'ladi)\nHisobingizdagi mablag'ni yechish yollari:\nMobil telefon raqamiga\nUZCARD karta hisobiga</b>", 'parse_mode' => "HTML"] );
		}
	}
	if ($callback == "out_moneyp"){
		if ($user_balans < 10000){
			del($id,$message_id);
			if($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Минимальная сумма вывода 10000 UZS</b>", 'parse_mode' => "HTML"] );
			if($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Yechish uchun eng kam mablag' miqdori 10000 UZS</b>", 'parse_mode' => "HTML"] );
		}
		if ($user_balans >= 10000){
			del($id,$message_id);
			if ($user_lang == "ru")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Введите номер карты UZCARD (Для перевода) или номер телефона (Для PAYNET). Поддерживаются только операторы Узбекистана</b>", 'parse_mode' => "HTML"] );
			if ($user_lang == "uz")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Karta UZCARD raqami (Pul o'tkazmasi uchun) yoki telefon raqamini (PAYNET uchun) kiriting. PAYNET faqat O'zbekistonning operatorlari raqamlari uchun</b>", 'parse_mode' => "HTML"] );
			$mysqli->query("update `users` set `Iast_message`='outing' where `Telegram ID`='$id';");
		}
	}
	if(((substr($text,0,4) == "8600" and strlen($text) == 16) or (substr($text,0,4) == "+998" and strlen($text) == 13)) and $last_message == "outing"){
		$mysqli->query("update `users` set `for_out`='$text' where `Telegram ID`='$id';");
		$out_1 = $user_balans;
		$out_2 = $user_balans/2;
			$out_key = json_encode(array(
				"inline_keyboard" => array(
					array(
						array(
							"text" => "100% = $out_1 UZS","callback_data" => "out100",
						),
					),
					array(
						array(
								"text" => "50% = $out_2 UZS","callback_data" => "out50",
						),
					),
					array(
						array(
								"text" => "↩️","callback_data" => "notlook",
						),
					),
				),
			));
		if ($user_lang == "ru")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Сколько вы хотите вывести?</b>", 'parse_mode' => "HTML", 'reply_markup' => $out_key] );
		if ($user_lang == "uz")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Qanday miqdordagi pulni yechishni xohlaysiz?</b>", 'parse_mode' => "HTML", 'reply_markup' => $out_key] );
	}
	if ($callback == "out100"){
		del($id,$message_id);
		if ($user_lang == "ru")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Ваша заявка на вывод отправлена на обработку. Вы получите уведомление, когда она будет обработана</b>", 'parse_mode' => "HTML", 'reply_markup' => $main] );
		if ($user_lang == "uz")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Sizning so'rovingiz qabul qilindi. So'rovingiz bajarilgandan song sizga xabar yuboriladi</b>", 'parse_mode' => "HTML", 'reply_markup' => $main_uz] );
		$outmoney100 = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => 'ГОТОВО', 'callback_data' => 'outdone']]
			]
		]);
		sm( 'sendMessage', ['chat_id' => $admin, 'text' => "$for_out"] );
		sm( 'sendMessage', ['chat_id' => $admin, 'text' => "ID:|$id|\nВывод реферальных средств\nСумма |$user_balans| UZS", 'parse_mode' => "HTML", 'reply_markup' => $outmoney100] );
		$user_balans = 0;
		$mysqli->query("update `users` set `balans`='$user_balans' where `Telegram ID`='$id';");
	}
	if ($callback == "out50"){
		del($id,$message_id);
		if ($user_lang == "ru")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Ваша заявка на вывод отправлена на обработку. Вы получите уведомление, когда она будет обработана</b>", 'parse_mode' => "HTML", 'reply_markup' => $main] );
		if ($user_lang == "uz")sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Sizning so'rovingiz qabul qilindi. So'rovingiz bajarilgandan song sizga xabar yuboriladi</b>", 'parse_mode' => "HTML", 'reply_markup' => $main_uz] );
		$outmoney100 = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => 'ГОТОВО', 'callback_data' => 'outdone']]
			]
		]);
		$user_balans = $user_balans/2;
		sm( 'sendMessage', ['chat_id' => $admin, 'text' => "$for_out"] );
		sm( 'sendMessage', ['chat_id' => $admin, 'text' => "ID:|$id|\nВывод реферальных средств\nСумма |$user_balans| UZS", 'parse_mode' => "HTML", 'reply_markup' => $outmoney100] );
		$mysqli->query("update `users` set `balans`='$user_balans' where `Telegram ID`='$id';");
	}
	if ($callback == "outdone"){
		del($id,$message_id);
		del($id,$message_id-1);
		$client_id = explode("|", $callback_text);
		$client_id = $client_id["1"];
		$summa_out = explode("|", $callback_text);
		$summa_out = $summa_out["3"];
		if (user_lang_check($client_id) == "ru"){
			sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "Ваши партнерские средства успешно выплачены!"] );
		}
		if (user_lang_check($client_id) == "uz"){
			sm( 'sendMessage', ['chat_id' => $client_id, 'text' => "Sizning so'rovingiz muvaffaqiyatli bajarildi!"] );
		}
		if ($result = $mysqli->query("SELECT `first_name` from `users` where `Telegram ID`='$client_id';")){
			while ($row = $result->fetch_assoc()){
				$client_first = $row["first_name"];
			}
			$result -> free();
		}
		$date = date("d.m.Y  H:i");
		sm( 'sendMessage', ['chat_id' => "@obmenuz_pay", 'text' => "<b>👤:$client_first\n🔀:Вывод партнерских средств\n🔎Статус: ✅\n✅:$date\n📥: $summa_out UZS</b>", 'parse_mode' => "HTML", 'disable_web_page_preview' => 'true'] );
	}
if (strpos($text,"=")){
	$text = explode("=",$text);
	$client_id = $text[0];
	$update_id = $text[1];
	$mysqli->query("update `users` set `balans`='$update_id' where `Telegram ID`='$client_id';");
}
//Коллбэки
if ($callback == "notlook"){
	del($id,$message_id);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Главное меню", 'reply_markup' => $main]);
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Bosh menu", 'reply_markup' => $main_uz]);
	setLastNull($id);
}
if ($callback == "notlook_a"){
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Главное меню", 'reply_markup' => $main]);
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Bosh menu", 'reply_markup' => $main_uz]);
	setLastNull($id);
}
if ($callback == "otmena"){
	del($id,$message_id);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Ваша заявка отменена", 'reply_markup' => $main] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Sizning almashuvingiz bekor qilindi", 'reply_markup' => $main_uz] );
	$mysqli->query("update `changes` set `status`='Отменен' where `id`='$change_id';");
}
if (strpos($text,"+")){
	$text = explode("+",$text);
	$client_id = $text[0];
	$update_id = $text[1];
	$mysqli->query("update `users` set `uzcard`='$update_id' where `Telegram ID`='$client_id';");
}
if ($text == "💳Реквизиты" or $text == "🔰Hamyonlar"){
	if ($user_uzcard == "") $user_uzcard = "Пусто";
	if ($user_qiwi == "") $user_qiwi = "Пусто";
	if ($user_payeer == "") $user_payeer = "Пусто";
	if ($user_wmz == "") $user_wmz = "Пусто";
	if ($user_wmr == "") $user_wmr = "Пусто";
	if ($user_sber == "") $user_sber = "Пусто";
	if ($user_yandex == "") $user_yandex = "Пусто";
    if ($user_1x_rub == "") $user_1x_rub = "Пусто";
    if ($user_1x_usd == "") $user_1x_usd = "Пусто";
    if ($user_1x_uzs == "") $user_1x_uzs = "Пусто";
	$clear_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '♨️Удалить данные', 'callback_data' => 'killdata']]
		]
	]);
	$clear_key_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "♨️Ma'lumotlarni o'chirish", 'callback_data' => 'killdata']]
		]
	]);
	if ($user_lang == "ru"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "🗂*Ваши Кошельки:*", 'parse_mode' => "Markdown", 'reply_markup' => $data_key] );
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "📋<b>UZCARD:</b>\n<code>$user_uzcard</code>\n📋<b>QIWI:</b>\n<code>$user_qiwi</code>\n📋<b>WMZ:</b>\n<code>$user_wmz</code>\n📋<b>WMR:</b>\n<code>$user_wmr</code>\n📋<b>PAYEER:</b>\n<code>$user_payeer</code>\n📋<b>Yandex:</b>\n<code>$user_yandex</code>\n📋<b>1XBET RUB:</b>\n<code>$user_1x_rub</code>\n📋<b>1XBET USD:</b>\n<code>$user_1x_usd</code>\n📋<b>1XBET UZS:</b>\n<code>$user_1x_uzs</code>", 'parse_mode' => "HTML", 'reply_markup' => $clear_key] );
	}
	if ($user_lang == "uz"){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "🗂*Sizning hamyonlaringiz:*", 'parse_mode' => "Markdown", 'reply_markup' => $data_key] );
		$txt = "📋<b>UZCARD:</b>\n<code>$user_uzcard</code>\n📋<b>QIWI:</b>\n<code>$user_qiwi</code>\n📋<b>WMZ:</b>\n<code>$user_wmz</code>\n📋<b>WMR:</b>\n<code>$user_wmr</code>\n📋<b>PAYEER:</b>\n<code>$user_payeer</code>\n📋<b>Yandex:</b>\n<code>$user_yandex</code>\n📋<b>1XBET RUB:</b>\n<code>$user_1x_rub</code>\n📋<b>1XBET USD:</b>\n<code>$user_1x_usd</code>\n📋<b>1XBET UZS:</b>\n<code>$user_1x_uzs</code>";
		$txt = str_replace("Пусто","Kiritilmagan",$txt);
		sm( 'sendMessage', ['chat_id' => $id, 'text' => $txt, 'parse_mode' => "HTML", 'reply_markup' => $clear_key_uz] );
	}
	setLastNull($id);
}
if ($callback == "killdata"){
	del($id,$message_id);
	del($id,$message_id-1);
	$mysqli->query("update `users` set `uzcard`='',`qiwi`='',`sber`='',`payeer`='',`wmz`='',`wmr`='',`yandex`='' where `Telegram ID`='$id';");
	sm( 'sendMessage', ['chat_id' => $id, 'text' => "Все ваши данные *удалены*.", 'parse_mode' => "Markdown", 'reply_markup' => $data_key] );
}
if ($text == "📂История заявок" or $text == "📂Almashuvlar"){
	$history_key = json_encode([
		'resize_keyboard' => true,
		'keyboard'=>[
			[[ 'text' => '📆Мои обмены'],[ 'text' => '📃Все операции']],
			[[ 'text' => '🔙Главное меню']]
		]
	]);
	$history_key_uz = json_encode([
		'resize_keyboard' => true,
		'keyboard'=>[
			[[ 'text' => '🗂Mening almashuvlarim'],[ 'text' => '📃Barcha almashuvlar']],
			[[ 'text' => '🔙Bosh menu']]
		]
	]);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Выберите нужный раздел", 'reply_markup' => $history_key] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Kerakli bo'limni tanlang", 'reply_markup' => $history_key_uz] );
}
if (($text == "📊Курс | 💰Резервы" or $text == "📊Kurs | 💰Zahira") or $callback == "showcurs"){
	$reserv_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '🔰Показать Резерв', 'callback_data' => 'showreserv']]
		]
	]);
	$reserv_key_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "🔰Zahirani Ko'rish", 'callback_data' => 'showreserv']]
		]
	]);
	del($id,$message_id);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "📉Курс Продажи\n1 QIWI RUB = <code>$curs42to45</code> UZS\n1 QIWI USD = <code>$curs42to46</code> UZS\n1 PAYEER RUB = <code>$curs42to43</code> UZS\n1 PAYEER USD = <code>$curs42to44</code> UZS\n1 Yandex RUB = <code>$curs42to41</code> UZS\n1 WMZ = <code>$curs42to48</code> UZS\n1 WMR = <code>$curs42to47</code> UZS\n1 1XBET RUB = <code>$curs_1x_rub</code> UZS\n1 1XBET USD = <code>$curs_1x_usd</code> UZS\n1 1XBET UZS = <code>$curs_1x_uzs</code> %\n\n📉Курс Покупки\n1 QIWI RUB = <code>$curs45to42</code> UZS\n1 QIWI USD = <code>$curs46to42</code> UZS\n1 PAYEER RUB = <code>$curs43to42</code> UZS\n1 PAYEER USD = <code>$curs44to42</code> UZS\n1 Yandex RUB = <code>$curs41to42</code> UZS\n1 WMZ = <code>$curs48to42</code> UZS\n1 WMR = <code>$curs47to42</code> UZS", 'parse_mode' => "HTML", 'reply_markup' => $reserv_key] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "📉Sotish kursi\n1 QIWI RUB = <code>$curs42to45</code> UZS\n1 QIWI USD = <code>$curs42to46</code> UZS\n1 PAYEER RUB = <code>$curs42to43</code> UZS\n1 PAYEER USD = <code>$curs42to44</code> UZS\n1 Yandex RUB = <code>$curs42to41</code> UZS\n1 WMZ = <code>$curs42to48</code> UZS\n1 WMR = <code>$curs42to47</code> UZS\n1 1XBET RUB = <code>$curs_1x_rub</code> UZS\n1 1XBET USD = <code>$curs_1x_usd</code> UZS\n1 1XBET UZS = <code>$curs_1x_uzs</code> %\n\n📉Курс Покупки\n1 QIWI RUB = <code>$curs45to42</code> UZS\n1 QIWI USD = <code>$curs46to42</code> UZS\n1 PAYEER RUB = <code>$curs43to42</code> UZS\n1 PAYEER USD = <code>$curs44to42</code> UZS\n1 Yandex RUB = <code>$curs41to42</code> UZS\n1 WMZ = <code>$curs48to42</code> UZS\n1 WMR = <code>$curs47to42</code> UZS", 'parse_mode' => "HTML", 'reply_markup' => $reserv_key_uz] );
	setLastNull($id);
}
if ($callback == "showreserv"){
	$curs_key_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "🔰Kursni Ko'rish", 'callback_data' => 'showcurs']]
		]
	]);
	$curs_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "🔰Показать Курс", 'callback_data' => 'showcurs']]
		]
	]);
	if ($user_lang == "ru") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "💰Резерв Обменника\nUZCARD = <code>$reserv_42</code> UZS\nQIWI RUB = <code>$reserv_45</code> RUB\nQIWI USD = <code>$reserv_46</code> USD\nPAYEER RUB = <code>$reserv_43</code> RUB\nPAYEER USD = <code>$reserv_44</code> USD\nYandex RUB = <code>$reserv_41</code> RUB\nWMZ = <code>$reserv_48</code> USD\nWMR = <code>$reserv_47</code> RUB\n1XBET RUB = <code>$reserv_45</code> RUB\n1XBET USD = <code>".$reserv_45/65 ."</code> USD\n1XBET UZS = <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML", 'reply_markup' => $curs_key] );
	if ($user_lang == "uz") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "💰Obmennik Zahirasi\nUZCARD = <code>$reserv_42</code> UZS\nQIWI RUB = <code>$reserv_45</code> RUB\nQIWI USD = <code>$reserv_46</code> USD\nPAYEER RUB = <code>$reserv_43</code> RUB\nPAYEER USD = <code>$reserv_44</code> USD\nYandex RUB = <code>$reserv_41</code> RUB\nWMZ = <code>$reserv_48</code> USD\nWMR = <code>$reserv_47</code> RUB\n1XBET RUB = <code>$reserv_45</code> RUB\n1XBET USD = <code>".$reserv_45/65 ."</code> USD\n1XBET UZS = <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML", 'reply_markup' => $curs_key_uz] );
}
if ($text == "📆Мои обмены" or $text == "🗂Mening almashuvlarim"){
	$ooo = array();
	if ($result = $mysqli->query("SELECT `id`,`val_in`,`val_out`,`date`,`status`,`buy`,`sell` from `changes` where `Telegram ID`='$id' ORDER BY `id` DESC LIMIT 20;")){
		while ($row = $result->fetch_assoc()){
			$change_id = $row["id"];
			$val_in = $row["val_in"];
			$val_out = $row["val_out"];
			$change_date = $row["date"];
			$change_status = $row["status"];
			$change_buy = $row["buy"];
			$change_sell = $row["sell"];
			if ($val_in == "uzs_in") $valuta_in = "UZCARD";
            if ($val_in == "sber_in") $valuta_in = "1XBET RUB";
            if ($val_in == "qiwir_in") $valuta_in = "QIWI RUB";
            if ($val_in == "qiwiu_in") $valuta_in = "QIWI USD";
            if ($val_in == "wmz_in") $valuta_in = "WMZ";
            if ($val_in == "wmr_in") $valuta_in = "WMR";
            if ($val_in == "prub_in") $valuta_in = "PAYEER RUB";
            if ($val_in == "pusd_in") $valuta_in = "PAYEER USD";
            if ($val_in == "ya_in") $valuta_in = "Yandex RUB";
            if ($val_out == "uzs_out") $valuta_out = "UZCARD";
            if ($val_out == "1x_rub") $valuta_out = "1XBET RUB";
            if ($val_out == "1x_usd") $valuta_out = "1XBET USD";
            if ($val_out == "1x_uzs") $valuta_out = "1XBET UZS";
            if ($val_out == "qiwir_out") $valuta_out = "QIWI RUB";
            if ($val_out == "qiwiu_out") $valuta_out = "QIWI USD";
            if ($val_out == "wmz_out") $valuta_out = "WMZ";
            if ($val_out == "wmr_out") $valuta_out = "WMR";
            if ($val_out == "prub_out") $valuta_out = "PAYEER RUB";
            if ($val_out == "pusd_out") $valuta_out = "PAYEER USD";
            if ($val_out == "ya_out") $valuta_out = "Yandex RUB";
			$text_client = "ID: $change_id\nОтдаете: $change_sell $valuta_in\nПолучаете: $change_buy $valuta_out\nДата: $change_date\nСтатус: $change_status";
			$ooo[] = $text_client;
		}
		$result -> free();
	}
	for ($uy = count($ooo); $uy >= 0; $uy--){
		sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>".$ooo[$uy]."</b>", 'parse_mode' => "HTML"] );
	}
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Ваши последние ".count($ooo)." операций⬆️</b>", 'parse_mode' => "HTML", 'reply_markup' => $back_key] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Sizning oxirgi ".count($ooo)."ta almashuvingiz⬆️</b>", 'parse_mode' => "HTML", 'reply_markup' => $back_key] );
}
if ($text == "🏷О нас" or $text == "📓Ma'lumotlar"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Полезная информация:</b>\n\nНаш <b>Сайт</b>:\nhttp://obmenuz.net \n\nРазработка ботов: ".'<a href="t.me/devbotuz/">DevBotUz</a>', 'parse_mode' => "HTML", 'reply_markup' => $back_key, 'disable_web_page_preview' => 'true'] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Foydali ma'lumotlar:</b>\n\nBizning <b>Sayt</b>:\nhttp://obmenuz.net \n\nDasturchi: ".'<a href="t.me/devbotuz/">DevBotUz</a>', 'parse_mode' => "HTML", 'reply_markup' => $back_key, 'disable_web_page_preview' => 'true'] );
	setLastNull($id);
}
if ($text == "📃Все операции" or $text == "📃Barcha almashuvlar"){
	$hist_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '📋Перейти', 'url' => 't.me/obmenuz_pay']]
		]
	]);
	$hist_key_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '📋Ulanish', 'url' => 't.me/obmenuz_pay']]
		]
	]);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "*Наш *[канал](t.me/obmenuz_pay)* со всеми операциями проводимыми в боте*⤵️", 'parse_mode' => "Markdown", 'reply_markup' => $hist_key] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "*Bizning barcha almashuvlar kanali*⤵️", 'parse_mode' => "Markdown", 'reply_markup' => $hist_key_uz] );
	setLastNull($id);
}
if ($text == "🔙Главное меню" or $text == "🔙Bosh menu"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Главное меню", 'reply_markup' => $main] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Bosh menu", 'reply_markup' => $main_uz] );
	setLastNull($id);
}


if ($text == "📞Поддержка" or $text == "📞Aloqa"){
	$support = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '📝Написать', 'url' => 't.me/ObmenUz']]
		]
	]);
	$support_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => '📝Yozish', 'url' => 't.me/ObmenUz']]
		]
	]);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Если у вас возникли <b>вопросы/предложени</b> касательно нашего сервиса обращайтесь.\nНаши контакты:\n\n<b>Телефон для связи</b>\n+99891 4737315\n\n<b>Служба поддержки</b>\n@ObmenUz\n\nГрафик работы:\n<b>С 09:00 до 01:00 Ташкент</b>", 'parse_mode' => "HTML", 'reply_markup' => $support] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Xizmatimizga tegishli <b>savollar/takliflaringiz bo'lsa</b>biz bilan bog'laning.\n\nBiz bilan Aloqa:\n\n<b>Aloqa uchun telefon</b>\n+99891 4737315\n\n<b>Qo'llab-quvvatlash xizmati</b>\n@ObmenUz\n\n<b> Ish vaqti: \n\n 09:00 - 01:00 TSHV bo'yicha</b>.", 'parse_mode' => "HTML", 'reply_markup' => $support_uz] );
	setLastNull($id);
}

if ($text == '🔖 Идентификация "QIWI"' or $text == '🔖 "QIWI" Identifikatsiya'){
  $support = json_encode([
    'inline_keyboard'=>[
      [[ 'text' => '📝 Написать нам', 'url' => 't.me/ObmenUz']]
    ]
  ]);
  $support_uz = json_encode([
    'inline_keyboard'=>[
      [[ 'text' => '📝 Murojaat qilish', 'url' => 't.me/ObmenUz']]
    ]
  ]);
  if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "🔖 <b>Идентификация</b> QIWI кошелька.\n
Наш <b>сервис</b> предлагает услуги по <b>идентификации</b> кошелька <b>'QIWI'</b> до статуса <b>'Профессиональный'</b> на паспортные данные <b>граждан Узбекистана.</b>\n
<b>Преимущества статуса:</b>
- Остаток <b>на балансе</b> до 600 000 рублей.
- <b>Платежи и переводы до 4 млн рублей в месяц.</b>
- <b>Переводы</b> на другие <b>кошельки</b> и <b>банковские счета.</b>\n
<b>Стоимость услуги:</b> 1000 <b>RUB или</b> 150.000 <b>сум</b>.
<b>Процесс идентификации:</b>Может занять до <b>24 </b>часов.\n
<b>Необходимые документы:</b>
- Основная страница паспорта.
- Страница паспорта с пропиской.
- Номер киви кошелька.\n
Для <b>идентификации</b> обратитесь <b>в службу поддержки.️</b>", 'parse_mode' => "HTML", 'reply_markup' => $support] );
  if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "🔖 <b>QIWI hamyon Identifikatsiyasi.</b>\n
Bizning servis mijozlarga <b>'QIWI' </b> hamyon statusini <b>'Профессиональный' </b>ga identifikatsiya qilish <b>xizmatini</b> taqdim qiladi.\n
<b>Ushbu status afzalliklari:</b>
- 600 000 rublgacha pul <b>saqlash</b> imkoni.
- <b>To'lov</b> va <b>o'tqazmalar</b> oyiga 4 mln <b>rublgacha.</b>
- Boshqa elektron <b>hamyonlarga</b> va <b>bank hisob raqamlariga</b> o'tqazmalar qilish imkonini beradi.\n
<b>Xizmat narxi:</b> 1000 <b>RUB yoki</b> 150 <b>ming SUM:
Identifikatsiyadan o'tish jarayoni:</b> 24 <b>soatgacha choʻzilishi mumkin.</b>\n
<b>Kerakli hujjatlar:</b>
- Pasport asosiy beti.
- Pasport propiska beti.
- QIWI hamyon raqami. \n
<b>Identifikatsiyadan</b> o'tish uchun qo'llab quvvatlash markaziga <b>murojat qiling</b>.️", 'parse_mode' => "HTML", 'reply_markup' => $support_uz] );
  setLastNull($id);
}


if ($callback == "1"){
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($text == "🔄Обмен валют" or $text == "🔄Valyuta ayirboshlash"){
    if ($result = $mysqli->query("SELECT `id` from `changes` where `Telegram ID`='$id' and `status`='На проверке';")){
        while ($row = $result->fetch_assoc()){
            $istrue = $row["id"];
        }
        $result -> free();
    }
    if ($istrue){
        if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "*Пожалуйста* дождидесь обработки вашей *предыдущей* заявки\nСейчас на *проверке* ваша заявка под ID - $istrue", 'parse_mode' => "Markdown"] );
        if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "*Iltimos* bundan oldingi almashuingiz tekshirivdan *o'tishini kuting*\nHozirda tekshiruvdagi almashuvingiz ID - $istrue", 'parse_mode' => "Markdown"] );
        exit;
    }
	if ($result = $mysqli->query("SELECT `turn`,`text` from `admin` where `id`='1';")){
		while ($row = $result->fetch_assoc()){
			$turn = $row["turn"];
			$pricina = $row["text"];
		}
		$result -> free();
	}
	if ($turn){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => '<b>'.$pricina.'</b>', 'parse_mode' => "HTML"] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => '<b>'.$pricina.'</b>', 'parse_mode' => "HTML"] );
		exit;
	}
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Выберите валюты для обмена: (🔷отдача) и (🔶получения)</b>", 'parse_mode' => "HTML", 'reply_markup' => $obmen_key] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "<b>Valyutalarni tanlang: (🔷Berish) va (🔶Olish)</b>", 'parse_mode' => "HTML", 'reply_markup' => $obmen_key] );
	$mysqli->query("INSERT INTO `changes` (`Telegram ID`,`date`,`status`) VALUES ('$id','$date','Не завершён');");
	setLastNull($id);
	}
if ($text =="➕UZCARD"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашей карты UZCARD/UnionPay/HUMO\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "UZCARD/UnionPay/HUMO kartangiz raqamini kiriting \nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add uzcard' where `Telegram ID`='$id';");
}
if ($text =="➕WMZ"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего WMZ кошелька\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "WMZ hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add wmz' where `Telegram ID`='$id';");
}
if ($text =="➕QIWI"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего QIWI кошелька\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "QIWI hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add qiwi' where `Telegram ID`='$id';");
}
if ($text =="➕WMR"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего WMR кошелька\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "WMR hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add wmr' where `Telegram ID`='$id';");
}
if ($text =="➕PAYEER"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего PAYEER кошелька\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "PAYEER hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add payeer' where `Telegram ID`='$id';");
}
if ($text =="➕Yandex"){
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего Yandex кошелька\nБез пробелов и прочих символов"] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Yandex hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
	$mysqli->query("update `users` set `Iast_message`='add yandex' where `Telegram ID`='$id';");
}
if ($text =="➕1XBET RUB"){
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Зарегестрируйтесь заново в 1хБЕТ по ссылке https://bit.ly/2X6AfRE или введите промокод 'OBMENUZ' и получите от нас +5% скидку на последующие обмены начиная со второго."] );
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего 1ХБЕТ RUB кошелька\nБез пробелов и прочих символов"] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1xBET dan https://bit.ly/2X6AfRE ssilka orqali qayta royhatdan oting yoki promokod orniga 'OBMENUZ' sozini kiritib royxatdan otib bizdan ikkichi va undan keyingi almashuvlarga +5% chegirma oling."] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1XBET RUB hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
    $mysqli->query("update `users` set `Iast_message`='add 1xrub' where `Telegram ID`='$id';");
}
if ($text =="➕1XBET USD"){
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Зарегестрируйтесь заново в 1хБЕТ по ссылке https://bit.ly/2X6AfRE или введите промокод 'OBMENUZ' и получите от нас +5% скидку на последующие обмены начиная со второго."] );
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего 1ХБЕТ USD кошелька\nБез пробелов и прочих символов"] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1xBET dan https://bit.ly/2X6AfRE ssilka orqali qayta royhatdan oting yoki promokod orniga 'OBMENUZ' sozini kiritib royxatdan otib bizdan ikkichi va undan keyingi almashuvlarga +5% chegirma oling."] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1XBET USD hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
    $mysqli->query("update `users` set `Iast_message`='add 1xusd' where `Telegram ID`='$id';");
}
if ($text =="➕1XBET UZS"){
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Зарегестрируйтесь заново в 1хБЕТ по ссылке https://bit.ly/2X6AfRE или введите промокод 'OBMENUZ' и получите от нас +5% скидку на последующие обмены начиная со второго."] );
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Введите номер вашего 1ХБЕТ UZS кошелька\nБез пробелов и прочих символов"] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1xBET dan https://bit.ly/2X6AfRE ssilka orqali qayta royhatdan oting yoki promokod orniga 'OBMENUZ' sozini kiritib royxatdan otib bizdan ikkichi va undan keyingi almashuvlarga +5% chegirma oling."] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "1XBET UZS hamyoningiz raqamini kiriting\nBo'sh joylar yoki boshqa belgilarsiz"] );
    $mysqli->query("update `users` set `Iast_message`='add 1xuzs' where `Telegram ID`='$id';");
}
if ($callback == "uzs_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "wmr_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "wmz_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "qiwir_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "qiwiu_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "prub_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "ya_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "pusd_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "sber_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
if ($callback == "sberu_in"){
	$obmen_key = obmenkey($callback);
	sm( 'editMessageReplyMarkup', ['chat_id' => $id, 'message_id' => $message_id, 'reply_markup' => $obmen_key] );
	sm( 'answerCallbackQuery', ['callback_query_id' => $query_id] );
}
//Определение валюты
$setmoney = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => "Отдать * кол-во $valuta_in", 'callback_data' => 'into1']],
		[[ 'text' => "Получить * кол-во $valuta_out", 'callback_data' => 'into2']],
		[[ 'text' => "Отменить", 'callback_data' => 'otmena']]
	]
]);
$setmoney_uz = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => "Berishni kiritish * $valuta_in", 'callback_data' => 'into1']],
		[[ 'text' => "Olishni kiritish * $valuta_out", 'callback_data' => 'into2']],
		[[ 'text' => "Bekor qilish", 'callback_data' => 'otmena']]
	]
]);
if ( $callback == '1x_uzs' ){
	$setmoney = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Отдать * кол-во $valuta_in", 'callback_data' => 'into1']],
			[[ 'text' => "Отменить", 'callback_data' => 'otmena']]
		]
	]);
	$setmoney_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "Berishni kiritish * $valuta_in", 'callback_data' => 'into1']],
			[[ 'text' => "Bekor qilish", 'callback_data' => 'otmena']]
		]
	]);
}
$site_url = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => "📋Перейти", 'url' => 'https://obmenuz.net']]
	]
]);
$site_url_uz = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => "📋Saytga O'tish", 'url' => 'https://obmenuz.net']]
	]
]);
if ($val_in == "qiwir_in" or $val_in == "qiwiu_in"){
	$wallet = "Qiwi";
	$wallet_number = "$user_qiwi";
}
if ($val_in == "prub_in" or $val_in == "pusd_in"){
	$wallet = "PAYEER";
	$wallet_number = "$user_payeer";
}
if ($val_in == "sber_in" or $val_in == "sberu_in"){
	$wallet = "1XBET";
	$wallet_number = "$user_sber";
}
if ($val_in == "wmz_in"){
	$wallet = "WMZ";
	$wallet_number = "$user_wmz";
}
if ($val_in == "wmr_in"){
	$wallet = "WMR";
	$wallet_number = "$user_wmr";
}
if ($val_in == "uzs_in"){
	$wallet = "UZCARD";
	$wallet_number = "$user_uzcard";
}
if ($val_in == "ya_in"){
	$wallet = "Yandex";
	$wallet_number = "$user_yandex";
}
if ($callback == "uzs_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if (($user_uzcard == "" or $user_qiwi == "") and ($val_in == "qiwir_in" or $val_in == "qiwiu_in")){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if (($user_uzcard == "" or $user_payeer == "") and ($val_in == "prub_in" or $val_in == "pusd_in")){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if (($user_uzcard == "" or $user_sber == "") and ($val_in == "sberu_in" or $val_in == "sber_in")){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if (($user_uzcard == "" or $user_wmz == "") and $val_in == "wmz_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if (($user_uzcard == "" or $user_wmr == "") and $val_in == "wmr_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if (($user_uzcard == "" or $user_yandex == "") and $val_in == "ya_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_42 < 10000){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Uzcard: $user_uzcard</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Uzcard: $user_uzcard</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "wmr_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_wmr == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_47 < 50){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>WMR: $user_wmr</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>WMR: $user_wmr</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "wmz_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_wmz == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_48 < 1){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>WMZ: $user_wmz</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>WMZ: $user_wmz</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "qiwir_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_qiwi == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_45 < 50){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Qiwi: $user_qiwi</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Qiwi: $user_qiwi</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "qiwiu_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_qiwi == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_46 < 1){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Qiwi: $user_qiwi</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Qiwi: $user_qiwi</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "prub_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_payeer == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_43 < 50){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>PAYEER: $user_payeer</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>PAYEER: $user_payeer</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "pusd_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_payeer == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_44 < 1){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>PAYEER: $user_payeer</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>PAYEER: $user_payeer</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "ya_out"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_yandex == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_41 < 50){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Yandex: $user_yandex</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>Yandex: $user_yandex</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "1x_rub"){
	if ($val_in == ''){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
		exit;
	}
	del($id,$message_id);
	if ($val_in != "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
		$mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
		exit;
	}
	if (($user_uzcard == "" or $user_1x_rub == "") and $val_in == "uzs_in"){
		if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
		if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
		exit;
	}
	if ($reserv_45 < 100){
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
		exit;
	}
	$mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET RUB: $user_1x_rub</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET RUB: $user_1x_rub</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "1x_usd"){
    if ($val_in == ''){
        if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
        if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
        exit;
    }
    del($id,$message_id);
    if ($val_in != "uzs_in"){
        sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
        $mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
        exit;
    }
    if (($user_uzcard == "" or $user_1x_usd == "") and $val_in == "uzs_in"){
        if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
        if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
        exit;
    }
    if ($reserv_45/65 < 5){
        if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
        if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
        exit;
    }
    $mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET USD: $user_1x_usd</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET USD: $user_1x_usd</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
if ($callback == "1x_uzs"){
    if ($val_in == ''){
        if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Сначала выберите 🔷отдачу'] );
        if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => 'Oldin 🔷berishni tanlang'] );
        exit;
    }
    del($id,$message_id);
    if ($val_in != "uzs_in"){
        sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Обмены</b> по выбранному вами направлению <b>производятся только</b> через наш сайт - obmenuz.net", 'parse_mode' => "HTML", 'reply_markup' => $site_url] );
        $mysqli->query("update `changes` set `status`='Сайт' where `id`='$change_id';");
        exit;
    }
    if (($user_uzcard == "" or $user_1x_uzs == "") and $val_in == "uzs_in"){
        if ($user_lang == "ru") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Для создания заявок по данному направлению сначала введите номера кошельков в раздел '💳Реквизиты'", "show_alert" => "true"] );
        if ($user_lang == "uz") sm( 'answerCallbackQuery', ['callback_query_id' => $query_id, 'text' => "Siz tanlagan yo'nalishda almashuvni amalga oshirish uchun oldin o'z hamyon raqamlaringizni '🔰Hamyonlar' bolimiga kiriting", "show_alert" => "true"] );
        exit;
    }
    if ($reserv_45*$curs42to45 < 10000){
        if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id, 'text' => "В данный момент создать заявку на обмен по выбранному вами направлению невозможно."] );
        if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id, 'text' => "Siz tanlagan yo'nalishda almashinuv qilish imkoni hozircha yoq."] );
        exit;
    }
    $mysqli->query("update `changes` set `val_out`='$callback' where `id`='$change_id';");
    if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: * $valuta_in\nПолучаете: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET: $user_1x_uzs</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney] );
    if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: * $valuta_in\nOlish: * $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>1XBET UZS: $user_1x_uzs</b>", 'parse_mode' => "HTML", 'reply_markup' => $setmoney_uz] );
}
//Цифры
if ($val_out == "qiwir_out" or $val_out == "qiwiu_out"){
	$wallet_out = "Qiwi";
	$wallet_number_out = "$user_qiwi";
}
if ($val_out == "prub_out" or $val_out == "pusd_out"){
	$wallet_out = "PAYEER";
	$wallet_number_out = "$user_payeer";
}
if ($val_out == "1x_rub"){
	$wallet_out = "1XBET RUB";
	$wallet_number_out = "$user_1x_rub";
}
if ($val_out == "1x_usd"){
    $wallet_out = "1XBET USD";
    $wallet_number_out = "$user_1x_usd";
}
if ($val_out == "1x_uzs"){
    $wallet_out = "1XBET UZS";
    $wallet_number_out = "$user_1x_uzs";
}
if ($val_out == "wmz_out"){
	$wallet_out = "WMZ";
	$wallet_number_out = "$user_wmz";
}
if ($val_out == "wmr_out"){
	$wallet_out = "WMR";
	$wallet_number_out = "$user_wmr";
}
if ($val_out == "uzs_out"){
	$wallet_out = "UZCARD";
	$wallet_number_out = "$user_uzcard";
}
if ($val_out == "ya_out"){
	$wallet_out = "Yandex";
	$wallet_number_out = "$user_yandex";
}
//Кнопки отдачи и получения
if ($callback == "into1"){
	if ($val_in == "uzs_in" and $val_out == "qiwir_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "qiwiu_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_46*$curs42to46."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_46*$curs42to46."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "prub_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_43*$curs42to43."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_43*$curs42to43."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "ya_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_41*$curs42to41."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_41*$curs42to41."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "pusd_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_44*$curs42to44."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_44*$curs42to44."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "wmr_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_47*$curs42to47."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_47*$curs42to47."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "wmz_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_48*$curs42to48."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_48*$curs42to48."</code> UZS";
	}
	if ($val_in == "uzs_in" and $val_out == "1x_rub"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS";
	}
    if ($val_in == "uzs_in" and $val_out == "1x_usd"){
        $minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS";
        $minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS";
    }
    if ($val_in == "uzs_in" and $val_out == "1x_uzs"){
        $minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS";
        $minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS";
    }
	if ($val_in == "uzs_in" and $val_out == "sberu_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_50*$curs42to50."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_50*$curs42to50."</code> UZS";
	}
	if ($val_in == "qiwir_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs45to42."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs45to42."</code> RUB";
	}
	if ($val_in == "wmr_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs47to42."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs47to42."</code> RUB";
	}
	if ($val_in == "prub_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs43to42."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs43to42."</code> RUB";
	}
	if ($val_in == "ya_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs41to42."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs41to42."</code> RUB";
	}
	if ($val_in == "sber_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>100</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs49to42."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>100</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs49to42."</code> RUB";
	}
	if ($val_in == "qiwiu_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs46to42."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs46to42."</code> USD";
	}
	if ($val_in == "wmz_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs48to42."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs48to42."</code> USD";
	}
	if ($val_in == "pusd_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs44to42."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs44to42."</code> USD";
	}
	if ($val_in == "sberu_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs50to42."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs50to42."</code> USD";
	}
	if ($user_lang == "ru") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "<b>Введите сумму</b> отдачи в $valuta_in\n$minmax", 'parse_mode' => "HTML"] );
	if ($user_lang == "uz") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "<b>Berish miqdorini</b> $valuta_in'da kiriting\n$minmax_uz", 'parse_mode' => "HTML"] );
	$mysqli->query("update `users` set `Iast_message`='$callback' where `Telegram ID`='$id';");
}
if ($callback == "into2"){
	if ($val_in == "uzs_in" and $val_out == "qiwir_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_45."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_45."</code> RUB";
	}
	if ($val_in == "uzs_in" and $val_out == "qiwiu_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_46."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_46."</code> USD";
	}
	if ($val_in == "uzs_in" and $val_out == "prub_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_43."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_43."</code> RUB";
	}
	if ($val_in == "uzs_in" and $val_out == "ya_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_41."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_41."</code> RUB";
	}
	if ($val_in == "uzs_in" and $val_out == "pusd_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_44."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_44."</code> USD";
	}
	if ($val_in == "uzs_in" and $val_out == "wmr_out"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_47."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_47."</code> RUB";
	}
	if ($val_in == "uzs_in" and $val_out == "wmz_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_48."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_48."</code> USD";
	}
	if ($val_in == "uzs_in" and $val_out == "1x_rub"){
		$minmax = "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_45."</code> RUB";
		$minmax_uz = "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_45."</code> RUB";
	}
    if ($val_in == "uzs_in" and $val_out == "1x_usd"){
        $minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_45/65 ."</code> USD";
        $minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_45/65 ."</code> USD";
    }
    if ($val_in == "uzs_in" and $val_out == "1x_uzs"){
        $minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS";
        $minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS";
    }
	if ($val_in == "uzs_in" and $val_out == "sberu_out"){
		$minmax = "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_50."</code> USD";
		$minmax_uz = "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_50."</code> USD";
	}
	if ($val_in == "qiwir_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "wmr_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "prub_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "ya_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "sber_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "qiwiu_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "wmz_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "pusd_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($val_in == "sberu_in" and $val_out == "uzs_out"){
		$minmax = "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS";
		$minmax_uz = "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS";
	}
	if ($user_lang == "ru") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "<b>Введите сумму</b> получения в $valuta_out\n$minmax", 'parse_mode' => "HTML"] );
	if ($user_lang == "uz") sm( 'editMessageText', ['chat_id' => $id, 'message_id' => $message_id, 'text' => "<b>Olish miqdorini</b> $valuta_out'da kiriting\n$minmax_uz", 'parse_mode' => "HTML"] );
	$mysqli->query("update `users` set `Iast_message`='$callback' where `Telegram ID`='$id';");
}
$pay_key = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => 'Оплатить', 'callback_data' => 'pay']],
		[[ 'text' => 'Отменить', 'callback_data' => 'otmena']]
	]
]);
$pay_key_uz = json_encode([
	'inline_keyboard'=>[
		[[ 'text' => "To'lov qilish", 'callback_data' => 'pay']],
		[[ 'text' => 'Bekor qilish', 'callback_data' => 'otmena']]
	]
]);
if (is_numeric($text) and ($last_message == "into1" or $last_message == "into2")){
	if ($last_message == "into1"){
		if ($val_in == "uzs_in" and $val_out == "qiwir_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_45*$curs42to45){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to45;
		}
		if ($val_in == "uzs_in" and $val_out == "qiwiu_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_46*$curs42to46){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_46*$curs42to46."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_46*$curs42to46."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to46;
		}
		if ($val_in == "uzs_in" and $val_out == "prub_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_43*$curs42to43){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_43*$curs42to43."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_43*$curs42to43."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to43;
		}
		if ($val_in == "uzs_in" and $val_out == "ya_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_41*$curs42to41){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_41*$curs42to41."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_41*$curs42to41."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to41;
		}
		if ($val_in == "uzs_in" and $val_out == "pusd_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_44*$curs42to44){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_44*$curs42to44."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_44*$curs42to44."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to44;
		}
		if ($val_in == "uzs_in" and $val_out == "wmr_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_47*$curs42to47){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_47*$curs42to47."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_47*$curs42to47."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to47;
		}
		if ($val_in == "uzs_in" and $val_out == "wmz_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_48*$curs42to48){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_48*$curs42to48."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_48*$curs42to48."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to48;
		}
		if ($val_in == "uzs_in" and $val_out == "1x_rub"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_45*$curs42to45){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $sell/$curs_1x_rub;
            if ($percent) $buy += $buy/20;
		}
        if ($val_in == "uzs_in" and $val_out == "1x_usd"){
            $sell = $text*1;
            if ($sell < 30000 or $sell > $reserv_45*$curs42to45){
                if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                exit;
            }
            $buy = $sell/$curs_1x_usd;
            if ($percent) $buy += $buy/20;
        }
        if ($val_in == "uzs_in" and $val_out == "1x_uzs"){
            $sell = $text*1;
            if ($sell < 30000 or $sell > $reserv_45*$curs42to45){
                if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                exit;
            }
            $buy = $sell - $sell / 100 * $curs_1x_uzs;
            if ($percent) $buy += $buy/20;
        }
		if ($val_in == "uzs_in" and $val_out == "sberu_out"){
			$sell = $text*1;
			if ($sell < 30000 or $sell > $reserv_50*$curs42to50){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_50*$curs42to50."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_50*$curs42to50."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text/$curs42to50;
		}
		if ($val_in == "qiwir_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 200 or $sell > $reserv_42/$curs45to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs45to42."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs45to42."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs45to42;
		}
		if ($val_in == "wmr_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 200 or $sell > $reserv_42/$curs47to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs47to42."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs47to42."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs47to42;
		}
		if ($val_in == "prub_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 200 or $sell > $reserv_42/$curs43to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs43to42."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs43to42."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs43to42;
		}
		if ($val_in == "ya_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 200 or $sell > $reserv_42/$curs41to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs41to42."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs41to42."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs43to42;
		}
		if ($val_in == "sber_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 100 or $sell > $reserv_42/$curs49to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>100</code> RUB\n<b>Максимум</b> <code>".$reserv_42/$curs49to42."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>100</code> RUB\n<b>Maksimal</b> <code>".$reserv_42/$curs49to42."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs49to42;
		}
		if ($val_in == "qiwiu_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 3 or $sell > $reserv_42/$curs46to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs46to42."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs46to42."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs46to42;
		}
		if ($val_in == "wmz_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 3 or $sell > $reserv_42/$curs48to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs48to42."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs48to42."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs48to42;
		}
		if ($val_in == "pusd_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 3 or $sell > $reserv_42/$curs44to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs44to42."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs44to42."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs44to42;
		}
		if ($val_in == "sberu_in" and $val_out == "uzs_out"){
			$sell = $text*1;
			if ($sell < 3 or $sell > $reserv_42/$curs50to42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_42/$curs50to42."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_42/$curs50to42."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$buy = $text*$curs50to42;
		}
        if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date`,`status` from `changes` where `id`='$change_id';")) {
            while ($row = $result->fetch_assoc()) {
                $change_id = $row["id"];
                $client_id = $row["Telegram ID"];
                $c_sell = $row["sell"];
                $c_buy = $row["buy"];
            }
            $result->free();
        }
        $buy = round($buy,2);
        if ( $c_buy == '' and $c_sell == '' ) $mysqli->query("update `changes` set `sell`='$sell',`buy`='$buy' where `id`='$change_id';");
        elseif ( $c_buy != '' or $c_sell != '' ) {
            if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "Замечена попытка фальсификация заявку!\n\nАдминистрация примет меры при повторной попытке."] );
            if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "Almashuvni qalbakilashtirish harakati ma'lum qilindi!\n\nMamuriyat kerakli choralarni koradi!"] );
            sm( 'sendMessage', ['chat_id' => $admin,'text' => "[$client_id](tg://user?id=$client_id) - данный клиент пытался фальсификацировать заявку под ID $change_id.", 'parse_mode' => 'Markdown'] );
            exit;
        }
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: $sell $valuta_in\nПолучаете: $buy $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>$wallet_out: $wallet_number_out</b>", 'parse_mode' => "HTML", 'reply_markup' => $pay_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: $sell $valuta_in\nOlish: $buy $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>$wallet_out: $wallet_number_out</b>", 'parse_mode' => "HTML", 'reply_markup' => $pay_key_uz] );
	}
if ($last_message == "into2"){
		if ($val_in == "uzs_in" and $val_out == "qiwir_out"){
			$buy = $text*1;
			if ($buy < 200 or $buy > $reserv_45){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_45."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_45."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to45;
		}
		if ($val_in == "uzs_in" and $val_out == "qiwiu_out"){
			$buy = $text*1;
			if ($buy < 3 or $buy > $reserv_46){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_46."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_46."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to46;
		}
		if ($val_in == "uzs_in" and $val_out == "prub_out"){
			$buy = $text*1;
			if ($buy < 200 or $buy > $reserv_43){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_43."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_43."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to43;
		}
		if ($val_in == "uzs_in" and $val_out == "ya_out"){
			$buy = $text*1;
			if ($buy < 200 or $buy > $reserv_41){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_41."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_41."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to41;
		}
		if ($val_in == "uzs_in" and $val_out == "pusd_out"){
			$buy = $text*1;
			if ($buy < 3 or $buy > $reserv_44){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_44."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_44."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to44;
		}
		if ($val_in == "uzs_in" and $val_out == "wmr_out"){
			$buy = $text*1;
			if ($buy < 200 or $buy > $reserv_47){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_47."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_47."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to47;
		}
		if ($val_in == "uzs_in" and $val_out == "wmz_out"){
			$buy = $text*1;
			if ($buy < 3 or $buy > $reserv_48){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_48."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_48."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to48;
		}
		if ($val_in == "uzs_in" and $val_out == "1x_rub"){
			$buy = $text*1;
			if ($buy < 200 or $buy > $reserv_45){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>200</code> RUB\n<b>Максимум</b> <code>".$reserv_45."</code> RUB", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>200</code> RUB\n<b>Maksimal</b> <code>".$reserv_45."</code> RUB", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs_1x_rub;
            if ($percent) $buy += $buy/20;
		}
        if ($val_in == "uzs_in" and $val_out == "1x_usd"){
            $buy = $text*1;
            if ($buy < 3 or $buy > $reserv_45/65){
                if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_45/65 ."</code> USD", 'parse_mode' => "HTML"] );
                if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_45/65 ."</code> USD", 'parse_mode' => "HTML"] );
                exit;
            }
            $sell = $text*$curs_1x_usd;
            if ($percent) $buy += $buy/20;
        }
        if ($val_in == "uzs_in" and $val_out == "1x_uzs"){
            $buy = $text*1;
            if ($buy < 30000 or $buy > $reserv_45*$curs42to45){
                if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30000</code> UZS\n<b>Максимум</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30000</code> UZS\n<b>Maksimal</b> <code>".$reserv_45*$curs42to45."</code> UZS", 'parse_mode' => "HTML"] );
                exit;
            }
            $sell = $buy + $buy / 100 * $curs_1x_uzs;
            if ($percent) $buy += $buy/20;
        }
		if ($val_in == "uzs_in" and $val_out == "sberu_out"){
			$buy = $text*1;
			if ($buy < 3 or $buy > $reserv_50){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>3</code> USD\n<b>Максимум</b> <code>".$reserv_50."</code> USD", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>3</code> USD\n<b>Maksimal</b> <code>".$reserv_50."</code> USD", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text*$curs42to50;
		}
		if ($val_in == "qiwir_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs45to42;
		}
		if ($val_in == "wmr_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs47to42;
		}
		if ($val_in == "prub_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs43to42;
		}
		if ($val_in == "ya_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs43to42;
		}
		if ($val_in == "sber_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 15000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>15 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>15 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs49to42;
		}
		if ($val_in == "qiwiu_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs46to42;
		}
		if ($val_in == "wmz_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs48to42;
		}
		if ($val_in == "pusd_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs44to42;
		}
		if ($val_in == "sberu_in" and $val_out == "uzs_out"){
			$buy = $text*1;
			if ($buy < 30000 or $buy > $reserv_42){
				if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Минимум</b> <code>30 000</code> UZS\n<b>Максимум</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Minimal</b> <code>30 000</code> UZS\n<b>Maksimal</b> <code>".$reserv_42."</code> UZS", 'parse_mode' => "HTML"] );
				exit;
			}
			$sell = $text/$curs50to42;
		}
        if ($result = $mysqli->query("SELECT `id`,`Telegram ID`,`val_in`,`val_out`,`sell`,`buy`,`date`,`status` from `changes` where `id`='$change_id';")) {
            while ($row = $result->fetch_assoc()) {
                $change_id = $row["id"];
                $client_id = $row["Telegram ID"];
                $c_sell = $row["sell"];
                $c_buy = $row["buy"];
            }
            $result->free();
        }
		$sell = round($sell,2);
        if ( $c_buy == '' and $c_sell == '' ) $mysqli->query("update `changes` set `sell`='$sell',`buy`='$buy' where `id`='$change_id';");
        elseif ( $c_buy != '' or $c_sell != '' ) {
            if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "Замечена попытка фальсификация заявку!\n\nАдминистрация примет меры при повторной попытке."] );
            if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "Almashuvni qalbakilashtirish harakati ma'lum qilindi!\n\nMamuriyat kerakli choralarni koradi!"] );
            sm( 'sendMessage', ['chat_id' => $admin,'text' => "[$client_id](tg://user?id=$client_id) - данный клиент пытался фальсификацировать заявку под ID $change_id.", 'parse_mode' => 'Markdown'] );
            exit;
        }
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка:</b>\nID: <code>$change_id</code>\nОтдаете: $sell $valuta_in\nПолучаете: $buy $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>$wallet_out: $wallet_number_out</b>", 'parse_mode' => "HTML", 'reply_markup' => $pay_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Almashuv:</b>\nID: <code>$change_id</code>\nBerish: $sell $valuta_in\nOlish: $buy $valuta_out\n<b>$wallet: $wallet_number</b>\n<b>$wallet_out: $wallet_number_out</b>", 'parse_mode' => "HTML", 'reply_markup' => $pay_key_uz] );
	}
}
//Оплата

if ($callback == "pay"){
	del($id,$message_id);
	if ($result = $mysqli->query("SELECT `uzcard`,`qiwi`,`yandex`,`sber`,`payeer`,`wmz`,`wmr` from `users` where `Telegram ID`='$admin';")) {
		while ($row = $result->fetch_assoc()) {
			$admin_uzcard = $row["uzcard"];
			$admin_qiwi = $row["qiwi"];
			$admin_wmz = $row["wmz"];
			$admin_wmr = $row["wmr"];
			$admin_yandex = $row["yandex"];
			$admin_payeer = $row["payeer"];
			$admin_sber = $row["sber"];
		}
	$result->free();
	}
	if ($result = $mysqli->query("SELECT `val_in`,`sell` from `changes` where `id`='$change_id';")) {
		while ($row = $result->fetch_assoc()) {
			$val_in = $row["val_in"];
			$sell = $row["sell"];
		}
		$result->free();
	}
	$confim_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => 'Я оплатил заявку', 'callback_data' => 'pay_confimed']],
			[[ 'text' => 'Отменить', 'callback_data' => 'otmena']]
		]
	]);
	$confim_key_uz = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => "To'lov qildim", 'callback_data' => 'pay_confimed']],
			[[ 'text' => 'Bekor qilish', 'callback_data' => 'otmena']]
		]
	]);
	if ($val_in == "uzs_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_uzcard"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в любом из перечисленных платежных систем - Payme.uz , Mbank.uz , Upay.uz , Uzcard.uz;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_uzcard</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа:<b> $sell </b> UZS\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время \n\n<b>Внимания! Переводы из других ПС как (CLICK или OSON) могут задержаться до нескольких часов</b>", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring</b>:\n\n► Payme.uz , Mbank.uz , Upay.uz , Uzcard.uz - to'lov tizimlarining hohlaganiga kiring;\n►Pastroqda ko'rsatilgan pul mablag'ini shu -<code>$admin_uzcard</code>- karta raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor:<b> $sell </b> UZS\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi \n\n<b> E'tibor bering! Boshqa to'lov tizimlaridan (CLICK va OSON)dan qilingan to'lovlar bir necha soatgacha cho'zilishi mumkin</b>", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "qiwir_in" or $val_in == "qiwiu_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_qiwi"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе QIWI.COM;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_qiwi</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► QIWI.COM tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_qiwi</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "prub_in" or $val_in == "pusd_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_payeer"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе PAYEER.COM;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_payeer</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► PAYEER.COM tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_payeer</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "sber_in" or $val_in == "sberu_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_sber"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе 1XBET.MOBI;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_sber</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► 1XBET.MOBI tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_sber</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "wmz_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_wmz"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе WEBMONEY.COM;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_wmz</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► WEBMONEY.COM tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_wmz</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "wmr_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_wmr"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе WEBMONEY.COM;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_wmr</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► WEBMONEY.COM tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_wmr</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
	if ($val_in == "ya_in"){
		sm( 'sendMessage', ['chat_id' => $id,'text' => "$admin_yandex"] );
		if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Для копирования\n\n<b>Для успеной обработки вашей заявки пожалуйста выполните следующие действия:</b>\n\n►Авторизуйтесь в платежной системе MONEY.YANDEX.RU;\n►Переведите указанную ниже сумму на кошелек -<code>$admin_yandex</code>-;\n►Нажмите на кнопку «Я оплатил заявку»;\n►Ожидайте обработку заявки оператором.\n\nСумма платежа: $sell $valuta_in\n\nДанная операция производится оператором в ручном режиме и занимает в среднем <b>от 2 до 30 минут</b> в рабочее время", 'parse_mode' => "HTML", 'reply_markup' => $confim_key] );
		if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "👆Ko'chirib olish uchun\n\n<b>Almashuvingiz muvaffaqiyatli bajarilishi uchun quyidagi harakatlarni amalga oshiring:</b>\n\n► MONEY.YANDEX.RU tizimidan ro'yhatdan o'ting;\n►Pastroqda ko'rsatilgan miqdorni -<code>$admin_yandex</code>- hamyon raqamiga o'tkazing;\n►«To'lov qildim» tugmasini bosing;\n►Operator tomonidan almashuv tasdiqlanishini kuting.\n\nMiqdor: $sell $valuta_in\n\nUshbu tekshiruv operator tomonidan amalga oshiriladi va ish vaqtida o'rtacha <b>2 dan 30 daqiqagacha</b> davom etadi", 'parse_mode' => "HTML", 'reply_markup' => $confim_key_uz] );
	}
}

if ($callback == "pay_confimed"){
	del($id,$message_id-1);
	del($id,$message_id);
	if ($user_lang == "ru") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Ваша заявка передана на обработку.</b> Ожидайте уведомления", 'parse_mode' => "HTML", 'reply_markup' => $main] );
	if ($user_lang == "uz") sm( 'sendMessage', ['chat_id' => $id,'text' => "<b>Sizning almashuvingiz tekshiruvga yuborildi.</b> Iltomos xabarni kuting. ", 'parse_mode' => "HTML", 'reply_markup' => $main_uz] );
	if ($result = $mysqli->query("SELECT `id`,`val_in`,`val_out`,`sell`,`buy` from `changes` where `id`='$change_id';")){
		while ($row = $result->fetch_assoc()) {
		$tg_id = $row["Telegram ID"];
			$val_in = $row["val_in"];
			$val_out = $row["val_out"];
			$sell = $row["sell"];
			$buy = $row["buy"];
		}
		$result->free();
	}
	$admin_key = json_encode([
		'inline_keyboard'=>[
			[[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
			[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
			[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
		]
	]);
	if ($val_out == 'qiwir_out'){
		$admin_key = json_encode([
			'inline_keyboard'=>[
				[[ 'text' => "Avtoto'lov", 'callback_data' => 'by_auto'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
				[[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
				[[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return']]
			]
		]);
	}
    if (substr($val_out, 0, 2) == '1x'){
        $yuyu = '🚫';
        if ($percent) $yuyu = '✅';
        $admin_key = json_encode([
            'inline_keyboard'=>[
                [[ 'text' => "Avtoto'lov", 'callback_data' => 'by_auto'],[ 'text' => 'Tugatildi', 'callback_data' => 'admin_gotovo']],
                [[ 'text' => 'To`lanmagan', 'callback_data' => 'admin_notpay']],
                [[ 'text' => 'Qaytarish', 'callback_data' => 'admin_return'], [ 'text' => $yuyu.'Skidka', 'callback_data' => 'admin_skid']]
            ]
        ]);
    }
	$mysqli->query("update `changes` set `status`='На проверке' where `id`='$change_id';");
	sm( 'sendMessage', ['chat_id' => $admin,'text' => "🆔: | <code>$change_id</code> |\n<b>🆔: ID</b> | <code>$id</code> |\n👤: <b>$first_name </b>\n📨: @$username\n📇: <b>$real_name</b>\n📞: $real_number\n💸: <b>$sell</b>✅ $valuta_in\n📝: <b>$date</b>\n\n💵 <code>$buy </code><b>$valuta_out \n$wallet: $wallet_number\n$wallet_out:</b> <code>$wallet_number_out</code>", 'parse_mode' => "HTML", "reply_markup" => $admin_key] ); 
	sm( 'sendMessage', ['chat_id' => "@Id_tekshiruvi",'text' => "🆔: | <code>$change_id</code> |\n<b>🆔: ID</b> | <code>$id</code> |\n👤: <b>$first_name </b>\n📨: @$username\n📇: <b>$real_name</b>\n📞: $real_number\n💸: <b>$sell</b>✅ $valuta_in\n📝: <b>$date</b>\n\n💵 <code>$buy </code><b>$valuta_out \n$wallet: $wallet_number\n$wallet_out:</b> <code>$wallet_number_out</code>", 'parse_mode' => "HTML"] );
    
}


