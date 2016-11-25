<?php
// src/IT/PlatformBundle/DoctrineListener/ApplicationCreationListener.php

namespace IT\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use IT\PlatformBundle\Email\ApplicationMailer;
use IT\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
  /**
   * @var ApplicationMailer
   */
  private $applicationMailer;

  public function __construct(ApplicationMailer $applicationMailer)
  {
    $this->applicationMailer = $applicationMailer;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    // On ne veut envoyer un email que pour les entités Application
    if (!$entity instanceof Application) {
      return;
    }

    $this->applicationMailer->sendNewNotification($entity);
  }
}
