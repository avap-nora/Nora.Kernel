<?php
declare(strict_types=1);

namespace Nora\Kernel;

use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    public function testIsTrue()
    {
        $this->assertInstanceOf(Kernel::class, new Kernel());
    }
}
