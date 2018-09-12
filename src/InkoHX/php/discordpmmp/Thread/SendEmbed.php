<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/12
 * Time: 12:50
 */

namespace InkoHX\php\discordpmmp\Thread;

use pocketmine\Thread;
use pocketmine\utils\InternetException;

class SendEmbed extends Thread
{
    private static $webhook, $title, $field, $value, $color, $username, $avatarurl;

    /**
     * SendEmbed constructor.
     * @param string $webhook
     * @param string $title
     * @param string $field
     * @param string $value
     * @param int $color
     * @param string $username
     * @param string $avatarurl
     */
    public function __construct(string $webhook, string $title, string $field, string $value, int $color = 16777215, string $username = "InkoHX", string $avatarurl = "https://avatars1.githubusercontent.com/u/33122816?s=460&v=4")
    {
        self::$webhook = $webhook;
        self::$title = $title;
        self::$field = $field;
        self::$value = $value;
        self::$color = $color;
        self::$username = $username;
        self::$avatarurl = $avatarurl;
    }

    public function run()
    {
        /**
         * @param string $webhook
         * @param string $data
         * @param callable|null $onSuccess
         * @return array
         */
        function post(string $webhook, string $data, callable $onSuccess = null)
        {
            $ch = curl_init($webhook);
            curl_setopt_array($ch, [] + [
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_FORBID_REUSE => 1,
                    CURLOPT_FRESH_CONNECT => 1,
                    CURLOPT_AUTOREFERER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT_MS => (int)(10 * 1000),
                    CURLOPT_TIMEOUT_MS => (int)(10 * 1000),
                    CURLOPT_HTTPHEADER => array_merge(["User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0 " . \pocketmine\NAME], array()),
                    CURLOPT_HEADER => true
                ]);
            try {
                $raw = curl_exec($ch);
                $error = curl_error($ch);
                if ($error !== "") {
                    throw new InternetException($error);
                }
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $rawHeaders = substr($raw, 0, $headerSize);
                $body = substr($raw, $headerSize);
                $headers = [];
                foreach (explode("\r\n\r\n", $rawHeaders) as $rawHeaderGroup) {
                    $headerGroup = [];
                    foreach (explode("\r\n", $rawHeaderGroup) as $line) {
                        $nameValue = explode(":", $line, 2);
                        if (isset($nameValue[1])) {
                            $headerGroup[trim(strtolower($nameValue[0]))] = trim($nameValue[1]);
                        }
                    }
                    $headers[] = $headerGroup;
                }
                if ($onSuccess !== null) {
                    $onSuccess($ch);
                }
                return [$body, $headers, $httpCode];
            } finally {
                curl_close($ch);
            }
        }

        post(self::$webhook, json_encode([
            "avatar_url" => self::$avatarurl,
            "username" => self::$username,
            "embeds" => [
                [
                    "title" => self::$title,
                    "type" => "rich",
                    "color" => self::$color,
                    "fields" => [
                        [
                            "name" => self::$field,
                            "value" => self::$value,
                            "inline" => false
                        ]
                    ],
                    "footer" => [
                        "text" => "Developed by VectorNetworkProject",
                        "icon_url" => "https://avatars2.githubusercontent.com/u/41660146?s=200&v=4"
                    ]
                ]
            ]
        ]));
    }
}