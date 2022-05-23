<?php

namespace Asma\Hawari\Observer;

use Asma\Hawari\Helper\SQS;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;


class OrderObserver implements ObserverInterface
{
    private SQS $SQS;
    private LoggerInterface $logger;

    /**
     * @param \Magento\Sales\Model\Order $order
     * @param SQS $SQS
     */
    public function __construct(
        \Magento\Sales\Model\Order $order,
        SQS $SQS,
        LoggerInterface  $logger
    )
    {
        $this->order = $order;
        $this->SQS = $SQS;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @throws \Exception
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->debug('From Observer SQS');
        $orderId = $observer->getEvent()->getOrder()->getId();
        $order = $this->order->load($orderId);
        $orderState = Order::STATE_PROCESSING;
        $order->setState($orderState)->setStatus(Order::STATE_PROCESSING);
        $order->save();
        $this->SQS->preparePayLoad();
    }
}
