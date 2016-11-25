<?php
// src/IT/PlatformBundle/Repository/CategoryRepository.php

namespace IT\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
  public function getLikeQueryBuilder($pattern)
  {
    return $this
      ->createQueryBuilder('c')
      ->where('c.name LIKE :pattern')
      ->setParameter('pattern', $pattern)
    ;
  }
}