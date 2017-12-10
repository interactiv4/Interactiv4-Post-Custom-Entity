<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Api;

interface EntityRepositoryInterface
{

    /**
     * Save entity
     *
     * @param \Interactiv4\Post\Api\Data\EntityInterface $entity
     * @return \Interactiv4\Post\Api\Data\EntityInterface
     */
    public function save(\Interactiv4\Post\Api\Data\EntityInterface $entity);

    /**
     * Retrieve entity by id
     *
     * @param int $entityId
     * @return \Interactiv4\Post\Api\Data\EntityInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($entityId);

    /**
     * Retrieve entity by attribute
     *
     * @param $attributeCode
     * @param $value
     * @return \Interactiv4\Post\Api\Data\EntityInterface|boolean
     */
    public function get($attributeCode, $value);

    /**
     * Delete $entity.
     *
     * @param \Interactiv4\Post\Api\Data\EntityInterface $entity
     * @return boolean
     * @throws \Magento\Framework\Exception\CouldNotSaveException|\Magento\Framework\Exception\StateException
     */
    public function delete(\Interactiv4\Post\Api\Data\EntityInterface $entity);

    /**
     * Delete entity by ID.
     *
     * @param int $entityId
     * @return boolean
     */
    public function deleteById($entityId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Interactiv4\Post\Api\Data\EntitySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
