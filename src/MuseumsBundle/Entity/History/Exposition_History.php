<?php

namespace MuseumsBundle\Entity\History;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exposition_History
 *
 * @ORM\Table(name="history_exposition__history")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\History\Exposition_HistoryRepository")
 */
class Exposition_History
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
     * @ORM\Column(name="exposition_id", type="integer")
     */
    private $exposition_id;

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
     * Set expositionId.
     *
     * @param int $expositionId
     *
     * @return Exposition_History
     */
    public function setExpositionId($expositionId)
    {
        $this->exposition_id = $expositionId;

        return $this;
    }

    /**
     * Get expositionId.
     *
     * @return int
     */
    public function getExpositionId()
    {
        return $this->exposition_id;
    }
}
