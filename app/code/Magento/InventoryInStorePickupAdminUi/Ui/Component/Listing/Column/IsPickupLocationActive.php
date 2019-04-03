<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryInStorePickupAdminUi\Ui\Component\Listing\Column;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\InventoryInStorePickupApi\Api\Data\PickupLocationInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class IsPickupLocationActive extends Column
{
    /**
     * Move extension attribute value to row data.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource):array
    {
        if (isset($dataSource['data']['totalRecords'])
            && $dataSource['data']['totalRecords'] > 0
        ) {
            foreach ($dataSource['data']['items'] as &$row) {
                $row[PickupLocationInterface::IS_PICKUP_LOCATION_ACTIVE] =
                    $row[ExtensibleDataInterface::EXTENSION_ATTRIBUTES_KEY]
                    [PickupLocationInterface::IS_PICKUP_LOCATION_ACTIVE] ?? '';
            }
        }

        return $dataSource;
    }
}
