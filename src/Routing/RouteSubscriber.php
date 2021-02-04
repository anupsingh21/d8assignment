<?php

namespace Drupal\d8assignment\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * Alter the rount to call the extended version of SiteInformationForm with additional fields.
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('system.site_information_settings')) {
      $route->setDefault('_form', '\Drupal\d8assignment\Form\SiteInformationForm');
    }
  }

}