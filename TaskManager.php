<?php
namespace TasksBundle;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Exception;
use TasksBundle\Entity\TaskInterface;

/**
 * @method mixed findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskManager
{
    /**
     *
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     *
     * @var string
     */
    protected $entityClass;
    
    /**
     * 
     * @param EntityManager $entityManager
     * @param string $entityClass
     */
    public function __construct(EntityManager $entityManager, $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
    }
    
    /**
     * Creates a new model instance and, optionally, populates it's values.
     * 
     * @param array $data (optional).
     * @return object
     */
    public function create(array $data = null)
    {
        $object = new $this->entityClass;
        if (null !== $data) {
            $this->hydrate($object, $data);
        }
        return $object;
    }

    /**
     * 
     * @param TaskInterface $object
     */
    public function store(TaskInterface $object)
    {
        if (!$this->entityManager->contains($object)) {
            $this->entityManager->persist($object);
        }
        $this->entityManager->flush($object);
    }
    
    /**
     * Removes model by id or instance.
     * 
     * @param int|object $object
     * @return boolean
     */
    public function remove($object)
    {
        if (is_numeric($object)) {
            $object = $this->find($object);
            if (!$object) {
                return false;
            }
        }
        $this->entityManager->remove($object);
        $this->entityManager->flush($object);
        return true;
    }
    
    /**
     * Search for tasks. Every key is a field name and value is a field value.
     * 
     * @param array $params
     * @return Collection
     */
    public function search(array $params = array(), array $orderBy = null, $limit = null, $offset = null) 
    {
        return $this->findBy($params, $orderBy, $limit, $offset);
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->entityManager->getRepository($this->entityClass)->createQueryBuilder('t');
    }
    
    /**
     * 
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }
    
    /**
     * Fills data into model.
     * 
     * @param object $object
     * @param array $data
     * @return type
     * @throws Exception
     */
    public function hydrate($object, array $data)
    {
        foreach ($data as $name => $value) {
            $method = 'set' . $name;
            if (!method_exists($method, $method)) {
                throw new Exception('Unknown method "' . $name . '".');
            }
            call_user_func_array(array($object, $method), array($value));
        }
        return $object;
    }
    
    /**
     * Proxies to ObjectRepository instance.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array(array(
            $this->entityManager->getRepository($this->entityClass),
            $name
        ), $arguments);
    }
}