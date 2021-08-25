<?php

use App\Counter;
use App\GeneralSetting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function systemDetails()
{
    $system['name'] = 'TransLab';
    $system['version'] = '1.0';
    return $system;
}

function getLatestVersion()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/version/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}


function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}

function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}


function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }
    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $image = Image::make($file);
    if (!empty($size)) {
        $size = explode('x', strtolower($size));
        $image->resize($size[0], $size[1]);
    }
    $image->save($location . '/' . $filename);

    if (!empty($thumb)) {

        $thumb = explode('x', $thumb);
        Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
    }

    return $filename;
}

function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}


function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}

function activeTemplate($asset = false)
{

    $gs = GeneralSetting::first(['active_template']);

    $template = $gs->active_template;

    $sess = session()->get('template');
    if (trim($sess) != null) {
        $template = $sess;
    }
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}


function activeTemplateName()
{
    $gs = GeneralSetting::first(['active_template']);
    $template = $gs->active_template;
    $sess = session()->get('template');
    if (trim($sess) != null) {
        $template = $sess;
    }
    return $template;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function currency(){
    $data['crypto'] = 8;
    $data['fiat'] = 2;
    return $data;
}


function getAmount($amount, $length = 0)
{
    if(0 < $length){
        return number_format( $amount + 0, $length);
    }
    return $amount + 0;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet, $amount, $crypto = null)
{

    $varb = $wallet . "?amount=" . $amount;
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
}

function curlContent($url)
{
    //open connection
    $ch = curl_init();
    //set the url, number of POST vars, POST data

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    return $result;
}


function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function str_slug($title = null)
{
    return \Illuminate\Support\Str::slug($title);
}

function str_limit($title = null, $length = 10)
{
    return \Illuminate\Support\Str::limit($title, $length);
}


function getIpInfo()
{
    $ip = Null;
    $deep_detect = TRUE;

    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }


    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

    $country    = @$xml->geoplugin_countryName;
    $city       = @$xml->geoplugin_city;
    $area       = @$xml->geoplugin_areaCode;
    $code       = @$xml->geoplugin_countryCode;
    $long       = @$xml->geoplugin_longitude;
    $lat        = @$xml->geoplugin_latitude;


    $user_owner = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_owner)) {
            $os_platform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_owner)) {
            $browser = $value;
        }
    }

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['os_platform'] = $os_platform;
    $data['browser'] = $browser;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');

    return $data;
}


function site_name()
{
    $general = GeneralSetting::first();
    $sitname = str_word_count($general->sitename);
    $sitnameArr = explode(' ', $general->sitename);
    if ($sitname > 1) {
        $title = "<span>$sitnameArr[0] </span> " . str_replace($sitnameArr[0], '', $general->sitename);
    } else {
        $title = "<span>$general->sitename</span>";
    }

    return $title;
}



function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}

function getPageSections($arr = false)
{

    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        asort($sections);
    }
    return $sections;
}


function getImage($image, $avatar=false ,$clean = '')
{
    if($avatar){
        return file_exists($image) && is_file($image) ? asset($image) . $clean : asset(imagePath()['image']['avatar']);
    }
    else{
        return file_exists($image) && is_file($image) ? asset($image) . $clean : asset(imagePath()['image']['default']);
    }
}


function notify($user, $type, $shortCodes = null)
{

    send_email($user, $type, $shortCodes);
    send_sms($user, $type, $shortCodes);
}

function send_sms($user, $type, $shortCodes = [])
{
    $general = GeneralSetting::first(['sn', 'sms_api']);
    $sms_template = \App\SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    if ($general->sn == 1 && $sms_template) {

        $template = $sms_template->sms_body;

        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{' . $code . '}}', $value, $template);
        }
        $template = urlencode($template);

        $message = shortCodeReplacer("{{number}}", $user->mobile, $general->sms_api);
        $message = shortCodeReplacer("{{message}}", $template, $message);
        $result = @file_get_contents($message);
    }
}

