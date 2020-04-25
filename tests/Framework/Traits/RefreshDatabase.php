<?php

namespace App\Tests\Framework\Traits;

use Doctrine\ORM\Tools\SchemaTool;


trait RefreshDatabase
{
    public function refreshDatabase(){
        static $metadata = null;

        if (is_null($metadata)) {
            $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();

        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
    }

}