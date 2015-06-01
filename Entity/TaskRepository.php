<?php
namespace TasksBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    /**
     * 
     * @param array $ids
     * @return mixed
     */
    public function removeMany(array $ids)
    {
        $qb = $this->_em->createQuery(sprintf('DELETE FROM %s t WHERE t.id IN(:ids)', $this->_entityName));
        $qb->setParameter('ids', $ids);
        return $qb->execute();
    }
}