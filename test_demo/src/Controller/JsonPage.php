<?php

namespace Drupal\test_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json Page extending Controller base.
 */
class JsonPage extends ControllerBase {

  /**
   * Json Page function.
   */
  public function jsonpage($api_key, $node_nid) {
    \Drupal::service('messenger')->addMessage('Came in the function');
    $serializer = \Drupal::service('serializer');
    $node = Node::load($node_nid);
    if(isset($node)) {
	    $type = $node->getEntityTypeId();
	    $bundle = $node->bundle();
    }
    $val = \Drupal::state()->get('siteapikey');// getting the api key value
    if($bundle == 'landing_page' && isset($val) && $val == $api_key) {
       $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
    }
    else {
		$data = '<p>Access Denied</p>';
    }
	 return array(
	  '#markup' => $data
	);
  }
}
