<?php
declare(strict_types=1);

namespace CodingLiki\OoAutoloader;

use CodingLiki\OoAutoloader\Components\FromSrcComponent;

require_once __DIR__ . '/Interfaces/AutoloaderComponentInterface.php';
$components = ['FromSrcComponent'];

foreach ($components as $component) {
    require_once sprintf("%s/Components/%s.php", __DIR__, $component);
}

class Autoloader
{

    /**
     * @var  Interfaces\AutoloaderComponentInterface[]
     */
    private array $autoloaderComponents;

    public function __construct(array $autoloaderComponents)
    {
        $this->autoloaderComponents = $autoloaderComponents;
    }

    public function init(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load(string $fullClassName): bool
    {
        foreach ($this->autoloaderComponents as $component) {
            if ($component->load($fullClassName)) {
                return true;
            }
        }

        return false;
    }
}


(new Autoloader(
    [
        new FromSrcComponent()
    ]
))->init();