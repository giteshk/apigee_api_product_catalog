<?php

use \Drupal\apigee_edge\Entity\AppInterface;
use \Drupal\apigee_api_product_catalog_productowners\ProductOwnerHelper;

function apigee_api_product_catalog_productowners_token_info_alter(&$data){
  /** @var \Drupal\Core\Entity\EntityTypeInterface[] $apigee_entity_types */
  $apigee_entity_types = Drupal::service('apigee_edge_actions.edge_entity_type_manager')->getEntityTypes();
  foreach($apigee_entity_types as $entity_type) {
    if (in_array(AppInterface::class, class_implements($entity_type->getOriginalClass()))) {
      $token_type = $entity_type->get('token_type');
      $data['tokens'][$token_type]['product_owners'] = [
        'name' => t('All API Product Owners'),
        'description' => t("List of all the Product Owners of all API Products on this app"),
        'type' => 'string',
        'module' => 'token',
      ];
      $data['tokens'][$token_type]['product_owners_pending'] = [
        'name' => t('All API Product Approvers'),
        'description' => t('List of all API Product Owners who need to approve/reject the App'),
        'type' => 'string',
        'module' => 'token',
      ];
    }
  }
}

function apigee_api_product_catalog_productowners_tokens_alter(array &$replacements, array $context, \Drupal\Core\Render\BubbleableMetadata $bubbleable_metadata) {
  $type = $context['type'];
  $data = $context['data'];
  $tokens = $context['tokens'];
  if ($type == 'entity' && !empty($data['entity'])) {
    /* @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $data['entity'];
    if ($entity instanceof AppInterface ) {
      $owners = ProductOwnerHelper::getProductOwnersByApp($entity);
      $replacements[$tokens['field_product_owners_all']] = $owners['all'];
      $replacements[$tokens['field_product_owners_pending']] = $owners['pending'];
    }
  }
}
