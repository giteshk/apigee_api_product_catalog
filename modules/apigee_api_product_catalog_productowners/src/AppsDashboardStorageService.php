<?php
namespace Drupal\apigee_api_product_catalog_productowners;


use Drupal\apigee_edge\Entity\App;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;

class AppsDashboardStorageService extends \Drupal\sm_appdashboard_apigee\AppsDashboardStorageService
{
  /**
   * @param AccountInterface|null $user
   * @return array
   */
  public static function getProductsToAdminister(AccountInterface $user = null) {
    if($user == null) {
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
  public function getAllAppDetails()
  {
    $apps = parent::getAllAppDetails();
    return array_filter($apps,  function (App $app){
      return static::hasAccessToAdministerApp($app);
    });
  }
  public static function hasAccessToAdministerApp(App $app, AccountInterface $user = null){
    $allowed_api_products = static::getProductsToAdminister($user);
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
