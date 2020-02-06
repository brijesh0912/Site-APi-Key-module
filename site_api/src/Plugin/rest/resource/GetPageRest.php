<?php

namespace Drupal\site_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "site_api",
 *   label = @Translation("Site Api page Json"),
 *   uri_paths = {
 *     "canonical" = "page_json/{siteapikey}/{nid}"
 *   }
 * )
 */
class GetPageRest extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($siteapikey = NULL, $nid = NULL) {

    if ($siteapikey && $nid) {
      $nids = \Drupal::entityQuery('node')
        ->condition('type', 'page')
        ->condition('nid', $nid)
        ->execute();

      //$config = \Drupal::config('welcome_alert.adminsettings');
      $site_config = \Drupal::config('system.site');
      $ApiKey = $site_config->get('siteapikey');

      if ($nids && ($siteapikey == $ApiKey)){
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
      }
      else {
        throw new AccessDeniedHttpException('access denied');
      }
    }
    $response = new ResourceResponse($nodes);
    // In order to generate fresh result every time (without clearing
    // the cache), you need to invalidate the cache.
    $response->addCacheableDependency($nodes);
    return $response;
  }

}
