<?php
namespace Framework\Config;
use Closure;
use Framework\DependencyInjection\DependencyManagerInterface;
use Framework\Handling\MiddlewareInterface;
class ConfigParser
{
    public const DEFAULT_CONFIG_PATH = "framework/Config/Defaults/";
    public const USER_CONFIG_PATH = "config/";
    public const CONFIG_FILES = [
        "services.yaml",
        "routing.yaml",
        "files.yaml"
    ];
    /**
     * @param string $directory
     * @return string
     */
    public static function getFullDirPath(string $directory): string
    {
        return dirname($_SERVER["DOCUMENT_ROOT"]) . "/" . $directory;
    }
    /**
     * @return void
     */
    public static function parseConfig(): void
    {
        $default_path = self::getFullDirPath(self::DEFAULT_CONFIG_PATH);
        $user_path = self::getFullDirPath(self::USER_CONFIG_PATH);
        foreach (self::CONFIG_FILES as $config_file){
            // Default config
            $default = [];
            if(file_exists($default_path . $config_file)){
                $default = @yaml_parse_file($default_path . $config_file) ?? [];
            }
            // User config
            $user = [];
            if(file_exists($user_path . $config_file)){
                $user = @yaml_parse_file($user_path . $config_file) ?? [];
            }
            // Merge
            Config::$config[str_replace(".yaml", "", $config_file)] = array_replace_recursive($default, $user);
        }
    }

}