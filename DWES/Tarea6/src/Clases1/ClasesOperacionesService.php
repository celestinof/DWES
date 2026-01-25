<?php

namespace Clases1;

class ClasesOperacionesService extends \SoapClient
{

 /**
  * @var array $classmap The defined classes
  */
 private static $classmap = array (
);

 /**
  * @param array $options A array of config values
  * @param string $wsdl The wsdl file to use
  */
 public function __construct(array $options = array(), $wsdl = null)
 {
 
  foreach (self::$classmap as $key => $value) {
    if (!isset($options['classmap'][$key])) {
      $options['classmap'][$key] = $value;
    }
  }
   $options = array_merge(array (
  'features' => 1,
), $options);
   if (!$wsdl) {
     $wsdl = '../servidorSoap/servicio.wsdl';
   }
   parent::__construct($wsdl, $options);
 }

 /**
  * Devuelve el precio de un producto
  *
  * @param int $codigoP
  * @return float
  */
 public function getPvp($codigoP)
 {
   return $this->__soapCall('getPvp', array($codigoP));
 }

 /**
  * Devuelve las unidades de un producto en una tienda
  *
  * @param int $codigoP
  * @param int $codigoT
  * @return int
  */
 public function getStock($codigoP, $codigoT)
 {
   return $this->__soapCall('getStock', array($codigoP, $codigoT));
 }

 /**
  * Devuelve un array con los códigos de todas las familias
  *
  * @return Array
  */
 public function getFamilias()
 {
   return $this->__soapCall('getFamilias', array());
 }

 /**
  * Devuelve los códigos de productos de una familia
  *
  * @param string $codigoF
  * @return Array
  */
 public function getProductosFamilia($codigoF)
 {
   return $this->__soapCall('getProductosFamilia', array($codigoF));
 }

}
