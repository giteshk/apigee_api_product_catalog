<?php

namespace Drupal\apigee_api_product_catalog_productowners;


use Drupal\apigee_edge\Entity\App;


class AppsDashboardStorageService extends \Drupal\sm_appdashboard_apigee\AppsDashboardStorageService
{

  public function getAllAppDetails()
  {
    $apps = parent::getAllAppDetails();
    return array_filter($apps,  function (App $app){
      return ProductOwnerHelper::hasAccessToAdministerApp($app);
    });
  }
}
