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

use Drupal\apigee_api_product_catalog_productowners\ProductOwnerHelper;
use Drupal\apigee_edge\Entity\AppInterface;

/**
 * Implements hook_token_info_alter
 *
 * Add the product owner tokens for the Edge App entity
 *
 * @param $data
 */
function apigee_api_product_catalog_productowners_token_info_alter(&$data) {
  /** @var \Drupal\Core\Entity\EntityTypeInterface[] $apigee_entity_types */
  $apigee_entity_types = Drupal::service('apigee_edge_actions.edge_entity_type_manager')
    ->getEntityTypes();
  foreach ($apigee_entity_types as $entity_type) {
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

/**
 * Implements hook_tokens_alter
 *
 * Write the values for the Product owner tokens
 *
 * @param array $replacements
 * @param array $context
 * @param \Drupal\Core\Render\BubbleableMetadata $bubbleable_metadata
 */
function apigee_api_product_catalog_productowners_tokens_alter(array &$replacements, array $context, \Drupal\Core\Render\BubbleableMetadata $bubbleable_metadata) {
  $type = $context['type'];
  $data = $context['data'];
  $tokens = $context['tokens'];
  if ($type == 'entity' && !empty($data['entity'])) {
    /* @var \Drupal\Core\Entity\ContentEntityInterface $entity */
    $entity = $data['entity'];
    if ($entity instanceof AppInterface) {
      $owners = ProductOwnerHelper::getProductOwnersByApp($entity);
      $replacements[$tokens['product_owners']] = $owners['all'];
      $replacements[$tokens['product_owners_pending']] = $owners['pending'];
    }
  }
}
