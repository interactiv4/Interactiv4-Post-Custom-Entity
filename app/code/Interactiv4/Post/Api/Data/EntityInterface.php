<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

interface EntityInterface extends CustomAttributesDataInterface
{
    const TABLE       = 'interactiv4_post_entity';
    const ID          = 'id';
    const NAME        = 'name';
    const DESCRIPTION = 'description';

    /**
     * Retrieve the name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Retrieve the description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Interactiv4\Post\Api\Data\EntityExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Interactiv4\Post\Api\Data\EntityExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\Interactiv4\Post\Api\Data\EntityExtensionInterface $extensionAttributes);
}
