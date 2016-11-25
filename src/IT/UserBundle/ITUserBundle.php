<?php

namespace IT\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ITUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
