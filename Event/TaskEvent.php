<?php
namespace TasksBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use TasksBundle\Entity\TaskInterface;

class TaskEvent extends Event
{
    const EVENT_EDITED = 'task.event.edited';
    const EVENT_EDIT_ERROR = 'task.event.edit_error';
    const EVENT_DELETED = 'task.event.deleted';
    const EVENT_DELETE_ERROR = 'task.event.delete_error';
    
    /**
     *
     * @var TaskInterface
     */
    protected $task;

    /**
     * 
     * @param TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * 
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

}