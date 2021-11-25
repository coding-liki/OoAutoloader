<?php

namespace CodingLiki\OoAutoloader;

class PrefixesFileGenerator
{
    public const PREFIXES_FILE_PATCH = __DIR__.'/psr-4-prefixes.json';
    public const PREFIXES_FIELD_NAME = 'psr-4';

    public static function generateAndSave(){
        $mainVinillaSpec = json_decode(file_get_contents(__DIR__."/../../../vinilla.json"), true);
        $psr4Map = [ __DIR__."/../../.." => $mainVinillaSpec[self::PREFIXES_FIELD_NAME] ?? []];
        foreach ($mainVinillaSpec['depends_on'] as $module) {
            $moduleVendorPath = __DIR__.'/../../'.$module;
            $vinillaSpec = json_decode(file_get_contents($moduleVendorPath."/vinilla.json"), true);
            $psr4Map[$moduleVendorPath] = $vinillaSpec[self::PREFIXES_FIELD_NAME] ?? [];
        }

        $normalizedPsr4Map = self::normalizeMap($psr4Map);

        file_put_contents(self::PREFIXES_FILE_PATCH, json_encode($normalizedPsr4Map));
    }

    private static function normalizeMap(array $psr4Map)
    {
        $normalizedMap = [];
        foreach ($psr4Map as $root => $prefixes){
            foreach ($prefixes as $prefix => $directory) {
                $normalizedMap[$prefix] = $root."/$directory";
            }
        }

        return $normalizedMap;
    }
}