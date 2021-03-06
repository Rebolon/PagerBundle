<?php

namespace Rebolon\Bundle\Pager\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\Container;
use Rebolon\Component\Pager\PagerTplInterface;

class PagerExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'pagerZone' => new \Twig_Function_Method($this, 'getPager', array('is_safe' => array('html'))),
        );
    }
    
    /**
     * @param \Rebolon\Component\Pager\PagerTplInterface $pager
     */
    public function getPager(PagerTplInterface $pager, $template = null)
    {
        /**
         * @todo check if tempalte exists
         */
        if (empty($template)) {
            $template = 'RebolonPagerBundle:Default:standard.html.twig';
        }
        
        return $this->container->get('templating')->render($template, array('pager'=>$pager));
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'pager';
    }
}
