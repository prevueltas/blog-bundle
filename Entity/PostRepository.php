<?php

namespace Prh\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class PostRepository.
 */
class PostRepository extends EntityRepository
{
    use BaseRepositoryTrait;

    /**
     * Find posts filtered by categories.
     *
     * @param array $categories Category ids
     * @param bool $in Apply IN or NOT IN in the query
     *
     * @return array|Posts[]
     */
    public function findPublishedPostsByCategories(array $categories, $in = true)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.categories', 'c');

        if ($in) {
            $qb->where($qb->expr()->in('c.id', $categories));
        } else {
            $qb->where($qb->expr()->notIn('c.id', $categories));
        }

        $qb->andWhere('p.state = ' . Post::STATE_PUBLISHED)
            ->orderBy('p.date', 'DESC');

        return $qb->getQuery()->getResult();
    }
}