<?php

namespace Rebolon\Bundle\Pager;

use Rebolon\Component\Pager\PagerConfigInterface;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
interface PagerBundleConfigInterface extends PagerConfigInterface
{
   /**
    * @param \Symfony\Component\DependencyInjection\Container $container
    */
   public function setContainer(\Symfony\Component\DependencyInjection\Container $container);
}