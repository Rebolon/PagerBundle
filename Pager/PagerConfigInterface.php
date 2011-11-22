<?php

namespace Rebolon\PagerBundle\Pager;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
interface PagerConfigInterface
{
   /**
    * @param \Symfony\Component\DependencyInjection\Container $container
    */
   public function setContainer(\Symfony\Component\DependencyInjection\Container $container);
   
   /**
    * @param int $suffixName
    */
   public function setSuffixName($suffixName);
   
   /**
    * @param int $itemPerPage
    */
   public function setItemPerPage($itemPerPage);

   /**
    * @param int $maxPagerItem
    */
   public function setMaxPagerItem($maxPagerItem);
}