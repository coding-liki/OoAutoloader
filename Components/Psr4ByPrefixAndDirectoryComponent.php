<?php

namespace CodingLiki\OoAutoloader\Components;

use CodingLiki\OoAutoloader\Interfaces\AutoloaderComponentInterface;
use CodingLiki\OoAutoloader\PrefixesFileGenerator;

class Psr4ByPrefixAndDirectoryComponent implements AutoloaderComponentInterface
{

    public function load(string $fullClassName): bool
    {
        $prefixes = json_decode(file_get_contents(PrefixesFileGenerator::PREFIXES_FILE_PATCH), true);

        foreach ($prefixes as $prefix => $directory){
            if(str_starts_with($fullClassName, $prefix)){
                $fileName = $directory.substr($fullClassName, strlen($prefix)).".php";
            } else {
                continue;
            }
            if (file_exists($fileName)) {
                require_once $fileName;

                return true;
            }
        }

        return false;
    }
}