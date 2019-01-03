<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Asm\AdvanceSearch\Model\Rewrites;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\CatalogSearch\Model\ResourceModel\Advanced\Collection as ProductCollection;
use Magento\CatalogSearch\Model\ResourceModel\AdvancedFactory;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Eav\Model\Entity\Attribute as EntityAttribute;
use Magento\Framework\Model\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Catalog advanced search model
 * @method int getEntityTypeId()
 * @method \Magento\CatalogSearch\Model\Advanced setEntityTypeId(int $value)
 * @method int getAttributeSetId()
 * @method \Magento\CatalogSearch\Model\Advanced setAttributeSetId(int $value)
 * @method string getTypeId()
 * @method \Magento\CatalogSearch\Model\Advanced setTypeId(string $value)
 * @method string getSku()
 * @method \Magento\CatalogSearch\Model\Advanced setSku(string $value)
 * @method int getHasOptions()
 * @method \Magento\CatalogSearch\Model\Advanced setHasOptions(int $value)
 * @method int getRequiredOptions()
 * @method \Magento\CatalogSearch\Model\Advanced setRequiredOptions(int $value)
 * @method string getCreatedAt()
 * @method \Magento\CatalogSearch\Model\Advanced setCreatedAt(string $value)
 * @method string getUpdatedAt()
 * @method \Magento\CatalogSearch\Model\Advanced setUpdatedAt(string $value)
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @api
 * @since 100.0.2
 */
class Advanced extends \Magento\Framework\Model\AbstractModel
{
    /**
     * User friendly search criteria list
     *
     * @var array
     */
    protected $_searchCriterias = [];

    /**
     * Product collection
     *
     * @var ProductCollection
     */
    protected $_productCollection;

    /**
     * Initialize dependencies
     *
     * @var Config
     */
    protected $_catalogConfig;

    /**
     * Catalog product visibility
     *
     * @var Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Attribute collection factory
     *
     * @var AttributeCollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Product factory
     *
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * Currency factory
     *
     * @var CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * Advanced Collection Factory
     *
     * @var ProductCollectionFactory
     */
    protected $productCollectionFactory;
    protected $_sellerCollection;

    /**
     * Construct
     *
     * @param Context $context
     * @param Registry $registry
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Config $catalogConfig
     * @param CurrencyFactory $currencyFactory
     * @param ProductFactory $productFactory
     * @param StoreManagerInterface $storeManager
     * @param ProductCollectionFactory $productCollectionFactory
     * @param AdvancedFactory $advancedFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Registry $registry,
        AttributeCollectionFactory $attributeCollectionFactory,
        Visibility $catalogProductVisibility,
        Config $catalogConfig,
        CurrencyFactory $currencyFactory,
        ProductFactory $productFactory,
        StoreManagerInterface $storeManager,
        ProductCollectionFactory $productCollectionFactory,
        AdvancedFactory $advancedFactory,
        \Lof\MarketPlace\Model\Seller $sellerCollection,
        array $data = []
    ) {
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_catalogConfig = $catalogConfig;
        $this->_currencyFactory = $currencyFactory;
        $this->_productFactory = $productFactory;
        $this->_storeManager = $storeManager;
        $this->_sellerCollection = $sellerCollection;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct(
            $context,
            $registry,
            $advancedFactory->create(),
            $this->productCollectionFactory->create(),
            $data
        );
    }

    /**
     * Add advanced search filters to product collection
     *
     * @param   array $values
     * @return  $this
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function addFilters($values)
    {
        $attributes = $this->getAttributes();
        $allConditions = [];

        foreach ($attributes as $attribute) {
            /* @var $attribute Attribute */
            if (!isset($values[$attribute->getAttributeCode()])) {
                continue;
            }
            $value = $values[$attribute->getAttributeCode()];
            $preparedSearchValue = $this->getPreparedSearchCriteria($attribute, $value);
            if (false === $preparedSearchValue) {
                continue;
            }
            $this->addSearchCriteria($attribute, $preparedSearchValue);

            if ($attribute->getAttributeCode() == 'price') {
                $rate = 1;
                $store = $this->_storeManager->getStore();
                $currency = $store->getCurrentCurrencyCode();
                if ($currency != $store->getBaseCurrencyCode()) {
                    $rate = $store->getBaseCurrency()->getRate($currency);
                }

                $value['from'] = (isset($value['from']) && is_numeric($value['from']))
                    ? (float)$value['from'] / $rate
                    : '';
                $value['to'] = (isset($value['to']) && is_numeric($value['to']))
                    ? (float)$value['to'] / $rate
                    : '';
            }

            if ($attribute->getBackendType() == 'datetime') {
                $value['from'] = (isset($value['from']) && !empty($value['from']))
                    ? date('Y-m-d\TH:i:s\Z', strtotime($value['from']))
                    : '';
                $value['to'] = (isset($value['to']) && !empty($value['to']))
                    ? date('Y-m-d\TH:i:s\Z', strtotime($value['to']))
                    : '';
            }
            $condition = $this->_getResource()->prepareCondition(
                $attribute,
                $value,
                $this->getProductCollection()
            );
            if ($condition === false) {
                continue;
            }

