<?php

namespace TasksBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass(repositoryClass="TasksBundle\Entity\TaskRepository")
 */
abstract class AbstractTask
{

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
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    protected $reminder;

    /**
     * @ORM\Column(type="date", name="alert_date", nullable=true)
     * @var DateTime
     */
    protected $alertDate;

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
    public function getReminder()
    {
        return $this->reminder;
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
     * @param int $reminder
     */
    public function setReminder($reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     *
     * @param DateTime $alertDate
     */
    public function setAlertDate(DateTime $alertDate)
    {
        $this->alertDate = $alertDate;
    }

}