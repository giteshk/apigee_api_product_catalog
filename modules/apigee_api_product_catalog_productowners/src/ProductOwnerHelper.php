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

use Apigee\Edge\Structure\CredentialProduct;
use Drupal\apigee_edge\Entity\App;
use Drupal\apigee_edge\Entity\AppInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\Tests\field\Functional\Email\EmailFieldTest;

/**
 * Class ProductOwnerHelper
 * @package Drupal\apigee_api_product_catalog_productowners
 */
class ProductOwnerHelper{

    /**
     * Returns the list of Product Owners for the App
     * Returns two values
     * - 'all' - All the product owners of the API Products the app consumes
     * - 'pending' - all the product owners of the API Products pending approval on the app
     *
     * @param App $app
     * @return array
     */
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
   * Gets a list of Products that the user can administer
   *
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

    /**
     * Verify if user has access to administer any API Products on an App
     *
     * @param App $app
     * @param AccountInterface|null $user
     * @return bool
     */
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
