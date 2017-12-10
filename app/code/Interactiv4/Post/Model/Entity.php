<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Model;

use Interactiv4\Post\Api\Data\EntityExtensionInterface;
use Interactiv4\Post\Api\Data\EntityInterface;
use Interactiv4\Post\Model\ResourceModel\Entity as ResourceModelEntity;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Entity extends AbstractExtensibleModel implements EntityInterface, IdentityInterface
{
    const CACHE_TAG = 'interactiv4_post_entity';

    /**
     * @var string
     */
    protected $_cacheTag = 'interactiv4_post_entity';

    /**
     * @var string
     */
    protected $_eventPrefix = 'interactiv4_post_entity';

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelEntity::class);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->_getData(self::DESCRIPTION);
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritdoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritdoc
     */
    public function setExtensionAttributes(EntityExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
