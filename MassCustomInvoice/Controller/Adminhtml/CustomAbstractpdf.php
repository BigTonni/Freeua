<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Freeua\MassCustomInvoice\Controller\Adminhtml;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use \Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Email\Model\Template\Config;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Registry;

/**
 * Class CustomAbstractpdf
 * @package Freeua\MassCustomInvoice\Controller\Adminhtml
 * @SuppressWarnings("FoundProtected")
 */
abstract class CustomAbstractpdf extends Action
{

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var Config
     */
    private $emailConfig;

    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    /**
     * Abstractpdf constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param Config $emailConfig
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Config $emailConfig,
        JsonFactory $resultJsonFactory
    ) {

        $this->emailConfig = $emailConfig;
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->resultJsonFactory = $resultJsonFactory;
    }
    
    /**
     * Set status to collection items
     *
     * @param AbstractCollection $collection
     * @return ResponseInterface|ResultInterface
     */
    abstract protected function massAction(AbstractCollection $collection);
}
