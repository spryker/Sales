<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business;

use Generated\Shared\Transfer\ItemSplitResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\OrderItemsAndExpensesTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @method SalesBusinessFactory getBusinessFactory()
 */
class SalesFacade extends AbstractFacade
{

    /**
     * @param CommentTransfer $commentTransfer
     *
     * @return CommentTransfer
     */
    public function saveComment(CommentTransfer $commentTransfer)
    {
        $commentsManager = $this->getBusinessFactory()->createCommentsManager();

        return $commentsManager->saveComment($commentTransfer);
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getArrayWithManualEvents($idOrder)
    {
        $orderManager = $this->getBusinessFactory()->createOrderDetailsManager();

        return $orderManager->getArrayWithManualEvents($idOrder);
    }

    /**
     * @param int $idOrder
     *
     * @return array
     */
    public function getAggregateState($idOrder)
    {
        $orderManager = $this->getBusinessFactory()->createOrderDetailsManager();

        return $orderManager->getAggregateState($idOrder);
    }

    /**
     * @deprecated
     *
     * @param int $idOrderItem
     *
     * @return array
     */
    public function getOrderItemManualEvents($idOrderItem)
    {
        return $this->getBusinessFactory()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getManualEvents($idOrderItem);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return OrderTransfer
     */
    public function getOrderByIdSalesOrder($idSalesOrder)
    {
        return $this->getBusinessFactory()
            ->createOrderManager()
            ->getOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param int $idOrderItem
     *
     * @deprecated
     *
     * @return OrderItemsTransfer
     */
    public function getOrderItemById($idOrderItem)
    {
        return $this->getBusinessFactory()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_OMS)
            ->getOrderItemById($idOrderItem);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function saveOrder(OrderTransfer $orderTransfer)
    {
        return $this->getBusinessFactory()
            ->createOrderManager()
            ->saveOrder($orderTransfer);
    }

    /**
     * @param int $idSalesOrderItem
     * @param int $quantity
     *
     * @return ItemSplitResponseTransfer
     */
    public function splitSalesOrderItem($idSalesOrderItem, $quantity)
    {
        return $this->getBusinessFactory()->createOrderItemSplitter()->split($idSalesOrderItem, $quantity);
    }

    /**
     * @param int $idRefund
     * @param OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer
     *
     * @return void
     */
    public function updateOrderItemsAndExpensesAfterRefund($idRefund, OrderItemsAndExpensesTransfer $orderItemsAndExpensesTransfer)
    {
        $this->getBusinessFactory()
            ->createOrderDetailsManager()
            ->updateOrderItemsAndExpensesAfterRefund($idRefund, $orderItemsAndExpensesTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     * @param int $idOrder
     *
     * @return SpySalesOrder
     */
    public function updateOrderCustomer(OrderTransfer $orderTransfer, $idOrder)
    {
        return $this->getBusinessFactory()
            ->createOrderDetailsManager()
            ->updateOrderCustomer($orderTransfer, $idOrder);
    }

    /**
     * @param AddressTransfer $addressesTransfer
     * @param int $idAddress
     *
     * @return mixed
     */
    public function updateOrderAddress(AddressTransfer $addressesTransfer, $idAddress)
    {
        return $this->getBusinessFactory()
            ->createOrderDetailsManager()
            ->updateOrderAddress($addressesTransfer, $idAddress);
    }

    /**
     * @param string $idOrder
     *
     * @return array
     */
    public function getPaymentLogs($idOrder)
    {
        return $this->getBusinessFactory()
            ->createOrderDetailsManager()
            ->getPaymentLogs($idOrder);
    }

    /**
     * @param OrderListTransfer $orderListTransfer
     *
     * @return OrderListTransfer
     */
    public function getOrders(OrderListTransfer $orderListTransfer)
    {
        return $this->getBusinessFactory()
            ->createOrderManager()
            ->getOrders($orderListTransfer);
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function getOrderDetails(OrderTransfer $orderTransfer)
    {
        return $this->getBusinessFactory()
            ->createOrderDetailsManager()
            ->getOrderDetails($orderTransfer);
    }

    /**
     * @param int $idSalesOrder
     *
     * @return RefundTransfer[]
     */
    public function getRefunds($idSalesOrder)
    {
        return $this->getBusinessFactory()
            ->getProvidedDependency(SalesDependencyProvider::FACADE_REFUND)
            ->getRefundsByIdSalesOrder($idSalesOrder);
    }

}