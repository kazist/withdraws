<?php

namespace Withdraws\Withdraws\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Withdraws
 *
 * @ORM\Table(name="withdraws_withdraws", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="created_by_index", columns={"created_by"}), @ORM\Index(name="modified_by_index", columns={"modified_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Withdraws extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="amount", type="decimal", precision=11, scale=2, nullable=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255, nullable=true)
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="paid_status", type="integer", length=11, nullable=true)
     */
    protected $paid_status;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_canceled", type="integer", length=11, nullable=true)
     */
    protected $is_canceled;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
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
     * @return Withdraws
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
     * @return Withdraws
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
     * Set amount
     *
     * @param string $amount
     *
     * @return Withdraws
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Withdraws
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Withdraws
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set paidStatus
     *
     * @param integer $paidStatus
     *
     * @return Withdraws
     */
    public function setPaidStatus($paidStatus)
    {
        $this->paid_status = $paidStatus;

        return $this;
    }

    /**
     * Get paidStatus
     *
     * @return integer
     */
    public function getPaidStatus()
    {
        return $this->paid_status;
    }

    /**
     * Set isCanceled
     *
     * @param integer $isCanceled
     *
     * @return Withdraws
     */
    public function setIsCanceled($isCanceled)
    {
        $this->is_canceled = $isCanceled;

        return $this;
    }

    /**
     * Get isCanceled
     *
     * @return integer
     */
    public function getIsCanceled()
    {
        return $this->is_canceled;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Withdraws
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set params
     *
     * @param string $params
     *
     * @return Withdraws
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

