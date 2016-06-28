<?php
namespace ScnSocialAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Loader\StandardAutoloader;
use Hybrid_Endpoint;

class HybridAuthController extends AbstractActionController
{
    public function indexAction()
    {
      Hybrid_Endpoint::process();
    }
}
