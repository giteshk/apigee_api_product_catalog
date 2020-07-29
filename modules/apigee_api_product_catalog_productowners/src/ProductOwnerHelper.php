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

use Apigee\Edge\Structure\CredentialProduct;
use Drupal\apigee_edge\Entity\App;
use Drupal\apigee_edge\Entity\AppInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\Tests\field\Functional\Email\EmailFieldTest;

class ProductOwnerHelper{

  public static function getProductOwnersByApp(App $app) {
    $products = [];
    foreach($app->getCredentials() as $credential) {
      foreach($credential->getApiProducts() as $product) {
        if($product->getStatus() == CredentialProduct::STATUS_PENDING) {
          $products['pending'][$product->getApiproduct()] = $product->getApiproduct();
        }
        $products['all'][$product->getApiproduct()] = $product->getApiproduct();
      }
    }
    $owners = [];
    if(!empty($products)) {
      $nids = \Drupal::entityQuery('node')
        ->condition('type', 'api_product')
        ->condition( 'field_apigee_api_product', $products)
        ->execute();
      $nodes = Node::loadMultiple($nids);
      foreach($nodes as $node){
        $product_name = $node->get("field_apigee_api_product")->getString();
        /* @var $owner \Drupal\Core\Field\Plugin\Field\FieldType\EmailItem */
        foreach($node->get("field_product_owners") as $owner) {
          $owners['all'][$owner->getString()] = $owner->getString();
          if(in_array($product_name, $products['pending'])) {
            $owners['pending'][$owner->getString()] = $owner->getString();
          }
        }
      }
    }
    return $owners;
  }

  /**
   * @param AccountInterface|null $user
   * @return array
   */
  public static function getProductsToAdminister(AccountInterface $user = null) {
    if($user === null) {
      $user = \Drupal::currentUser();
    }
    $cid = "AppDashboardProductOwnerItems:" . $user->id();
    $cache = \Drupal::cache()->get($cid);
    $api_products = [];
    if ($cache) {
      $api_products = $cache->data;
    } else {
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'api_product');
      if(!$user->hasPermission("manage appdashboard")) {
        $query = $query->
        condition("field_product_owners", strtolower($user->getEmail()));
      }
      $nids = $query->execute();
      $nodes = Node::loadMultiple($nids);
      $tags = ["apigee_api_product_catalog_productowners:{$user->getEmail()}"];
      foreach ($nodes as $node) {
        $product_name = $node->get("field_apigee_api_product")->getString();
        $api_products[$node->id()] = $product_name;
        $tags = Cache::mergeTags($tags, $node->getCacheTags());
      }
      \Drupal::cache()->set($cid, $api_products, Cache::PERMANENT, $tags);
    }
    return $api_products;
  }

  public static function hasAccessToAdministerApp(App $app, AccountInterface $user = null){
    $allowed_api_products = ProductOwnerHelper::getProductsToAdminister($user);
    foreach($app->getCredentials() as $credential){
      foreach($credential->getApiProducts() as $credentialProduct){
        if(in_array($credentialProduct->getApiproduct(), $allowed_api_products)) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }
}
