<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Kernel;

use Nora\Architecture\DI\InjectorInterface;
use Nora\Utility\FileSystem\CreateWritableDirectory;
use ReflectionClass;

class Bootstrap
{
    public function __invoke(
        string $name,
        string $context = 'app',
        string $directory = null,
        string $cacheNamespace = '',
        string $tmpDirectory = null,
        string $logDirectory = null
    ) : KernelInterface {
        $directory = $directory ?? $this->getDirectory($name);
        $meta = new KernelMeta($name, $context, $directory);
        $meta->tmpDir = (new CreateWritableDirectory)(
            ($tmpDirectory ?? ($directory.'/var/tmp/'.$context))
        );
        $meta->logDir = (new CreateWritableDirectory)(
            ($logDirectory ?? ($directory.'/var/log/'.$context))
        );
        $injector = new KernelInjector($meta);
        $kernelId = $meta->name . ucwords($context) . $cacheNamespace;
        $kernel = $injector->getInstance(KernelInterface::class);
        return $kernel;
    }

    private function getDirectory(string $name) : string
    {
        $class = $name . "\\Kernel";
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Invalid Class ". $class);
        }
        return dirname(
            (string) (new ReflectionClass($class))->getFileName(),
            2
        );
    }
}
