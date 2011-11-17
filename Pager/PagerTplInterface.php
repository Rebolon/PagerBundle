<?php

namespace Rebolon\PagerBundle\Pager;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
interface PagerTplInterface
{
   /**
    * Calculate all values of the pager and return an array of array(uri, label, title)
    *   label is a default value that can be used in template for standard display
    * @return array $pagerItem
    */
   public function buildPager();
   
   /**
    * Return pager name
    * @return string
    */
   public function getSuffixName();
   
   /**
    * Return true if the pageId is the current page
    * @return bool
    */
   public function isCurrentPage($pageId);
   
   /**
     * Return the total page number
     * @return int
     */
    public function getTotalPage();
    
    /**
     * Return the current page number
     * @return int
     */
    public function getCurrentPage();
}