            $table = $attribute->getBackend()->getTable();
            if ($attribute->getBackendType() == 'static') {
                $attributeId = $attribute->getAttributeCode();
            } else {
                $attributeId = $attribute->getId();
            }
            $allConditions[$table][$attributeId] = $condition;
        }
        if ($allConditions) {
            //print_r($allConditions);exit;
            $this->_registry->register('advanced_search_conditions', $allConditions);
            $this->getProductCollection()->addFieldsToFilter($allConditions);
        } else {
            throw new LocalizedException(__('Please specify at least one search term.'));
        }

        return $this;
    }

    /**
     * Retrieve array of attributes used in advanced search
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    public function getAttributes()
    {
        $attributes = $this->getData('attributes');
        if ($attributes === null) {
            $product = $this->_productFactory->create();
            $attributes = $this->_attributeCollectionFactory
                ->create()
                ->addHasOptionsFilter()
                ->addDisplayInAdvancedSearchFilter()
                ->addStoreLabel($this->_storeManager->getStore()->getId())
                ->setOrder('main_table.attribute_id', 'asc')
                ->load();
            foreach ($attributes as $attribute) {
                $attribute->setEntity($product->getResource());
            }
            $this->setData('attributes', $attributes);
        }
        return $attributes;
    }

    /**
     * Retrieve advanced search product collection
     *
     * @return Collection
     */
    public function getProductCollection()
    {
        if ($this->_productCollection === null) {
            $collection = $this->productCollectionFactory->create();
            $this->prepareProductCollection($collection);
            if (!$collection) {
                return $collection;
            }
            $this->_productCollection = $collection;
        }

        return $this->_productCollection;
    }

    /**
     * Prepare product collection
     *
     * @param Collection $collection
     * @return $this
     */
    public function prepareProductCollection($collection)
    {
       //$selerIdArray = array('11','39','40');
       //$centerpointLang = $this->getRequest()->getParam('lng');
        //$centerpointLat = $this->getRequest()->getParam('lat');
        //$title = $this->getRequest()->getParam('title');
        $selerIdArray = array();

        //$lat = $centerpointLat; //latitude
        $lat = '18.564688'; //latitude
        //$lon = $centerpointLang; //longitude
        $lon = '73.7783712'; //longitude
        $distance = 1; //your distance in KM
        $R = 6371; //constant earth radius. You can add precision here if you wish

        $maxLat = $lat + rad2deg($distance/$R);
        $minLat = $lat - rad2deg($distance/$R);
        $maxLon = $lon + rad2deg(asin($distance/$R) / cos(deg2rad($lat)));
        $minLon = $lon - rad2deg(asin($distance/$R) / cos(deg2rad($lat)));

        // filter collection in range of lat and long
        $sellerCollection = $this->_sellerCollection->getCollection()
        ->setOrder('position','ASC')
        ->addFieldToFilter('geo_lat',array('gteq'=>$minLat))
        ->addFieldToFilter('geo_lng',array('gteq'=>$minLon))
        ->addFieldToFilter('geo_lat',array('lteq'=>$maxLat))
        ->addFieldToFilter('geo_lng',array('lteq'=>$maxLon))
        ->addFieldToFilter('status',1);
        // get Seller id's
        $sellerData = $sellerCollection->getData();
        foreach($sellerData as $seldata):
            $selerIdArray[] = $seldata['seller_id'];
        endforeach;

        $collection
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->setStore($this->_storeManager->getStore())
            ->addMinimalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addAttributeToSort('price', 'asc')
            ->addFieldToFilter('seller_id', array('in' => $selerIdArray))
            ->setVisibility($this->_catalogProductVisibility->getVisibleInSearchIds());

        return $this;
    }

    /**
     * @param EntityAttribute $attribute
     * @param mixed $value
     * @return void
     */
    protected function addSearchCriteria($attribute, $value)
    {
        if (!empty($value)) {
            $this->_searchCriterias[] = ['name' => $attribute->getStoreLabel(), 'value' => $value];
        }
    }

    /**
     * Add data about search criteria to object state
     *
     * @todo: Move this code to block
     *
     * @param   EntityAttribute $attribute
     * @param   mixed $value
     * @return  string|bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function getPreparedSearchCriteria($attribute, $value)
    {
        if (is_array($value)) {
            if (isset($value['from']) && isset($value['to'])) {
                if (!empty($value['from']) || !empty($value['to'])) {
                    if (isset($value['currency'])) {
                        /** @var $currencyModel Currency */
                        $currencyModel = $this->_currencyFactory->create()->load($value['currency']);
                        $from = $currencyModel->format($value['from'], [], false);
                        $to = $currencyModel->format($value['to'], [], false);
                    } else {
                        $currencyModel = null;
                    }

                    if (strlen($value['from']) > 0 && strlen($value['to']) > 0) {
                        // -
                        $value = sprintf(
                            '%s - %s',
                            $currencyModel ? $from : $value['from'],
                            $currencyModel ? $to : $value['to']
                        );
                    } elseif (strlen($value['from']) > 0) {
                        // and more
                        $value = __('%1 and greater', $currencyModel ? $from : $value['from']);
                    } elseif (strlen($value['to']) > 0) {
                        // to
                        $value = __('up to %1', $currencyModel ? $to : $value['to']);
                    }
                } else {
                    return '';
                }
            }
        }

        if (($attribute->getFrontendInput() == 'select' ||
                $attribute->getFrontendInput() == 'multiselect') && is_array($value)
        ) {
            foreach ($value as $key => $val) {
                $value[$key] = $attribute->getSource()->getOptionText($val);

                if (is_array($value[$key])) {
                    $value[$key] = $value[$key]['label'];
                }
            }
            $value = implode(', ', $value);
        } elseif ($attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect') {
            $value = $attribute->getSource()->getOptionText($value);
            if (is_array($value)) {
                $value = $value['label'];
            }
        } elseif ($attribute->getFrontendInput() == 'boolean') {
            if (is_numeric($value)) {
                $value = $value == 1 ? __('Yes') : __('No');
            } else {
                $value = false;
            }
        }

        return $value;
    }

    /**
     * Returns prepared search criterias in text
     *
     * @return array
     */
    public function getSearchCriterias()
    {
        return $this->_searchCriterias;
    }
}
