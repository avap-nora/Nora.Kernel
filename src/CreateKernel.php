<?php
/**
 * this file is part of Nora
 */
declare(strict_types=1);

namespace Nora\Kernel;

use Nora\Architecture\DI\Bind;
use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Architecture\DI\Configuration\NullConfigurator;
use Nora\Architecture\DI\InjectorInterface;
use Nora\Kernel\Exception\InvalidContextException;
use Nora\Kernel\Exception\InvalidModuleException;

class CreateKernel
{
    private $module;

    /**
     * Kernel Create
     */
    public function __invoke(KernelMeta $meta)
    {
        if ($this->module instanceof AbstractConfigurator) {
            return $this->module;
        }
        $contextsArray = array_reverse(explode('-', $meta->context));
        $module = new NullConfigurator;

        // Context
        $this->loadedContexts = [];
        foreach ($contextsArray as $contextItem) {
            $class = $meta->name . '\Context\\' . ucwords($contextItem) . 'Module';
            if (! class_exists($class)) {
                $class = 'Nora\Kernel\Context\\' . ucwords($contextItem) . 'Context';
            }
            if (! is_a($class, AbstractConfigurator::class, true)) {
                throw new InvalidContextException($contextItem);
            }
            $this->loadedContexts[$contextItem] = $class;

            $module = is_subclass_of(
                $class,
                AbstractKernelModule::class
            ) ? new $class($meta, $module) : new $class($module);
        }
        if (! $module instanceof AbstractKernelModule) {
            throw new InvalidModuleException; // @codeCoverageIgnore
        }

        $module->override(new KernelModule($meta));

        // Bind
        (new Bind($module->getContainer(), InjectorInterface::class))->toInstance($this);

        $this->module = $module;
        return $module;
    }
}