function send_email($user, $type = null, $shortCodes = [])
{
    $general = GeneralSetting::first();

    $email_template = \App\EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if ($general->en != 1 || !$email_template) {
        return;
    }


    $message = shortCodeReplacer("{{name}}", $user->username, $general->email_template);
    $message = shortCodeReplacer("{{message}}", $email_template->email_body, $message);

    if (empty($message)) {
        $message = $email_template->email_body;
    }

    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{' . $code . '}}', $value, $message);
    }
    $config = $general->mail_config;

    if ($config->name == 'php') {
        send_php_mail($user->email, $user->username, $general->email_from, $email_template->subj, $message);
    } else if ($config->name == 'smtp') {
        send_smtp_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    } else if ($config->name == 'sendgrid') {
        send_sendGrid_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    } else if ($config->name == 'mailjet') {
        send_mailjet_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    }
}


function send_php_mail($receiver_email, $receiver_name, $sender_email, $subject, $message)
{
    $headers = "From: <$sender_email> \r\n";
    $headers .= "Reply-To: $receiver_name <$receiver_email> \r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    @mail($receiver_email, $subject, $message, $headers);
}


function send_smtp_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $config->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $config->username;
        $mail->Password   = $config->password;
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }else{
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = $config->port;
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom($sender_email, $sender_name);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo($sender_email, $receiver_name);
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
    } catch (Exception $e) {
       throw new Exception($e);
    }
}

function send_sendGrid_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $sendgridMail = new \SendGrid\Mail\Mail();
    $sendgridMail->setFrom($sender_email, $sender_name);
    $sendgridMail->setSubject($subject);
    $sendgridMail->addTo($receiver_email, $receiver_name);
    $sendgridMail->addContent("text/html", $message);
    $sendgrid = new \SendGrid($config->appkey);
    try {
        $response = $sendgrid->send($sendgridMail);
    } catch (Exception $e) {
        // echo 'Caught exception: '. $e->getMessage() ."\n";
    }
}


function send_mailjet_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $mj = new \Mailjet\Client($config->public_key, $config->secret_key, true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $sender_email,
                    'Name' => $sender_name,
                ],
                'To' => [
                    [
                        'Email' => $receiver_email,
                        'Name' => $receiver_name,
                    ]
                ],
                'Subject' => $subject,
                'TextPart' => "",
                'HTMLPart' => $message,
            ]
        ]
    ];
    $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
}


function getPaginate($paginate = 20)
{
    return $paginate;
}


