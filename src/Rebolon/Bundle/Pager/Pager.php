<?php

namespace Rebolon\Bundle\Pager;

use Rebolon\Component\Pager\Pager as PagerComponent;
use Rebolon\Bundle\Pager\PagerBundleConfigInterface;
use Rebolon\Bundle\Pager\Exception\NotContainerException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Translator;

/**
 * Pager optimized for Symfony2
 * converted from a personal Copix framework module
 * 
 * @author Benjamin RICHARD
 * @since 10/04/09
 */
class Pager 
    extends PagerComponent
    implements PagerBundleConfigInterface
{

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $_container;

    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $_request;

    /**
     * @var Symfony\Component\Translation\Translator
     */
    protected $_translator;

    /**
     * @see Rebolon\Bundle\Pager\PagerConfigInterface::setContainer()
     */
    public function setContainer(Container $container)
    {
        $this->_container = $container;

        $this->_request = $container->get('request');
        $this->_translator = $container->get('translator');

        return $this;
    }

    /**
     * @see Rebolon\Bundle\Pager\PagerAbstract::getCurrentURL()
     */
    protected function getCurrentURL()
    {
        return $this->_request->getRequestUri();
    }
    
    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getCurPageParam()
     */
    protected function getCurrentPageParam()
    {
        $cpp = $this->_request->query->get($this->getSuffixNameGoToPage());
        return $cpp ? : 0;
    }
    
    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getItemPerPageParam()
     */
    public function getItemPerPageParam()
    {
        $ipp = $this->_request->query->get($this->getSuffixNameItemPerPage());
        return $ipp ? : $this->_itemPerPage;
    }
    
    /**
     * @see \Rebolon\Component\Pager\PagerAbstract::getMaxPagerItemParam()
     */
    protected function getMaxPagerItemParam($totalPage)
    {
        $mpip = $this->_request->query->get($this->getSuffixNameMaxPagerItem());
        
        return $mpip ? : $this->_maxPagerItem;
    }

    /**
     * @see Rebolon\Bundle\Pager\PagerAbstract::getGoToPageURL()
     */
    protected function getGoToPageURL($page)
    {
        $queryString = http_build_query($this->addParamToQueryString($this->getSuffixNameGoToPage(), $page));
        return $this->_request->getPathInfo() . '?' . $queryString;
    }
    
    /**
     * @params string $keyGoToPage
     * @params string $valueGoToPage
     * @return array
     */
    protected function addParamToQueryString($keyGoToPage, $valueGoToPage) {
        $request = $this->_request->create(
            $this->getCurrentURL(), 'GET', array($keyGoToPage => $valueGoToPage)
        );

        return $request->query->all();
    }
    
    /**
     * @see Rebolon\PagerBundle\Pager\PagerAbstract::getTitleFirstPage()
     */
    protected function getTitleFirstPage()
    {
        return $this->_translator->trans('first');
    }

    /**
     * @see Rebolon\PagerBundle\Pager\PagerAbstract::getTitlePreviousPage()
     */
    protected function getTitlePreviousPage()
    {
        return $this->_translator->trans('previous');
    }

    /**
     * @see Rebolon\PagerBundle\Pager\PagerAbstract::getTitleNextPage()
     */
    protected function getTitleNextPage()
    {
        return $this->_translator->trans('next');
    }

    /**
     * @see Rebolon\PagerBundle\Pager\PagerAbstract::getTitleLastPage()
     */
    protected function getTitleLastPage()
    {
        return $this->_translator->trans('last');
    }

}