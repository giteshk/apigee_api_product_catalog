<?php
// Copyright 2020 Google LLC
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.



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
