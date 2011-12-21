<?php

namespace Rebolon\PagerBundle\Pager;

use \Rebolon\PagerBundle\Pager\PagerAbstract;
use \Rebolon\PagerBundle\Pager\Exception\NotContainerException;
use \Symfony\Component\DependencyInjection\Container;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Translation\Translator;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
class Pager 
    extends PagerAbstract 
    implements PagerConfigInterface
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $_container;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $_request;

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    protected $_translator;

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerConfigInterface::setContainer()
     */
    public function setContainer(\Symfony\Component\DependencyInjection\Container $container)
    {
        $this->_container = $container;

        $this->_request = $container->get('request');
        $this->_translator = $container->get('translator');

        return $this;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerConfigInterface::setSuffixName()
     */
    public function setSuffixName($suffixName)
    {
        $this->_suffixName = !is_null($suffixName) ? $suffixName : $this->_suffixName;
        return $this;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerConfigInterface::setItemPerPage()
     */
    public function setItemPerPage($itemPerPage)
    {
        $this->_itemPerPage = !is_null($itemPerPage) ? (int) $itemPerPage : $this->_itemPerPage;
        return $this;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerConfigInterface::setMaxPagerItem()
     */
    public function setMaxPagerItem($maxPagerItem)
    {
        $this->_maxPagerItem = !is_null($maxPagerItem) ? (int) $maxPagerItem : $this->_maxPagerItem;
        return $this;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getCurPageParam()
     */
    protected function getCurrentPageParam()
    {
        return $this->_request->get($this->getSuffixNameGoToPage(), $this->_firstPage);
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getItemPerPageParam()
     */
    public function getItemPerPageParam()
    {
        return $this->_request->get($this->getSuffixNameItemPerPage(), $this->_itemPerPage);
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getMaxPagerItemParam()
     */
    protected function getMaxPagerItemParam($totalPage)
    {
        return ($tmp = $this->_request->get($this->getSuffixNameMaxPagerItem(), $this->_maxPagerItem)) > $totalPage ? $totalPage : $tmp;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getCurrentURL()
     */
    protected function getCurrentURL()
    {
        return $this->_request->getRequestUri();
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getGoToPageURL()
     */
    protected function getGoToPageURL($page)
    {
        $request = $this->_request->create(
            $this->getCurrentURL(), 'GET', array($this->getSuffixNameGoToPage() => $page)
        );

        $queryString = http_build_query($request->query->all());

        return $request->getPathInfo() . '?' . $queryString;
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getTitleFirstPage()
     */
    protected function getTitleFirstPage()
    {
        return $this->_translator->trans('first');
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getTitlePreviousPage()
     */
    protected function getTitlePreviousPage()
    {
        return $this->_translator->trans('previous');
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getTitleNextPage()
     */
    protected function getTitleNextPage()
    {
        return $this->_translator->trans('next');
    }

    /**
     * @see \Rebolon\PagerBundle\Pager\PagerAbstract::getTitleLastPage()
     */
    protected function getTitleLastPage()
    {
        return $this->_translator->trans('last');
    }

}