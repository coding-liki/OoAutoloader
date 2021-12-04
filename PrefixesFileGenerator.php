<?php

namespace CodingLiki\OoAutoloader;

class PrefixesFileGenerator
{
    public const PREFIXES_FILE_PATCH = __DIR__ . '/psr-4-prefixes.json';
    public const PREFIXES_FIELD_NAME = 'psr-4';
    public const NOT_PROCESS = [
        '.',
        '..',
    ];

    public static function generateAndSave()
    {
        $vendorRootFolder = __DIR__ . "/../..";
        $vendorFolders = self::getFolderInFolder($vendorRootFolder);
        $rootPath = __DIR__."/../../..";
        $rootVinillaSpec = json_decode(file_get_contents($rootPath."/vinilla.json"), true);
        $psr4Map[$rootPath] = $rootVinillaSpec[self::PREFIXES_FIELD_NAME] ?? [];
        foreach ($vendorFolders as $vendorFolder) {
            $moduleFolders = self::getFolderInFolder($vendorRootFolder . "/$vendorFolder");
            foreach ($moduleFolders as $moduleFolder) {
                $modelPath = $vendorRootFolder . "/$vendorFolder/$moduleFolder";
                $vinillaSpec = json_decode(file_get_contents("$modelPath/vinilla.json"), true);
                $psr4Map[$modelPath] = $vinillaSpec[self::PREFIXES_FIELD_NAME] ?? [];
            }
        }

        $normalizedPsr4Map = self::normalizeMap($psr4Map);

        file_put_contents(self::PREFIXES_FILE_PATCH, json_encode($normalizedPsr4Map, JSON_PRETTY_PRINT));
    }

    private static function normalizeMap(array $psr4Map)
    {
        $normalizedMap = [];
        foreach ($psr4Map as $root => $prefixes) {
            foreach ($prefixes as $prefix => $directory) {
                $normalizedMap[$prefix][] = $root . "/$directory";
            }
        }

        return $normalizedMap;
    }

    private static function getFolderInFolder(string $folder): array
    {
        return array_filter(scandir($folder), function ($inFolder) use ($folder) {
            return is_dir($folder . "/$inFolder") && !in_array($inFolder, self::NOT_PROCESS);
        });
    }
}