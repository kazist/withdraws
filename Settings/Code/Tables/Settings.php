<?php

namespace Withdraws\Settings\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table(name="withdraws_settings", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="gateway_id_index", columns={"gateway_id"})})
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
     * @var integer
     *
     * @ORM\Column(name="gateway_id", type="integer", length=11, nullable=false)
     */
    protected $gateway_id;

    /**
     * @var string
     *
     * @ORM\Column(name="withdraw_id", type="string", length=255, nullable=true)
     */
    protected $withdraw_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_valid", type="integer", length=11)
     */
    protected $is_valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

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
     * Set gatewayId
     *
     * @param integer $gatewayId
     *
     * @return Settings
     */
    public function setGatewayId($gatewayId)
    {
        $this->gateway_id = $gatewayId;

        return $this;
    }

    /**
     * Get gatewayId
     *
     * @return integer
     */
    public function getGatewayId()
    {
        return $this->gateway_id;
    }

    /**
     * Set withdrawId
     *
     * @param string $withdrawId
     *
     * @return Settings
     */
    public function setWithdrawId($withdrawId)
    {
        $this->withdraw_id = $withdrawId;

        return $this;
    }

    /**
     * Get withdrawId
     *
     * @return string
     */
    public function getWithdrawId()
    {
        return $this->withdraw_id;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return Settings
     */
    public function setIsValid($isValid)
    {
        $this->is_valid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return integer
     */
    public function getIsValid()
    {
        return $this->is_valid;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return Settings
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set params
     *
     * @param string $params
     *
     * @return Settings
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string
     */
    public function getParams()
    {
        return $this->params;
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

