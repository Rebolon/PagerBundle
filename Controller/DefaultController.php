<?php

namespace Rebolon\PagerBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * 
     * Default action which do a sample usage of pager
     * 
     * @Route("/rebolon/pager/test/", name="rebolon_pager_test")
     */
    public function indexAction()
    {
        $countFake = 10;
        $pager = $this->get('rebolon_pager.pager');
        $pager->init($countFake);
        
        $data = $this->getList(
            $countFake, $pager->getOffset(), $pager->getItemPerPageParam()
            );

        return $this->render('RebolonPagerBundle:Default:test.html.twig',
            array('data'=>$data, 'pager'=>$pager)
            );
    }
    	
    /**
     * Build list fixtures
     * @param int nbWished
     * @param int offset
     * @param int npp
     * @return array
     */
	private function getList($nbWished, $offset, $npp)
	{
		$aList = array() ;
		for( $i=0 ; $i<$nbWished ; $i++ )
			if( $i >= $offset && $npp-- > 0 )
				$aList[] = static::getStdClassObject($i) ;
			
		return $aList ;
	}
	
	/**
     * Build fixtures
     * @param int $id
     * @return stdClass
     */
	static private function getStdClassObject($id)
	{
		$o = new \stdClass ;
		$o->id = $id ;
		$o->title = 'titre de mon objet n'.$id ;
		$o->desc = 'description de mon objet' ;
		return $o ;
	}
}
