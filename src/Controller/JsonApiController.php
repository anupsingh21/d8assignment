<?php

namespace Drupal\d8assignment\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\Config;

/**
 * Implementing Page JSON api dependending on siteapikey.
 */
class JsonApiController implements ContainerInjectionInterface{


/**
 * @var SerializerInterface
 */

protected $serializer;

/** 
 * @var Config
 */
protected $systemSite;

  /**
   * Constructs a new JsonApiController.
   */
  public function __construct(SerializerInterface $serializer, Config $system_site) {
    $this->serializer = $serializer;
    $this->systemSite = $system_site;
  }

  /**
   * Injecting dependency of serializer and config factory.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer'),
      $container->get('config.factory')->getEditable('system.site')
    );
  }


  /**
   * Access check for the JSON API.
   */

  public function access(AccountInterface $account, $siteapikey, NodeInterface $node) {
    $actual_siteapikey = $this->systemSite->get('siteapikey');
    $type = $node->gettype();
    return AccessResult::allowedIf(($actual_siteapikey === $siteapikey) && ($type == 'page'));
  }

  /**
   * Callback for the JSON API.
   */
  public function renderApi($siteapikey, NodeInterface $node) {
    // Serialize the json data before return.
    $output = $this->serializer->serialize([$node->gettype(), $node], 'json');
    return new JsonResponse(json_decode($output, TRUE));
  }
}