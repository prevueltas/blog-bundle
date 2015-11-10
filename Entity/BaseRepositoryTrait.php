<?php

namespace Prh\BlogBundle\Entity;

/**
 * Class BaseRepositoryTrait.
 */
trait BaseRepositoryTrait
{
    /**
     * Save an object in the database.
     *
     * @param $entity
     */
    public function save($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    /**
     * Delete an object from the database.
     *
     * @param $entity
     */
    public function delete($entity)
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush($entity);
    }
}
