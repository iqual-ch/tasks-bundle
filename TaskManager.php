<?php
namespace TasksBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class TaskManager
{
    /**
     *
     * @var ObjectManager
     */
    protected $objectManager;
    
    /**
     *
     * @var string
     */
    protected $entityClass;
    
    /**
     * 
     * @param ObjectManager $objectManager
     * @param string $entityClass
     */
    public function __construct(ObjectManager $objectManager, $entityClass)
    {
        $this->objectManager = $objectManager;
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
     * @param object $object
     */
    public function store($object)
    {
        if (!$this->objectManager->contains($object)) {
            $this->objectManager->persist($object);
        }
        $this->objectManager->flush($object);
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
        $this->objectManager->remove($object);
        $this->objectManager->flush($object);
        return true;
    }
    
    /**
     * Search for tasks. Every key is a field name and value is a field value.
     * 
     * @param array $params
     * @return \Doctrine\Common\Collections\Collection
     */
    public function search(array $params = array()) 
    {
        return $this->findBy($params);
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
            $this->objectManager->getRepository($this->entityClass),
            $name
        ), $arguments);
    }
}