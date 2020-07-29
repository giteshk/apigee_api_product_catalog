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
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\sm_appdashboard_apigee\AppsDashboardStorageServiceInterface;

/**
 * Class AppsDashboardController
 * @package Drupal\apigee_api_product_catalog_productowners
 */
class AppsDashboardController extends \Drupal\sm_appdashboard_apigee\Controller\AppsDashboardController {
    /**
     * Verify if the user has access to administer the app
     *
     * @param AccountInterface $account
     * @return mixed
     */
    public function access_app(AccountInterface $account) {
    /* @var $storage_service AppsDashboardStorageServiceInterface*/
    $storage_service = \Drupal::service("sm_appsdashboard_apigee.appsdashboard_storage");
    $apptype = \Drupal::routeMatch()->getParameter("apptype");
    $appid = \Drupal::routeMatch()->getParameter("appid");
    $app = $storage_service->getAppDetailsById($apptype, $appid);
    return AccessResult::allowedIf(ProductOwnerHelper::hasAccessToAdministerApp($app, $account));
  }

    /**
     * Verify if the user has access to view the app dashboard
     * @param AccountInterface $account
     * @return mixed
     */
    public function access_dashboard(AccountInterface $account) {
    return AccessResult::allowedIf(!empty(ProductOwnerHelper::getProductsToAdminister($account)));
  }
}