function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function imagePath()
{
    $data['gateway'] = [
        'path' => 'assets/images/gateway',
        'size' => '800x800',
    ];
    $data['deposit'] = [
        'path' => 'assets/images/verify_deposit',
    ];
    $data['image'] = [
        'default' => 'assets/images/default.png',
        'avatar' => 'assets/images/avatar.png',
    ];

    $data['ticket'] = [
        'path' => 'assets/images/support',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['logoIcon'] = [
        'path' => 'assets/images/logoIcon',
    ];
    $data['ownerLogo'] = [
        'path' => 'assets/owner/images/logo',
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['seo'] = [
        'path' => 'assets/images/seo',
        'size' => '600x315'
    ];
    return $data;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'd M, Y h:i A')
{
    $lang = session()->get('lang');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse($date)->translatedFormat($format);
}


function send_general_email($email, $subject, $message, $receiver_name = '')
{

    $general = GeneralSetting::first();
    if ($general->en != 1 || !$general->email_from) {
        return;
    }
    $message = shortCodeReplacer("{{message}}", $message, $general->email_template);
    $message = shortCodeReplacer("{{name}}", $receiver_name, $message);

    $config = $general->mail_config;

    if ($config->name == 'php') {
        send_php_mail($email, $receiver_name, $general->email_from, $subject, $message);
    } else if ($config->name == 'smtp') {
        send_smtp_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'sendgrid') {
        send_sendgrid_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'mailjet') {
        send_mailjet_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    }
}
if (!function_exists('putPermanentEnv')) {
    function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}

function getContent($data_keys, $singleQuery = false, $limit = null)
{
    if ($singleQuery) {
        $content = \App\Frontend::where('data_keys', $data_keys)->latest()->first();
    } else {
        $article = \App\Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        $content = $article->where('data_keys', $data_keys)->latest()->get();
    }
    return $content;
}


function getPackageLimitUnit($type) {
    if($type == 1)
        return 'Days';
    elseif($type == 2)
        return 'Months';
    elseif($type == 3)
        return 'Years';
}


function getPackageExpireDate($time_limit, $unit, $start = null){

    if($start == null){
        $start = Carbon\Carbon::now();
    }

    if($unit == 1)
        $result = $start->addDay($time_limit);
    elseif($unit == 2)
        $result = $start->addMonth($time_limit);

    elseif($unit == 3)
        $result = $start->addYear($time_limit);

    return $result;
}

function showGender($val)
{
    switch ($val) {

        case $val==0:
            $result = 'Others';
            break;
        case $val==1:
            $result = 'Male';
            break;
        case $val==2:
            $result = 'Female';
            break;
        default:
            $result = '';
            break;
    }
    return $result;
}
function showDayOff($val)
{
    switch ($val) {
        case $val==6:
            $result = 'Saturday';
            break;
        case $val==0:
            $result = 'Sunday';
            break;
        case $val==1:
            $result = 'Monday';
            break;
        case $val==2:
            $result = 'Tuesday';
            break;
        case $val==3:
            $result = 'Wednesday';
            break;
        case $val==4:
            $result = 'Thursday';
            break;
        case $val==5:
            $result = 'Friday';
            break;
        default:
            $result = '';
            break;
    }
    return $result;
}

function getStoppageInfo($stoppages){

    $data = Counter::routeStoppages($stoppages);

    return $data;
}

function paginate($items, $perPage = 5, $page = null,  $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
    'path' => Paginator::resolveCurrentPath(),
    'pageName' => 'page',
    ]);
}

function seatLayoutToArray($layoutString) {
    return $seat_layout = explode('x', str_replace(' ','', $layoutString));
}

function stoppageCombination($numbers, $arraySize, $level = 1, $i = 0, $addThis = [])
{
    // If this is the last layer, use a different method to pass the number.
    if ($level == $arraySize) {
        $result = [];
        for (; $i < count($numbers); $i++) {
            $result[] = array_merge($addThis, array($numbers[$i]));
        }
        return $result;
    }

    $result = [];
    $nextLevel = $level + 1;
    for (; $i < count($numbers); $i++) {
        // Add the data given from upper level to current iterated number and pass
        // the new data to a deeper level.
        $newAdd = array_merge($addThis, array($numbers[$i]));
        $temp = stoppageCombination($numbers, $arraySize, $nextLevel, $i, $newAdd);


        $result = array_merge($result, $temp);

    }

    return $result;
}

function showContentHeading($heading)
{
    $array      = explode(' ',$heading);
    $strArray   = array_chunk($array,ceil(count($array) / 2));
    $string     = '';
    if(isset($strArray[0])){
        foreach($strArray[0] as $k=>$str){
            $string .= ' '. $str;
        }
    }
    if(isset($strArray[1])){
        $string .= ' <span>';
        foreach($strArray[1] as $k=>$str){
            $string .= ' '.$str;
        }
        $string .= '</span>';
    }
    return $string;

}


function getOwnerLogo($owner) {
    $logo   = imagePath()['ownerLogo']['path'].'/'.$owner->username.'.png';
    if(!(file_exists($logo) && is_file($logo))){
        return getImage(imagePath()['logoIcon']['path'] .'/logo.png');
    }else{
        return getImage($logo);
    }
}
