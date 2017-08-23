<?php

namespace Withdraws\Settings\Gateways\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gateways
 *
 * @ORM\Table(name="withdraws_settings_gateways", indexes={@ORM\Index(name="user_id_index", columns={"user_id"}), @ORM\Index(name="gateway_id_index", columns={"gateway_id"}), @ORM\Index(name="setting_id_index", columns={"setting_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Gateways extends \Kazist\Table\BaseTable
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
     * @var integer
     *
     * @ORM\Column(name="setting_id", type="integer", length=11, nullable=false)
     */
    protected $setting_id;

    /**
     * @var string
     *
     * @ORM\Column(name="params", type="text", nullable=true)
     */
    protected $params;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_valid", type="integer", length=11, nullable=true)
     */
    protected $is_valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_default", type="integer", length=11, nullable=true)
     */
    protected $is_default;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

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
     * @return Gateways
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
     * @return Gateways
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
     * Set settingId
     *
     * @param integer $settingId
     *
     * @return Gateways
     */
    public function setSettingId($settingId)
    {
        $this->setting_id = $settingId;

        return $this;
    }

    /**
     * Get settingId
     *
     * @return integer
     */
    public function getSettingId()
    {
        return $this->setting_id;
    }

    /**
     * Set params
     *
     * @param string $params
     *
     * @return Gateways
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
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return Gateways
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
     * Set isDefault
     *
     * @param integer $isDefault
     *
     * @return Gateways
     */
    public function setIsDefault($isDefault)
    {
        $this->is_default = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return integer
     */
    public function getIsDefault()
    {
        return $this->is_default;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return Gateways
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

