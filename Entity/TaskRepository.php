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
    
    /**
     * 
     * @param array $ids
     * @param array $values
     * @return mixed
     */
    public function updateMany(array $ids, array $values)
    {
        $updatePart = [];
        $columns = array_keys($values);
        foreach ($columns as $column) {
            $updatePart[] = sprintf('t.%s = :%1$s', $column);
        }
        $dql = sprintf('UPDATE %s t SET %s WHERE t.id IN(:ids)', $this->_entityName, join(', ', $updatePart));
        $qb = $this->_em->createQuery($dql);
        $values['ids'] = $ids;
        $qb->setParameters($values);
        return $qb->execute();
    }
}