<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Controller;

use Orm\Zed\Oms\Persistence\Map\SpyOmsOrderItemStateHistoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\Communication\SalesCommunicationFactory;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Spryker\Zed\Sales\Business\SalesFacade;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method SalesCommunicationFactory getCommunicationFactory()
 * @method SalesFacade getFacade()
 * @method SalesQueryContainerInterface getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idOrder = $request->get('id-sales-order');

        $orderEntity = $this->getQueryContainer()
            ->querySalesOrderById($idOrder)
            ->findOne();

        if ($orderEntity === null) {
            throw new NotFoundHttpException('Record not found');
        }

        $orderItems = $this->getQueryContainer()
            ->querySalesOrderItemsWithState($idOrder)
            ->find();

        foreach ($orderItems as $orderItem) {
            $criteria = new Criteria();
            $criteria->addDescendingOrderByColumn(SpyOmsOrderItemStateHistoryTableMap::COL_ID_OMS_ORDER_ITEM_STATE_HISTORY);
            $orderItem->getStateHistoriesJoinState($criteria);
            $orderItem->resetPartialStateHistories(false);
        }

        $orderItemSplitFormCollection = $this->getCommunicationFactory()->getOrderItemSplitFormCollection($orderItems);

        $events = $this->getFacade()->getArrayWithManualEvents($idOrder);
        $allEvents = $this->groupEvents($events);
        $expenses = $this->getQueryContainer()
            ->querySalesExpensesByOrderId($idOrder)
            ->find();
        $shippingAddress = $this->getQueryContainer()
            ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressShipping())
            ->findOne();
        if ($orderEntity->getFkSalesOrderAddressShipping() === $orderEntity->getFkSalesOrderAddressBilling()) {
            $billingAddress = $shippingAddress;
        } else {
            $billingAddress = $this->getQueryContainer()
                ->querySalesOrderAddressById($orderEntity->getFkSalesOrderAddressBilling())
                ->findOne();
        }

        $logs = $this->getFacade()->getPaymentLogs($idOrder);

        $refunds = $this->getFacade()->getRefunds($idOrder);

        $itemsInProgress = $this->getCommunicationFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'in progress');
        $itemsPaid = $this->getCommunicationFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'paid');
        $itemsCancelled = $this->getCommunicationFactory()->getOmsFacade()->getItemsWithFlag($orderEntity, 'cancelled');

        return [
            'idOrder' => $idOrder,
            'orderDetails' => $orderEntity,
            'orderItems' => $orderItems,
            'events' => $events,
            'allEvents' => $allEvents,
            'expenses' => $expenses,
            'logs' => $logs,
            'refunds' => $refunds,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'orderItemSplitFormCollection' => $orderItemSplitFormCollection->create(),
            'itemsInProgress' => $itemsInProgress,
            'itemsPaid' => $itemsPaid,
            'itemsCancelled' => $itemsCancelled,
        ];
    }

    /**
     * @param $events
     *
     * @return array
     */
    protected function groupEvents($events)
    {
        $allEvents = [];
        foreach ($events as $eventList) {
            $allEvents = array_merge($allEvents, $eventList);
        }

        return array_unique($allEvents);
    }

}