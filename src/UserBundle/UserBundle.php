<?php

namespace UserBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UserBundle\DependencyInjection\Compiler\FOSUserOverridePass;

class UserBundle extends Bundle
{

    //prevent login after register
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FOSUserOverridePass());
    }
}
