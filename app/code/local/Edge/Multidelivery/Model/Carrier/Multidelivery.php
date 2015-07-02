<?php

class Edge_Multidelivery_Model_Carrier_Multidelivery
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'multidelivery';
    protected $_isFixed = true;

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $defaults = array(
            'enabled'       => false,
            'title'         => 'Standard',
            'description'   => '',
            'price'         => 0,
            'cost'          => 0
        );

        $result = Mage::getModel('shipping/rate_result');

        $jsonMethods = $this->getConfigData('methods');
        $methods = Mage::helper('core')->jsonDecode($jsonMethods);
        foreach ($methods as $code=>$config){

            // Merge the defaults with specific data to ensure all data is present
            $config = array_merge($defaults, $config);

            if(!$config['enabled']){
                continue;
            }

            if(isset($config['postcodes']) && isset($config['postcode_condition'])){
                if(!$this->allowMethodToPostcode($config['postcodes'], $config['postcode_condition'])){
                    continue;
                }
            }

            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier('multidelivery')
                   ->setCarrierTitle($config['title'])
                   ->setMethod($code)
                   ->setMethodTitle($config['title'])
                   ->setMethodDescription($config['description'])
                   ->setPrice($config['price'])
                   ->setCost($config['cost']);

            $result->append($method);
        }

        return $result;
    }

    protected function allowMethodToPostcode($postcodes, $condition)
    {
        $shippingPostcode = Mage::getSingleton('checkout/type_onepage')->getQuote()->getShippingAddress()->getData('postcode');
        if ($shippingPostcode){
            if($condition === 'allow'){
                foreach ($postcodes as $postcode){
                    if (strpos($shippingPostcode, $postcode) === 0){
                        return true;
                    }
                }
                return false;
            }
            elseif ($condition === 'restrict'){
                foreach ($postcodes as $postcode){
                    if (strpos($shippingPostcode, $postcode) === 0){
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    public function getAllowedMethods()
    {
        return array('multidelivery' => 'Multiple Delivery Methods');
    }
}
