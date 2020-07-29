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
