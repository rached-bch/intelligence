<?php
// src/IT/PlatformBundle/Validator/Antiflood.php

namespace IT\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint
{
  public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, merci d'attendre un peu.";

  public function validatedBy()
  {
    return 'it_platform_antiflood'; // Ici, on fait appel à l'alias du service
  }
}