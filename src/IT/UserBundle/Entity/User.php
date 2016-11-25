<?php
// src/IT/UserBundle/Entity/User.php

namespace IT\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="IT\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(name="first_name", type="string", length=255)
   * @Assert\NotBlank()
   */
  private $first_name;

  /**
   * @ORM\Column(name="last_name", type="string", length=255)
   * @Assert\NotBlank()
   */
  private $last_name;
}