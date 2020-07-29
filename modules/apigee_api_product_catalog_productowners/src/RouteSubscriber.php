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


use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase{
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('apps_dashboard.list');
    if($route) {
      $route->setRequirements(['_custom_access' => "\Drupal\apigee_api_product_catalog_productowners\AppsDashboardController::access_dashboard"]);
    }
    $route = $collection->get('apps_dashboard.view');
    if($route) {
      $route->setRequirements(['_custom_access'=> "\Drupal\apigee_api_product_catalog_productowners\AppsDashboardController::access_app"]);
    }
    $route = $collection->get('apps_dashboard.edit');
    if($route) {
      $route->setRequirements(['_custom_access'=> "\Drupal\apigee_api_product_catalog_productowners\AppsDashboardController::access_app"]);
    }
  }
}
