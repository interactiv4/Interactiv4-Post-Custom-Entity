<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Api\Data;

interface EntitySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * @return \Interactiv4\Post\Api\Data\EntityInterface[]
     */
    public function getItems();

    /**
     * @param \Interactiv4\Post\Api\Data\EntityInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
