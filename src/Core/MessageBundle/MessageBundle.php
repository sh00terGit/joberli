<?php

namespace Core\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MessageBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
