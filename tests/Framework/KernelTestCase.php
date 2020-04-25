<?php

namespace App\Tests\Framework;

use App\Tests\Framework\Traits\RefreshDatabase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;

abstract class KernelTestCase extends BaseKernelTestCase
{
    use RefreshDatabase;

    protected $em;

    protected function setUp(): void
    {
        parent::setUP();

        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();


        $this->refreshDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->em->close();
        $this->em = null;
    }
}