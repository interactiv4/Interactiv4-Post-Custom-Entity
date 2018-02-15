<?php
/**
 * @author Interactiv4 Team
 * @copyright Copyright (c) 2017 Interactiv4 (https://www.interactiv4.com)
 * @package Interactiv4_Post
 */

namespace Interactiv4\Post\Model;

use Interactiv4\Post\Api\Data\EntityInterface;
use Interactiv4\Post\Api\Data\EntitySearchResultsInterface;
use Interactiv4\Post\Api\Data\EntitySearchResultsInterfaceFactory;
use Interactiv4\Post\Api\EntityRepositoryInterface;
use Interactiv4\Post\Model\EntityFactory;
use Interactiv4\Post\Model\ResourceModel\Entity\Collection;
use Interactiv4\Post\Model\ResourceModel\Entity\CollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
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
     * @var CollectionProcessorInterface $collectionProcessor
     */
    private $collectionProcessor;

    /**
     * @var JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;

    /**
     * EntityRepository constructor.
     *
     * @param EntityFactory $entityFactory
     * @param CollectionFactory $entityCollectionFactory
     * @param EntitySearchResultsInterfaceFactory $entitySearchResultsInterfaceFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        EntityFactory $entityFactory,
        CollectionFactory $entityCollectionFactory,
        EntitySearchResultsInterfaceFactory $entitySearchResultsInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {
        $this->entityFactory = $entityFactory;
        $this->entityCollectionFactory = $entityCollectionFactory;
        $this->entitySearchResultsInterfaceFactory = $entitySearchResultsInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
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
        return $this->get($entityId);
    }

    /**
     * @inheritdoc
     */
    public function get($value, $attributeCode = null)
    {
        /** @var Entity $entity */
        $entity = $this->entityFactory->create()->load($value, $attributeCode);

        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Unable to find entity'));
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
            throw new CouldNotDeleteException(
                __('Unable to remove entity %1', $entityId)
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
        /** @var Collection $collection */
        $collection = $this->entityCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, EntityInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var EntitySearchResultsInterface $searchResults */
        $searchResults = $this->entitySearchResultsInterfaceFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
