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
    return AccessResult::allowedIf(ProductOwnerHelper::hasAccessToAdministerApp($app, $account));
  }
  public function access_dashboard(AccountInterface $account) {
    return AccessResult::allowedIf(!empty(ProductOwnerHelper::getProductsToAdminister($account)));
  }
}
