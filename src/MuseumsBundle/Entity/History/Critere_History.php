<?php

namespace MuseumsBundle\Entity\History;

use Doctrine\ORM\Mapping as ORM;

/**
 * Critere_History
 *
 * @ORM\Table(name="history_critere__history")
 * @ORM\Entity(repositoryClass="MuseumsBundle\Repository\History\Critere_HistoryRepository")
 */
class Critere_History
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
     * @ORM\Column(name="critere_id", type="integer")
     */
    private $critere_id;

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
     * Set critereId.
     *
     * @param int $critereId
     *
     * @return Critere_History
     */
    public function setCritereId($critereId)
    {
        $this->critere_id = $critereId;

        return $this;
    }

    /**
     * Get critereId.
     *
     * @return int
     */
    public function getCritereId()
    {
        return $this->critere_id;
    }
}
