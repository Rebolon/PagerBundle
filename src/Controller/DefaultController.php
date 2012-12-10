<?php

namespace Rebolon\Bundle\Pager\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * 
     * Default action which do a sample usage of pager
     * 
     * @Route("/rebolon/pager/test", name="rebolon_pager_test")
     */
    public function indexAction()
    {
        $countFake = 55;
        $pager = $this->get('rebolon_pager.pager');
        $pager->init($countFake);
        
        $data = $this->getList(
            $countFake, $pager->getOffset(), $pager->getItemPerPageParam()
            );

        return $this->render('Rebolon\\Bundle\\Pager:Default:test.html.twig',
            array('data'=>$data, 'pager'=>$pager)
            );
    }
    
    /**
     * 
     * Default action which do a sample usage of pager
     * 
     * @Route("/rebolon/pager/testWithLargeList", name="rebolon_pager_testWithLargeList")
     */
    public function testWithLargeListAction()
    {
        $countFake = 55;
        $pager = $this->get('rebolon_pager.pager');
        $pager->init($countFake);
        
        $data = $this->getLargeList(
            $countFake
            );

        return $this->render('Rebolon\\Bundle\\Pager:Default:testWithLargeList.html.twig',
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
     * Build a large list fixtures
     * @param int nbWished
     * @param int offset
     * @param int npp
     * @return array
     */
	private function getLargeList($nbWished)
	{
		$aList = array() ;
		for( $i=0 ; $i<$nbWished ; $i++ )
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
