<?php
/**
 *  Copyright © Magento, Inc. All rights reserved.
 *  See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\InventoryInStorePickup\Model\PickupLocation\Mapper\PreProcessor;

use Magento\Framework\Filter\Template;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryInStorePickupApi\Model\Mapper\PreProcessorInterface;

/**
 * Processor for transferring Frontend Description from Source entity to Pickup Location entity Description.
 */
class FrontendDescription implements PreProcessorInterface
{
    /**
     * @var Template
     */
    private $templateFilter;

    /**
     * @param Template $templateFilter
     */
    public function __construct(Template $templateFilter)
    {
        $this->templateFilter = $templateFilter;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function process(SourceInterface $source, $value): string
    {
        return $value ? $this->templateFilter->filter($value) : $value;
    }
}
