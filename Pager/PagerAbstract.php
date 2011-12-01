<?php

namespace Rebolon\PagerBundle\Pager;

use \Rebolon\PagerBundle\Pager\Exception\LastPageNotSetException;
use \Rebolon\PagerBundle\Pager\Exception\CurPageNotSetException;
use \Rebolon\PagerBundle\Pager\Exception\TotalPageNotSetException;
use \Rebolon\PagerBundle\Pager\Exception\FirstPageNotSetException;

/**
 * Pager abstraction layer (for php5.3)
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
abstract class PagerAbstract 
    implements PagerInterface, PagerTplInterface
{
    // param name
    const _PARAM_SUFFIXNAME_ = 'suffixName';
    const _PARAM_ITEMPERPAGE_ = 'itemPerPage';
    const _PARAM_MAXPAGERITEM_ = 'maxPagerItem';

    // suffix of get parameters sent throught url request from page to page
    const _SUFFIX_LIST_ = 'myPager_'; // suffix for list id
    const _SUFFIX_GOTOPAGE_ = 'gtp_'; // suffix for page to display
    const _SUFFIX_ITEMPERPAGE_ = 'ipp_'; // suffix for nb of item per page
    const _SUFFIX_MAXPAGERITEM_ = 'mpi_'; // suffix for nb of item of the pager

    /**
     * @var string name of the pager
     */
    protected $_suffixName = 'pager';

    /**
     * @var int nb of item per page
     */
    protected $_itemPerPage = 5;

    /**
     * @var int nb of item of the pager
     */
    protected $_maxPagerItem = 5;

    /**
     * @var int index of the first page
     */
    protected $_firstPage = 0;
    
    /**
     * @var int nb of page available
     */
    protected $_totalPage;

    /**
     * @var int index of the last page
     */
    protected $_lastPage;

    /**
     * @var int index of previous page
     */
    protected $_previousPage;

    /**
     * @var int index of next page
     */
    protected $_nextPage;

    /**
     * @var index of current page
     */
    protected $_curPage;

    /**
     * @var array list of index around current page 
     * (used to build pager  << < x y z > >> )
     */
    protected $_itemList;
    
    /**
     * the list counter that allow template to know if an item must be displayed
     * or not
     * @var int 
     */
    protected $_displayListCounter;

    /**
     * 
     * @param int $totalItem
     * @param array $options array for following parameters init° suffixName/itemPerPage/maxPagerItem
     * @return void
     */
    final public function __construct($totalItem=null, array $options = null)
    {
        if (!is_null($options)) {
            $this->setConfig($options);
        }

        if (!is_null($totalItem)) {
            $this->init($totalItem);
        }
    }

    /**
     * Change the value of properties suffixName, itemPerPage, maxPagerItem from 
     * the pager. It allows to use multiple pager on the same page (inside article,
     * widget per example...)
     * @param array $aArgs, array('suffixName'=>, 'itemPerPage'=>, 'maxPagerItem'=>)
     * @return PagerConfigInterface
     * 
     * @deprecated with symfony i use dependancy injection, so setter are called
     * automatically and i don't need setConfig
     */
    final protected function setConfig($options)
    {
        if (!is_array($options) || !array_key_exists(self::_PARAM_SUFFIXNAME_, $options))
            $options[self::_PARAM_SUFFIXNAME_] = null;

        if (!is_array($options) || !array_key_exists(self::_PARAM_ITEMPERPAGE_, $options))
            $options[self::_PARAM_ITEMPERPAGE_] = null;

        if (!is_array($options) || !array_key_exists(self::_PARAM_MAXPAGERITEM_, $options))
            $options[self::_PARAM_MAXPAGERITEM_] = null;

        $this->setSuffixName($options[self::_PARAM_SUFFIXNAME_]);
        $this->setItemPerPage($options[self::_PARAM_ITEMPERPAGE_]);
        $this->setMaxPagerItem($options[self::_PARAM_MAXPAGERITEM_]);
        return $this;
    }

    /**
     * 
     * @see \Rebolon\PagerBundle\Pager\PagerInterface::init()
     */
    final public function init($totalItem)
    {
        $this
            ->setTotalPage($totalItem)
            ->setFirstPage(0)
            ->doCalcLastPage()
            ->doCalcCurrentPage()
            ->doCalcPreviousPage()
            ->doCalcNextPage()
            ->doCreateItemList()
        ;
    }

    /**
     * 
     * @param int $totalpage 
     */
    final protected function setTotalPage($totalItem)
    {
        $this->_totalPage = ceil($totalItem / $this->getItemPerPageParam());
        return $this;
    }
    
    /**
     * 
     * @param int $firstPage
     */
    final protected function setFirstPage($firstPage)
    {
        $this->_firstPage = !is_null($firstPage) ? (int) $firstPage : $this->_firstPage;
        return $this;
    }

    /**
     * @todo lastPage depends on firstPage and totalPage => there must be events to modify this property when one of those are modified
     * 
     * @param int $firstpage 
     */
    final protected function doCalcLastPage()
    {
        if (is_null($this->_totalPage))
            throw new TotalPageNotSetException;
        if (is_null($this->_firstPage))
            throw new FirstPageNotSetException;

        $this->_lastPage = $this->_totalPage - 1 + $this->_firstPage;
        return $this;
    }

    /**
     * @todo _curPage depends on firstPage and lastPage => there must be events to modify this property when one of those are modified
     * 
     * @param int $firstpage 
     */
    final protected function doCalcCurrentPage()
    {
        if (is_null($this->_lastPage))
            throw new LastPageNotSetException;
        if (is_null($this->_firstPage))
            throw new FirstPageNotSetException;

        $curPage = $this->getCurrentPageParam();

        $this->_curPage = ($curPage < $this->_firstPage) ?
            $this->_firstPage : (
            ($curPage > $this->_lastPage) ? $this->_lastPage : $curPage);

        return $this;
    }

    /**
     * @todo _previousPage depends on firstPage and curPage => there must be events to modify this property when one of those are modified
     * 
     * @param int $firstpage 
     */
    final protected function doCalcPreviousPage()
    {
        if (is_null($this->_curPage))
            throw new CurPageNotSetException;
        if (is_null($this->_firstPage))
            throw new FirstPageNotSetException;

        $this->_previousPage = ($tmp = $this->_curPage - 1) >= $this->_firstPage ?
            $tmp : $this->_firstPage;

        return $this;
    }

    /**
     * @todo _nextPage depends on curPage and lastPage => there must be events to modify this property when one of those are modified
     * 
     * @param int $firstpage 
     */
    final protected function doCalcNextPage()
    {
        if (is_null($this->_lastPage))
            throw new LastPageNotSetException;
        if (is_null($this->_curPage))
            throw new CurPageNotSetException;

        $this->_nextPage = ($tmp = $this->_curPage + 1) <= $this->_lastPage ?
            $tmp : $this->_lastPage;

        return $this;
    }

    /**
     * @todo _itemList depends on firstPage, lastPage, curPage, totalPage => there must be events to modify this property when one of those are modified
     * 
     * @param int $firstpage 
     */
    final protected function doCreateItemList()
    {
        if (is_null($this->_lastPage))
            throw new LastPageNotSetException;
        if (is_null($this->_curPage))
            throw new CurPageNotSetException;
        if (is_null($this->_totalPage))
            throw new TotalPageNotSetException;
        if (is_null($this->_firstPage))
            throw new FirstPageNotSetException;
$logger = $this->_container->get('logger');
$logger->info('$totalPage: ' . $this->_totalPage);
$logger->info('$itemPerPage: ' . $this->_itemPerPage);
$logger->info('$maxPagerItem: ' . $this->_maxPagerItem);
        $maxItemPerPage =
            ($this->getMaxPagerItemParam($this->_totalPage) % 2) == 0 ?
            $this->getMaxPagerItemParam($this->_totalPage) :
            $this->getMaxPagerItemParam($this->_totalPage) + 1;
$logger->info('$maxItemPerPage: ' . $maxItemPerPage);
        $nbItemOnSide = floor($maxItemPerPage / 2);
$logger->info('$nbItemOnSide: ' . $nbItemOnSide);
        if ($this->_curPage <= $nbItemOnSide) {
            $startOffset = $this->_firstPage;
$logger->info('$startOffset case 1');
        } elseif (($this->_lastPage - $this->_curPage) <= $nbItemOnSide) {
            $startOffset =
                ($tmp = ($this->_lastPage - $maxItemPerPage + 1)) <= $this->_firstPage ?
                $this->_firstPage : $tmp;
$logger->info('$startOffset case 2');
        } else {
            $startOffset = $this->_curPage - $nbItemOnSide;
$logger->info('$startOffset case 3');
        }
$logger->info('$startOffset: ' . $startOffset);
        $endOffset = ($tmp = $startOffset + ($maxItemPerPage - 1)) > $this->_lastPage ?
            $this->_lastPage : $tmp;
$logger->info('$endOffset: ' . $endOffset);
        $this->_itemList = array();
        for ($i = $startOffset; $i <= $endOffset; $i++) {
$logger->info('$i: ' . $i);
            $this->_itemList[] = (int) $i;
        }

        return $this;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerTplInterface::buildPager()
     */
    final public function buildPager()
    {
        $pagerItem[] = array('uri' => $this->getGoToPageURL($this->getFirstPage()),
            'label' => '<<',
            'title' => $this->getTitleFirstPage());
        $pagerItem[] = array('uri' => $this->getGoToPageURL($this->getPreviousPage()),
            'label' => '<',
            'title' => $this->getTitlePreviousPage());

        if (is_array($this->getItemList())) {
            foreach ($this->getItemList() as $item) {
                $pagerItem[] = array('uri' => (
                    ($item != $this->getCurrentPage()) ?
                        static::getGoToPageURL($item) : null),
                    'label' => $item+1,
                    'title' => $item+1);
            }
        }

        $pagerItem[] = array('uri' => $this->getGoToPageURL($this->getNextPage()),
            'label' => '>',
            'title' => $this->getTitleNextPage());
        $pagerItem[] = array('uri' => $this->getGoToPageURL($this->getLastPage()),
            'label' => '>>',
            'title' => $this->getTitleLastPage());

        return $pagerItem;
    }
    
    /**
     * @see \Rebolon\PagerBundle\Pager\PagerInterface::isCurrentPage()
     */
    public function isCurrentPage($pageId)
    {
        $toTest = (int)$pageId;
        if ($toTest !== $pageId) {
            return false;
        }
        return $pageId == $this->getCurrentPage()+1;
    }

    /**
     * Retreive index of current page from request vars (POST, GET, or a default value)
     * @return int
     */
    abstract protected function getCurrentPageParam();

    /**
     * Retreive nb of max item in pager for the page from request vars (POST, 
     * GET, or default ais value of property _maxPagerItem)
     * @param int $totalPage
     * @return int
     */
    abstract protected function getMaxPagerItemParam($totalPage);

    /**
     * Return value of $_SERVER['SCRIPT_NAME']
     * @return string
     */
    abstract protected function getCurrentURL();

    /**
     * Build the the goToPage Url for the pager
     * @param int $page, index of page
     * @return string
     */
    abstract protected function getGoToPageURL($page);

    /**
     * Return title for the first page item
     * @return string 
     */
    abstract protected function getTitleFirstPage();

    /**
     * Return title for the previous page item
     * @return string 
     */
    abstract protected function getTitlePreviousPage();

    /**
     * Return title for the next page item
     * @return string 
     */
    abstract protected function getTitleNextPage();

    /**
     * Return title for the last page item
     * @return string 
     */
    abstract protected function getTitleLastPage();

    /**
     * Return pager name for param 'gotopage'
     * @return string
     */
    final protected function getSuffixNameGoToPage()
    {
        return static::_SUFFIX_GOTOPAGE_ . $this->_suffixName;
    }

    /**
     * Return pager name for param 'itemperpage'
     * @return string
     */
    final protected function getSuffixNameItemPerPage()
    {
        return static::_SUFFIX_ITEMPERPAGE_ . $this->_suffixName;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerTplInterface::getSuffixName()
     */
    final public function getSuffixName()
    {
        return static::_SUFFIX_LIST_ . $this->_suffixName;
    }

    /**
     * Return pager name for param 'maxpageritem'
     * @return string
     */
    final protected function getSuffixNameMaxPagerItem()
    {
        return static::_SUFFIX_MAXPAGERITEM_ . $this->_suffixName;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerInterface::getOffset()
     */
    public function getOffset()
    {
        return (int) $this->getCurrentPage() * $this->getItemPerPageParam();
    }

    /**
     * @see \Rebolon\PagerBundle\PagerTplInterface::getTotalPage()
     */
    public function getTotalPage()
    {
        return (int) $this->_totalPage;
    }

    /**
     * Return the property firstPage
     * @return int
     */
    public function getFirstPage()
    {
        return (int) $this->_firstPage;
    }

    /**
     * Return the property lastPage
     * @return int
     */
    public function getLastPage()
    {
        return (int) $this->_lastPage;
    }

    /**
     * Return the property previousPage
     * @return int
     */
    public function getPreviousPage()
    {
        return (int) $this->_previousPage;
    }

    /**
     * Return the property nextPage
     * @return int
     */
    public function getNextPage()
    {
        return (int) $this->_nextPage;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerTplInterface::getTotalPage()
     */
    public function getCurrentPage()
    {
        return (int) $this->_curPage;
    }
    
    /**
     * 
     * @see \Rebolon\PagerBundle\Pager\PagerTplInterface::isToDisplay()
     */
    public function isToDisplay($itemIndex)
    {
        $isToDisplay = false;
        
        if (is_null($this->_displayListCounter)) {
            $this->_displayListCounter = $this->getItemPerPageParam();
        }

        if ($itemIndex > $this->getOffset() && $this->_displayListCounter > 0) {
            $isToDisplay = true;
            $this->_displayListCounter--;
        }
        
        return $isToDisplay;
    }

    /**
     * Return the property itemList
     * @return array
     */
    public function getItemList()
    {
        return $this->_itemList;
    }
    
    /**
     *
     * @return string
     */
    public function __toString()
    {
        $string = 
            'nb page: ' . $this->getTotalPage() . chr(10)
            . 'first page: ' . $this->getFirstPage() . chr(10)
            . 'previous page: ' . $this->getPreviousPage() . chr(10)
            . 'current page: ' . $this->getCurrentPage() . chr(10)
            . 'next page: ' . $this->getNextPage() . chr(10)
            . 'last page: ' . $this->getLastPage() . chr(10)
            ;
        return $string;
    }

}