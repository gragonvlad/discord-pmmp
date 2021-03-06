<?php
/**
 * Created by PhpStorm.
 * User: InkoHX
 * Date: 2018/09/12
 * Time: 13:37
 */

namespace InkoHX\php\discordpmmp\Config;


class Offline
{
    /**
     * @return bool
     */
    public static function getOption(): bool
    {
        $data = DataFile::getOfflineSetting();
        return $data['send'];
    }

    /**
     * @return string
     */
    public static function getWebhookURL(): string
    {
        $data = DataFile::getOfflineSetting();
        return $data['webhook'];
    }

    /**
     * @return string
     */
    public static function getUsername(): string
    {
        $data = DataFile::getOfflineSetting();
        return $data['username'];
    }

    /**
     * @return string
     */
    public static function getAvatarURL(): string
    {
        $data = DataFile::getOfflineSetting();
        return $data['avatarurl'];
    }

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        $data = self::getEmbedSetting();
        return $data['title'];
    }

    /**
     * @return string
     */
    public static function getField(): string
    {
        $data = self::getEmbedSetting();
        return $data['field'];
    }

    /**
     * @return string
     */
    public static function getValue(): string
    {
        $data = self::getEmbedSetting();
        return $data['value'];
    }

    /**
     * @return int
     */
    public static function getColor(): int
    {
        $data = self::getEmbedSetting();
        return $data['color'];
    }

    /**
     * @return array
     */
    public static function getEmbedSetting(): array
    {
       $data = DataFile::getEmbedSetting();
       return $data['offline'];
    }
}