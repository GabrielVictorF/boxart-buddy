<?php

namespace App\Config\Processor;

use App\Config\Definition\ApplicationConfiguration;
use App\FolderNames;
use App\Util\Path;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

readonly class ApplicationConfigurationProcessor
{
    public const CONFIG_FILENAME = 'config.yml';
    public const CONFIG_FOLDER_FILENAME = 'config_folder.yml';
    public const CONFIG_FOLDER_ROMS = 'folder_roms.yml';
    public const CONFIG_ROM_TRANSLATIONS = 'rom_translations.yml';
    public const CONFIG_PORTMASTER_FILENAME = 'config_portmaster.yml';
    public const CONFIG_PACKAGE_FILENAME = 'config_package_muos.yml';
    public const PORTMASTER_ALTERNATES_FILE = 'portmaster-alternates.yml';

    public function __construct(private Path $path)
    {
    }

    public function process(): array
    {
        $config = Yaml::parseFile($this->path->joinWithBase(FolderNames::USER_CONFIG->value, self::CONFIG_FILENAME));
        $packageConfig = Yaml::parseFile($this->path->joinWithBase('config/application/', self::CONFIG_PACKAGE_FILENAME));
        $portmaster = Yaml::parseFile($this->path->joinWithBase(FolderNames::USER_CONFIG->value, self::CONFIG_PORTMASTER_FILENAME));
        $folderRoms = Yaml::parseFile($this->path->joinWithBase(FolderNames::USER_CONFIG->value, self::CONFIG_FOLDER_ROMS));
        $portmasterAlternates = Yaml::parseFile($this->path->joinWithBase('resources', self::PORTMASTER_ALTERNATES_FILE));

        $folderConfigPath = $this->path->joinWithBase(FolderNames::USER_CONFIG->value, self::CONFIG_FOLDER_FILENAME);
        $folderConfig = file_exists($folderConfigPath) ? Yaml::parseFile($folderConfigPath) : [];

        $processor = new Processor();
        $applicationConfiguration = new ApplicationConfiguration();

        return $processor->processConfiguration(
            $applicationConfiguration,
            [
                $config,
                ['folders' => $folderConfig],
                ['package' => $packageConfig],
                ['portmaster' => $portmaster],
                ['portmaster_alternates' => $portmasterAlternates],
                ['folder_roms' => $folderRoms],
            ]
        );
    }
}
