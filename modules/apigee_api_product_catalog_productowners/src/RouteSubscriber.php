<?php

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
