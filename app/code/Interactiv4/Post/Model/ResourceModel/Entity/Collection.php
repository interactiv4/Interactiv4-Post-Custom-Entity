<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Model\ResourceModel\Entity;

use Interactiv4\Post\Model\Entity as ModelEntity;
use Interactiv4\Post\Model\ResourceModel\Entity as ResourceModelEntity;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(ModelEntity::class, ResourceModelEntity::class);
    }
}
