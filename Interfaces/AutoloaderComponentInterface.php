<?php
declare(strict_types=1);

namespace  Autoloader\Interfaces;

interface AutoloaderComponentInterface
{
    public function load(string $fullClassName): bool;
}