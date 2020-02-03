<?php
declare(strict_types=1);

namespace Nora\Kernel;

use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    /**
     * @test
     */
    public function カーネルの呼び出し()
    {
        $kernel = (new Bootstrap)('NoraKernelFake', 'app-test');
        //
        // $this->assertInstanceOf(Kernel::class, $kernel);
        // return $kernel;
    }
}
