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
    * @see \Rebolon\PagerBundle\Pager\PagerAbstract::setSuffixName()
    */
   public function setSuffixName($suffixName);
   
   /**
    * @see \Rebolon\PagerBundle\Pager\PagerAbstract::setItemPerPage()
    */
   public function setItemPerPage($itemPerPage);

   /**
    * @see \Rebolon\PagerBundle\Pager\PagerAbstract::setMaxPagerItem()
    */
   public function setMaxPagerItem($maxPagerItem);
}