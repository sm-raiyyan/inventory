<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Inventory\Indexer;

use Magento\Inventory\Indexer\Scope\IndexSwitcherInterface;
use Magento\Inventory\Indexer\Scope\State;
use Magento\Inventory\Indexer\StockItem\DataProvider;
use Magento\Inventory\Indexer\StockItem\DimensionFactory;
use Magento\Inventory\Indexer\StockItem\IndexHandler;
use Magento\Inventory\Indexer\StockItem\Service\GetAssignedStocksInterface;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @inheritdoc
 */
class StockItem implements StockItemIndexerInterface
{

    /**
     * @var GetAssignedStocksInterface
     */
    private $assignedStocksForSource;

    /**
     * @var DimensionFactory
     */
    private $dimensionFactory;

    /**
     * @var IndexHandler
     */
    private $handler;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var State
     */
    private $indexScopeState;

    /**
     * @var IndexSwitcherInterface
     */
    private $indexSwitcher;

    /**
     * StockItem constructor.
     * @param DimensionFactory $dimensionFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        DimensionFactory $dimensionFactory,
        GetAssignedStocksInterface $assignedStocksForSource,
        State $indexScopeState,
        IndexHandler $handler,
        DataProvider $dataProvider,
        IndexSwitcherInterface $indexSwitcher
    ) {
        $this->dimensionFactory = $dimensionFactory;
        $this->handler = $handler;
        $this->dataProvider = $dataProvider;
        $this->assignedStocksForSource = $assignedStocksForSource;
        $this->indexScopeState = $indexScopeState;
        $this->indexSwitcher = $indexSwitcher;
    }

    /**
     * @inheritdoc
     */
    public function executeFull()
    {
        $stocks = $this->assignedStocksForSource->execute([]);

        foreach ($stocks as $stock) {
            $stockId = $stock[StockInterface::STOCK_ID];
            $dimensions = [$this->dimensionFactory->create(['name' => 'stock_', 'value' => $stockId])];
            $this->indexScopeState->useTemporaryIndex();
            $this->handler->cleanIndex($dimensions);
            $this->handler->saveIndex($dimensions, $this->dataProvider->fetchDocuments($stockId, []));

            $this->indexSwitcher->switchIndex($dimensions, static::INDEXER_ID);
            $this->indexScopeState->useRegularIndex();
        }
    }

    /**
     * @inheritdoc
     */
    public function executeRow($id)
    {
        $this->executeList([$id]);
    }

    /**
     * @inheritdoc
     */
    public function executeList(array $ids)
    {
        $stocks = $this->assignedStocksForSource->execute($ids);

        foreach ($stocks as $stockId) {
            $dimensions = [$this->dimensionFactory->create(['name' => 'stock', 'value' => $stockId])];
            $this->handler->cleanIndex($dimensions);
            $this->handler->saveIndex($dimensions, $this->dataProvider->fetchDocuments($stockId, $ids));
        }
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return StockItem::INDEXER_ID;
    }
}
