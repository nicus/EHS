<?php

namespace EHSBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EHSBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
