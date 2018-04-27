<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Freeua\DispatchedOrders\Controller\Adminhtml\Order;
//use Magento\Framework\Model\Resource\Db\Collection\AbstractCollection;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\ConfigOptionsListConstants;

function vardump($str){
    var_dump('<pre>');
    var_dump($str);
    var_dump('<pre>');
    
}

class MassDispatched extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    public $_resource;
    private $deploymentConfig;
    public function __construct(Context $context, ResourceConnection $resource,
        Filter $filter, CollectionFactory $collectionFactory,DeploymentConfig $deploymentConfig){
        
            $this->_resource = $resource;
            parent::__construct($context , $filter);
            $this->deploymentConfig = $deploymentConfig;
            $this->collectionFactory = $collectionFactory;
 
    }
    /**
     * Cancel selected orders
     *
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
        $countCancelOrder = 0;
        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $showTables = $connection->fetchCol('show tables');
//        //start get table name
//
//        // insert table prefix, if you use any, for example magento2_ ...
        $tblPrefix = (string)$this->deploymentConfig->get(
                ConfigOptionsListConstants::CONFIG_PATH_DB_PREFIX
        );
//        
        $tblSalesOrder = $connection->getTableName($tblPrefix . 'sales_order');
//        $tblSalesCreditmemoComment = $connection->getTableName($tblPrefix . 'sales_creditmemo_comment');
//        $tblSalesCreditmemoItem = $connection->getTableName($tblPrefix . 'sales_creditmemo_item');
//        $tblSalesCreditmemo = $connection->getTableName($tblPrefix . 'sales_creditmemo');
//        $tblSalesCreditmemoGrid = $connection->getTableName($tblPrefix . 'sales_creditmemo_grid');
//        $tblSalesInvoiceComment = $connection->getTableName($tblPrefix . 'sales_invoice_comment');
//        $tblSalesInvoiceItem = $connection->getTableName($tblPrefix . 'sales_invoice_item');
//        $tblSalesInvoice = $connection->getTableName($tblPrefix . 'sales_invoice');
//        $tblSalesInvoiceGrid = $connection->getTableName($tblPrefix . 'sales_invoice_grid');
//        $tblQuoteAddressItem = $connection->getTableName($tblPrefix . 'quote_address_item');
//        $tblQuoteItemOption = $connection->getTableName($tblPrefix . 'quote_item_option');
//        $tblQuote = $connection->getTableName($tblPrefix . 'quote');
//        $tblQuoteAddress = $connection->getTableName($tblPrefix . 'quote_address');
//        $tblQuoteItem = $connection->getTableName($tblPrefix . 'quote_item');
//        $tblQuotePayment = $connection->getTableName($tblPrefix . 'quote_payment');
//        $tblQuoteShippingRate = $connection->getTableName($tblPrefix . 'quote_shipping_rate');
//        $tblQuoteIDMask = $connection->getTableName($tblPrefix . 'quote_id_mask');
//        $tblSalesShipmentComment = $connection->getTableName($tblPrefix . 'sales_shipment_comment');
//        $tblSalesShipmentItem = $connection->getTableName($tblPrefix . 'sales_shipment_item');
//        $tblSalesShipmentTrack = $connection->getTableName($tblPrefix . 'sales_shipment_track');
//        $tblSalesShipment = $connection->getTableName($tblPrefix . 'sales_shipment');
//        $tblSalesShipmentGrid = $connection->getTableName($tblPrefix . 'sales_shipment_grid');
//        $tblSalesOrderAddress = $connection->getTableName($tblPrefix . 'sales_order_address');
//        $tblSalesOrderItem = $connection->getTableName($tblPrefix . 'sales_order_item');
//        $tblSalesOrderPayment = $connection->getTableName($tblPrefix . 'sales_order_payment');
//        $tblSalesOrderStatusHistory = $connection->getTableName($tblPrefix . 'sales_order_status_history');
//        $tblSalesOrderGrid = $connection->getTableName($tblPrefix . 'sales_order_grid');
//        $tblLogQuote = $connection->getTableName($tblPrefix . 'log_quote');
//        $showTablesLog = $connection->fetchCol('SHOW TABLES LIKE ?', '%'.$tblLogQuote);
//        $tblSalesOrderTax = $connection->getTableName($tblPrefix . 'sales_order_tax');    
        
        foreach ($collection->getItems() as $order) {

                $orderId = $order->getId();

                if ($order->getIncrementId()) {
                    if (in_array($tblSalesOrder, $showTables)) {
                        $result1 = $connection->fetchAll('SELECT customer_email FROM `'.$tblSalesOrder.'` WHERE entity_id='.$orderId);
                        $customerEmail = $result1[0]['customer_email'];
                        
                        if( $customerEmail != false ){
                            //Send text "We would like to inform you about your order have been dispatched." on customer's email
                        }

                    }
                    $connection->rawQuery('SET FOREIGN_KEY_CHECKS=1');
                }

            $countCancelOrder++;
        }

        if ($countCancelOrder) {
            $this->messageManager->addSuccess(__('We dispatched %1 order(s).', $countCancelOrder));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('sales/*/');
        return $resultRedirect;
    }
}
