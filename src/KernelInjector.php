<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Kernel;

use Nora\Architecture\AOP\Compiler\Compiler;
use Nora\Architecture\DI\Annotation\Inject;
use Nora\Architecture\DI\Bind;
use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Architecture\DI\Configuration\NullConfigurator;
use Nora\Architecture\DI\Dependency\Dependency;
use Nora\Architecture\DI\Exception\Untargeted;
use Nora\Architecture\DI\Injector;
use Nora\Architecture\DI\InjectorInterface;
use Nora\Architecture\DI\ValueObject\Name;
use Nora\Kernel\Exception\InvalidContextException;
use Nora\Utility\FileSystem\CreateWritableDirectory;

class KernelInjector implements InjectorInterface
{
    /**
     * @var string
     */
    private $scriptDir;


    /**
     * @var Configurator
     */
    private $configurator;

    /**
     * @var KernelMeta
     */
    private $meta;

    /**
     * @var array
     */
    public $loadedContexts;

    public $container;
    /**
     * Setup Kernel Injector
     */
    public function __construct(KernelMeta $meta)
    {
        $this->meta = $meta;
        $this->scriptDir = $meta->tmpDir . '/di';
        $this->classDir = (new CreateWritableDirectory)($meta->tmpDir . '/class');

        $this->container = (new CreateKernel)($meta)->getContainer();
        // Bind
        (new Bind($this->container, InjectorInterface::class))->toInstance($this);

        $this->container->weaveAspects(new Compiler($this->classDir));
    }

    /**
     * {@inheritDoc}
     */
    public function getInstance($interface, $name = Name::ANY)
    {
        try {
            $instance = $this->container->getInstance($interface, $name);
        } catch (Untargeted $e) {
            $this->bind($interface);
            $instance = $this->getInstance($interface, $name);
        }
        return $instance;
    }

    private function bind(string $class)
    {
        (new Bind($this->container, $class));
        $this->container->getInstance($class, Name::ANY);
        // $bound = $this->container[$class . '-' . Name::ANY];
        // if ($bound instanceof Dependency) {
        //     $this->container->weaveAspect(
        //         new Compiler($this->classDir),
        //         $bound
        //     )->getInstance($class, Name::ANY);
        // }
    }

    public function getContainer()
    {
        return $this->container;
    }
}
