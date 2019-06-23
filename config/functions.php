<?php
require_once __DIR__ . '/../vendor/autoload.php';

function setLanguage() {
    if (isSet($_GET["lang"])) {
        $language = $_GET["lang"];
        $_SESSION["lang"] = $language;
        setcookie("lang", $language, time() + (3600 * 24 * 30));
    } else if (isSet($_SESSION["lang"])) {
        $language = $_SESSION["lang"];
    } else if (isSet($_COOKIE["lang"])) {
        $language = $_COOKIE["lang"];
    } else {
        $language = "en";
    }

    switch ($language) {
        case "vi":
            $lang_file = "lang.vi.php";
            break;
        default :
            $lang_file = "lang.php";
            break;
    }
    return $lang_file;
}

/*
 * To control all login actions
 * @link: http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
 */

function sec_session_start($needRegenerateId) {
    $session_name = "sec_session_id";
    session_name($session_name);
//    If TRUE cookie will only be sent over secure connections. 
//    So if not HTTPS, you can not get cookies' value
    $secure = false;
    $http_only = true;
    if (ini_set("session.use_only_cookies", 1) === FALSE) {
        exit();
    }
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $http_only);
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if ($needRegenerateId) {
        session_regenerate_id(true);
    }
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_username() {
    return $_SESSION["username"];
}

function get_userid() {
    return $_SESSION["user_id"];
}

function esc_url($url) {
    if ($url == "") {
        return $url;
    }
    $url = preg_replace("|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i", "", $url);
    $strip = array("%0d", "%0a", "%0D", "%0A");
    $url = (string) $url;
    $count = 1;
    while ($count) {
        $url = str_replace($strip, "", $url, $count);
    }
    $url = str_replace(";//", "://", $url);
    $url = htmlentities($url);
    $url = str_replace("&amp;", "&#038;", $url);
    $url = str_replace("'", "&#039;", $url);
    if ($url[0] !== "/") {
        return "";
    } else {
        return $url;
    }
}

function errorLogger($message, $level) {
    Logger::configure(__DIR__ . '/../Include/log4Error.php');
    $logger = Logger::getLogger("error");
    if (strcmp($level, "info") == 0) {
        $logger->info($message);
    } else if (strcmp($level, "error") == 0) {
        $logger->error($message);
    } else if (strcmp($level, "debug") == 0) {
        $logger->debug($message);
    } else {
        $logger->fatal($message);
    }
}

function webLogger($message, $level) {
    Logger::configure(__DIR__ . '/../Include/log4Web.php');
    $logger = Logger::getLogger("web");
    if (strcmp($level, "info") == 0) {
        $logger->info($message);
    } else if (strcmp($level, "error") == 0) {
        $logger->error($message);
    } else if (strcmp($level, "debug") == 0) {
        $logger->debug($message);
    } else {
        $logger->fatal($message);
    }
}

/**
 * http://stackoverflow.com/questions/3706855/send-email-with-a-template-using-php
 * @param type $path
 * @param type $vals
 * @return type
 */
function createEmailFromTemplate($path, $variables) {
    $template = file_get_contents($path);
    foreach ($variables as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }
    return $template;
}

function sendEmail($content, $receivers) {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = "128.199.227.1";
    $mail->SMTPAuth = true;
//    $mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->isHTML(true);
    $mail->SMTPKeepAlive = true; // prevent the SMTP session from being closed after each message
    $email_log = "";
    //https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting
    //disable certifcate check
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Username = EMAIL_USER;
    $mail->Password = EMAIL_PASSWORD;
    $mail->setFrom(EMAIL);
    $mail->addCustomHeader("Precedence: bulk");
    $mail->addCustomHeader("List-Unsubscribe", "<unsubscribe@edumall.co.th>");
    $mail->addAddress($receivers);
    $mail->Subject = "Your activate account link";
    $mail->Body = $content;
    if (!$mail->send()) {
//        emailLogger("Message has been sent:".EMAIL." --> ".$receivers.$mail->ErrorInfo, "error");
    } else {
//                $email_log = 'Message has been sent';
//        emailLogger("Message has been sent:".EMAIL." --> ".$receivers, "info");
    }
    $mail->SmtpClose();
}

/**
 * Some urls are returned from Minerva system wrong
 */
function fixUrl($url) {
    $last_character = substr($url, -1);
    if (strcasecmp($last_character, "=") == 0) {
        return substr($url, 0, strlen($url) - 1);
    }
    return $url;
}

/**
 * Check if user logged in
 * @return boolean
 */
function checkUserLogin($needRegenerateId = false) {
    sec_session_start($needRegenerateId);
    $db = new Database();
    $conn = $db->getConnection();
    $isLogin = login_check($conn);
    $db->dbClose();
    //http://konrness.com/php5/how-to-prevent-blocking-php-requests/
    //with this can we call multi request at a time?
    session_write_close();
    return $isLogin;
}

/**
 * Get current date
 * @return type
 */
function getCurrentDate() {
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $now = new \DateTime();
    return $now->format("Y-m-d H:i:s");
}
