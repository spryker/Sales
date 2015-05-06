<?php

namespace SprykerFeature\Zed\Sales\Dependency\Plugin;

use Generated\Shared\Transfer\StockStockProductTransfer;
use SprykerFeature\Zed\Stock\Persistence\Propel\SpyStockProduct;

interface ManagerStockPluginInterface
{
    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1);

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1);

    /**
     * @param StockStockProductTransfer $stockProduct
     *
     * @return SpyStockProduct
     */
    public function updateStockProduct(StockStockProductTransfer $stockProduct);
}