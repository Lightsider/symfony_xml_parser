<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;


class ClientRepository extends EntityRepository
{
    public function findAllWithPhones() {
        return $this->createQueryBuilder('clients')
            ->getQuery()
            ->execute();
    }
}