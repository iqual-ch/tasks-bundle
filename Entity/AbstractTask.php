<?php

namespace TasksBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass(repositoryClass="TasksBundle\Entity\TaskRepository")
 */
abstract class AbstractTask implements TaskInterface
{
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_PENDING = 'pending';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_DONE = 'done';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @var DateTime
     */
    protected $deadline;

    /**
     * @ORM\Column(type="integer", name="remind_in", nullable=true)
     * @var int
     */
    protected $remindIn;

    /**
     * @ORM\Column(type="date", name="alert_date", nullable=true)
     * @var DateTime
     */
    protected $alertDate;
    
    /**
     *
     * @ORM\Column(type="string", length=127, nullable=true)
     * @var string
     */
    protected $type;
    
    /**
     *
     * @ORM\Column(type="string", length=127, nullable=true)
     * @var string
     */
    protected $status = self::STATUS_NOT_STARTED;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->status = self::STATUS_NOT_STARTED;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @return DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     *
     * @return int
     */
    public function getRemindIn()
    {
        return $this->remindIn;
    }

    /**
     *
     * @return DateTime
     */
    public function getAlertDate()
    {
        return $this->alertDate;
    }

    /**
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @param DateTime $deadline
     */
    public function setDeadline(DateTime $deadline)
    {
        $this->deadline = $deadline;
    }

    /**
     *
     * @param int $days
     */
    public function setRemindIn($days)
    {
        $this->remindIn = $days;
    }

    /**
     *
     * @param DateTime $alertDate
     */
    public function setAlertDate(DateTime $alertDate)
    {
        $this->alertDate = $alertDate;
    }

    /**
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * 
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    
    /**
     * 
     * @return string[]
     */
    public static function getStatuses()
    {
        return array(
            self::STATUS_NOT_STARTED, self::STATUS_PENDING, self::STATUS_OVERDUE, self::STATUS_DONE
        );
    }
    
    /**
     * 
     * @param string $status
     * @param string $prefix
     * @return string
     */
    public function prepareStatusName($status, $delimiter = '.', $prefix = '', $postfix = '')
    {
        return $prefix . $delimiter . $status . $delimiter . $postfix;
    }

    public function isOverdue()
    {
        return $this->deadline < new DateTime;
    }
}