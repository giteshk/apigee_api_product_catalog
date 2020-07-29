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

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase{
   /**
    * Modify the access callback for the AppDashboard administration screens
    *
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
