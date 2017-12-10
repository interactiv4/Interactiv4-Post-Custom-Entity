<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Model;

use Interactiv4\Post\Api\Data\EntityInterface;
use Interactiv4\Post\Api\Data\EntitySearchResultsInterfaceFactory;
use Interactiv4\Post\Api\EntityRepositoryInterface;
use Interactiv4\Post\Model\EntityFactory;
use Interactiv4\Post\Model\ResourceModel\Entity\Collection;
use Interactiv4\Post\Model\ResourceModel\Entity\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Exception;

class EntityRepository implements EntityRepositoryInterface
{

    /**
     * @var EntityFactory $entityFactory
     */
    private $entityFactory;

    /**
     * @var CollectionFactory $entityCollectionFactory
     */
    private $entityCollectionFactory;

    /**
     * @var EntitySearchResultsInterfaceFactory $entitySearchResultsInterfaceFactory
     */
    private $entitySearchResultsInterfaceFactory;

    /**
     * EntityRepository constructor.
     *
     * @param EntityFactory $entityFactory
     * @param CollectionFactory $entityCollectionFactory
     * @param EntitySearchResultsInterfaceFactory $entitySearchResultsInterfaceFactory
     */
    public function __construct(
        EntityFactory $entityFactory,
        CollectionFactory $entityCollectionFactory,
        EntitySearchResultsInterfaceFactory $entitySearchResultsInterfaceFactory
    ) {
        $this->entityFactory = $entityFactory;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entitySearchResultsInterfaceFactory = $entitySearchResultsInterfaceFactory;
    }

    /**
     * @inheritdoc
     */
    public function save(EntityInterface $entity)
    {
        $entity->getResource()->save($entity);

        return $entity;
    }


    /**
     * @inheritdoc
     */
    public function getById($entityId)
    {
        $entity = $this->entityFactory->create()->load($entityId);

        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Unable to find entity with ID "%1"', $entityId));
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function get($attributeCode, $value)
    {
        $entity = $this->entityFactory->create()->load($value, $attributeCode);

        if (!$entity->getId()) {
            return false;
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function delete(EntityInterface $entity)
    {

        $entityId = $entity->getId();
        try {
            $entity->getResource()->delete($entity);
        } catch (ValidatorException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove $entity %1', $entityId)
            );
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($entityId)
    {
        $entity = $this->getById($entityId);

        return $this->delete($entity);
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->entityCollectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */T
    private function addSortOrdersToCollection(
        SearchCriteriaInterface $searchCriteria,
        Collection $collection
    ) {
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addPagingToCollection(
        SearchCriteriaInterface $searchCriteria,
        Collection $collection
    ) {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return mixed
     */
    private function buildSearchResult(
        SearchCriteriaInterface $searchCriteria,
        Collection $collection
    ) {
        $searchResults = $this->entitySearchResultsInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
