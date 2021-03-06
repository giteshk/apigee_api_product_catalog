<?php
/**
 * Copyright 2020 Google LLC
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * version 2 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

namespace Drupal\apigee_api_product_catalog_productowners;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceModifierInterface;

/**
 * Class ApigeeApiProductCatalogProductOwnersServiceProvider
 *
 * @package Drupal\apigee_api_product_catalog_productowners
 */
class ApigeeApiProductCatalogProductOwnersServiceProvider implements ServiceModifierInterface {

  public function alter(ContainerBuilder $container) {
    $container
      ->getDefinition('sm_appsdashboard_apigee.appsdashboard_storage')
      ->setClass('Drupal\apigee_api_product_catalog_productowners\AppsDashboardStorageService');

  }

}
