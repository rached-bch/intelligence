<?php

// src/IT/PlatformBundle/Controller/IndexController.php

namespace IT\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
  public function indexAction()
  {
    // On donne toutes les informations nécessaires à la vue
    return $this->render('ITPlatformBundle:Default:index.html.twig');
  }

  public function menuAction()
  {
    return $this->render('ITPlatformBundle:Default:menu.html.twig', array(
    ));
  }
}
