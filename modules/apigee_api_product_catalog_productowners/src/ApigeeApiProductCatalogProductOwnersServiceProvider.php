<?php


namespace Drupal\apigee_api_product_catalog_productowners;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

class ApigeeApiProductCatalogProductOwnersServiceProvider implements   ServiceModifierInterface {
  public function alter(ContainerBuilder $container) {
    $container
      ->getDefinition('sm_appsdashboard_apigee.appsdashboard_storage')
      ->setClass('Drupal\apigee_api_product_catalog_productowners\AppsDashboardStorageService');
    // Repeat the above for as many services as you like.
  }
}
