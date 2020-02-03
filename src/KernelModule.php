<?php
namespace Nora\Kernel;

use Nora\Architecture\DI\Configuration\AbstractConfigurator;
use Nora\Kernel\Annotation\KernelName;
use Nora\Kernel\Annotation\ProjectRoot;
use Nora\Utility\DotEnv\EnvLoader;

class KernelModule extends AbstractConfigurator
{
    public function __construct(KernelMeta $meta)
    {
        $this->meta = $meta;
        parent::__construct(null);
    }

    public function configure()
    {
        $this->bind(KernelMeta::class)->toInstance($this->meta);
        $this
            ->bind(KernelInterface::class)
            ->to($this->meta->name . "\\Kernel");
        $this
            ->bind()
            ->annotatedWith(KernelName::class)
            ->toInstance($this->meta->name);
        $this
            ->bind()
            ->annotatedWith("project_root")
            ->toInstance($this->meta->directory);
    }
}
