<?php

/*
 * This file is part of the broadway/broadway-saga package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Broadway\Saga\State\MongoDB;

use Broadway\Saga\State\Testing\AbstractRepositoryTest;
use Doctrine\MongoDB\Collection;
use Doctrine\MongoDB\Configuration;
use Doctrine\MongoDB\Connection;

class MongoDBRepositoryTest extends AbstractRepositoryTest
{
    protected static $dbName = 'doctrine_mongodb';

    /** @var Connection */
    protected $connection;

    /**
     * @return MongoDBRepository
     */
    protected function createRepository()
    {
        $config = new Configuration();
        $config->setLoggerCallable(function ($msg) {});
        $this->connection = new Connection(null, [], $config);
        $db               = $this->connection->selectDatabase(self::$dbName);
        $coll             = $db->createCollection('test');

        return new MongoDBRepository($coll);
    }

    /**
     *
     */
    public function tearDown()
    {
        /** @var Collection[] $collections */
        $collections = $this->connection->selectDatabase(self::$dbName)->listCollections();
        foreach ($collections as $collection) {
            $collection->drop();
        }

        $this->connection->close();
        unset($this->connection);
    }
}
