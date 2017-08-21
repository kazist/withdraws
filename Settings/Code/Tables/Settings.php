<?php

namespace Withdraws\Settings\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="withdraws_settings", indexes={@ORM\Index(name="user_id_index", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Settings extends \Kazist\Table\BaseTable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", length=11, nullable=false)
     */
    protected $user_id;

    /**
     * @var string
     *
     * @ORM\Column(name="id_passport", type="string", length=255, nullable=false)
     */
    protected $id_passport;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=255, nullable=false)
     */
    protected $pin;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Settings
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set idPassport
     *
     * @param string $idPassport
     *
     * @return Settings
     */
    public function setIdPassport($idPassport)
    {
        $this->id_passport = $idPassport;

        return $this;
    }

    /**
     * Get idPassport
     *
     * @return string
     */
    public function getIdPassport()
    {
        return $this->id_passport;
    }

    /**
     * Set pin
     *
     * @param string $pin
     *
     * @return Settings
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modifiedBy
     *
     * @return integer
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}

