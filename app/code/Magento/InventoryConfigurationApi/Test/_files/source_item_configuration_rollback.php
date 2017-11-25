<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Framework\Api\DataObjectHelper;
use Magento\InventoryConfigurationApi\Api\Data\SourceItemConfigurationInterfaceFactory;
use Magento\InventoryConfigurationApi\Api\Data\SourceItemConfigurationInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\InventoryConfigurationApi\Api\DeleteSourceItemConfigurationInterface;

/** @var DataObjectHelper $dataObjectHelper */
$dataObjectHelper = Bootstrap::getObjectManager()->get(DataObjectHelper::class);
/** @var SourceItemInterfaceFactory $sourceItemFactory */
$configurationFactory = Bootstrap::getObjectManager()->get(SourceItemConfigurationInterfaceFactory::class);
/** @var  DeleteSourceItemConfigurationInterface $configurationDelete */
$configurationDelete = Bootstrap::getObjectManager()->get(DeleteSourceItemConfigurationInterface::class);

$inventoryConfigurationData = [
    SourceItemConfigurationInterface::SOURCE_ITEM_ID => 1,
    SourceItemConfigurationInterface::INVENTORY_NOTIFY_QTY => 2.000
];

/** @var SourceItemConfigurationInterface $inventoryConfiguration */
$inventoryConfiguration = $configurationFactory->create();
$dataObjectHelper->populateWithArray(
    $inventoryConfiguration,
    $inventoryConfigurationData,
    SourceItemConfigurationInterface::class
);

$configurationDelete->execute($inventoryConfiguration);
