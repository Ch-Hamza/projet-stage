<?php

namespace MuseumsBundle\Entity\History;

use Doctrine\ORM\Mapping as ORM;

/**
 * Canton_History
 *
 * @ORM\Table(name="history_canton__history")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\History\Canton_HistoryRepository")
 */
class Canton_History
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="action", type="string")
     */
    private $action;

    /**
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\Column(name="canton_id", type="integer")
     */
    private $canton_id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Set cantonId.
     *
     * @param int $cantonId
     *
     * @return Canton_History
     */
    public function setCantonId($cantonId)
    {
        $this->canton_id = $cantonId;

        return $this;
    }

    /**
     * Get cantonId.
     *
     * @return int
     */
    public function getCantonId()
    {
        return $this->canton_id;
    }
}
