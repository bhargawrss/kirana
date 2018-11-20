<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\MarketPlace\Model\Locator;

use Lof\MarketPlace\Api\Data\ProductInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Interface LocatorInterface
 */
interface LocatorInterface
{
    /**
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * @return StoreInterface
     */
    public function getStore();

    /**
     * @return array
     */
    public function getWebsiteIds();

    /**
     * @return string
     */
    public function getBaseCurrencyCode();
}
