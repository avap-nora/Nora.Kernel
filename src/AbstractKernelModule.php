<?php
namespace Nora\Kernel;

use Nora\Architecture\DI\Configuration\AbstractConfigurator;

abstract class AbstractKernelModule extends AbstractConfigurator
{
    protected $meta;

    public function __construct(KernelMeta $meta, AbstractConfigurator $configurator = null)
    {
        $this->meta = $meta;
        parent::__construct($configurator);
    }
}
