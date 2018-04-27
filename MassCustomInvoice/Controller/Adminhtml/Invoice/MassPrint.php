<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Freeua\MassCustomInvoice\Controller\Adminhtml\Invoice;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Freeua\MassCustomInvoice\Helper\Pdf;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\Order\Pdf\Invoice;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository;
use Magento\Sales\Model\Order\InvoiceRepository;


class MassPrint extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction {

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::sales_invoice';

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var Invoice
     */
    protected $pdfInvoice;
    
    /**
     * @var ForwardFactory
     */

    private $resultForwardFactory;

    /**
     * @var Pdf
     */
    private $helper;
    
    /**
     * @var PdfgeneratorRepository
     */
    private $pdfGeneratorRepository;
    
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param DateTime $dateTime
     * @param FileFactory $fileFactory
     * @param Invoice $pdfInvoice
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        DateTime $dateTime,
        FileFactory $fileFactory,
        Invoice $pdfInvoice,
        CollectionFactory $collectionFactory,   
        Pdf $helper,
        ForwardFactory $resultForwardFactory,
        PdfgeneratorRepository $pdfGeneratorRepository,
        InvoiceRepository $invoiceRepository
    ) {
        $this->fileFactory = $fileFactory;
        $this->dateTime = $dateTime;
        $this->pdfInvoice = $pdfInvoice;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $filter);
        $this->helper = $helper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->pdfGeneratorRepository = $pdfGeneratorRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @return $this
     */
    private function returnNoRoute()
    {
        return $this->resultForwardFactory->create()->forward('noroute');
    }
    
    /**
     * Save collection items to pdf invoices
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface
     * @throws \Exception
     */
    public function massAction(AbstractCollection $collection)
    {
        //Choise pdf template
        $templateId = 3;

        if (!$templateId) {
            return $this->returnNoRoute();
        }

        $templateModel = $this->pdfGeneratorRepository
            ->getById($templateId);

        if (!$templateModel) {
            return $this->returnNoRoute();
        }
        
        $flag = false;
        foreach ($collection->getItems() as $invoice_obj) {
            $invoiceId = $invoice_obj->getId();
            
            if (!$invoiceId) {
                return $this->returnNoRoute();
            }

            $invoice = $this->invoiceRepository
                ->get($invoiceId);
            if (!$invoice) {
                return $this->returnNoRoute();
            }

            $helper = $this->helper;

            $helper->setInvoice($invoice);            
            $helper->setTemplate($templateModel);
            $helper->bodyPart();

            $flag = true;
        }      

        if( $flag ){
            $pdfFileData = $helper->template2Pdf();
            $date = $this->dateTime->date('Y-m-d_H-i-s');

            $fileName = $pdfFileData['filename'] . $date . '.pdf';

            $res = $this->fileFactory->create(
                $fileName,
                $pdfFileData['filestream'],
                DirectoryList::VAR_DIR,
                'application/pdf'
            );

            return true;
        }
        return false;     
    }

}