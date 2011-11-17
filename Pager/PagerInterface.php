<?php

namespace Rebolon\PagerBundle\Pager;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
interface PagerInterface
{
   /**
    * Calculate all values of the pager.
    * @param $totalItem, nombre d'item de contenu
    * @return \Rebolon\PagerBundle\Pager\PagerAbstract
    */
   public function init($totalItem);
   
   /**
    * Retreive nb of item to display for the page from request vars (POST, GET, 
    * or default is value of property _itemPerPage)
    * @return int
    */
   public function getItemPerPageParam();
   
   /**
    * Calculate the offset of the content to display
    * @return int
    */
   public function getOffset();
}