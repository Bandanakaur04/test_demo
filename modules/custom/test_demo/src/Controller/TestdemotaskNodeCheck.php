<?php

/**
 * @file
 * Contains \Drupal\test_demo\Controller\CustomNodeCheck
 * test_demo menu to check siteapikey and node id (/nodecheck/{key}/{nid})
 */
namespace Drupal\test_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\HeaderBag;

/**
 * Provides route responses for the Example module.
 */
class TestdemotaskNodeCheck extends ControllerBase {


  public function nodecheck($key,$nid) {

    $output = array(
      'status' => false,
      'data' => '',
    );
    $path = \Drupal::request()->getpathInfo();
    $arg  = explode('/',$path);
    $key = '';
    if( false == empty( $arg[2] ) ) {
      $key = $arg[2];
      $siteapikey = \Drupal::config('test_demo.settings')->get('siteapikey');
      if ( $key != $siteapikey ) {
        //access denied 
        $output['data'] = 'Access Denied';
        return new JsonResponse($output);
      }

    } 
    $nid = $arg[3];
    $node = \Drupal\node\Entity\Node::load($nid);
    if( true == empty( $node ) ) {
      //not a node 
      $output['data'] = 'not a node';
      $output['status'] = false;

    } else {
      $serializer = \Drupal::service('serializer');
      //$node = Node::load(2);
      $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
      $output['data'] = $data;
      $output['status'] = true;

    }

    return new JsonResponse($output);
  }

}



