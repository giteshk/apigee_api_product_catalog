<?php

namespace Drupal\apigee_api_product_catalog_productowners;

use Drupal\apigee_api_product_catalog_productowners\AppsDashboardStorageService;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\sm_appdashboard_apigee\AppsDashboardStorageServiceInterface;

class AppsDashboardController extends \Drupal\sm_appdashboard_apigee\Controller\AppsDashboardController {
  public function access_app(AccountInterface $account) {
    /* @var $storage_service AppsDashboardStorageServiceInterface*/
    $storage_service = \Drupal::service("sm_appsdashboard_apigee.appsdashboard_storage");
    $apptype = \Drupal::routeMatch()->getParameter("apptype");
    $appid = \Drupal::routeMatch()->getParameter("appid");
    $app = $storage_service->getAppDetailsById($apptype, $appid);
    return AccessResult::allowedIf(AppsDashboardStorageService::hasAccessToAdministerApp($app, $account));
  }
  public function access_dashboard(AccountInterface $account) {
    return AccessResult::allowedIf(!empty(AppsDashboardStorageService::getProductsToAdminister($account)));
  }
}
