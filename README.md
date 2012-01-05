README
======

Here is a simple Pager for Symfony2

[![Build Status](https://secure.travis-ci.org/Rebolon/PagerBundle.png)] 
(http://travis-ci.org/#!/Rebolon/PagerBundle)

Why do you need a pager ?
Because you have a too many item to display on a page. But there is two possibility :
 * You want to retreive only items to display. In that case the pager must be able to display those items and create the pager caracteristic
 * You have already retreived a huge list (from a web service). In that case the pager must display only items of the current page, and also the pager caracteristic

For the first case, my pager can help you to get limit and offset value to allow you to retreive only wanted data.

Features
--------

 * Twig template support (classic and twitter boostrap template)
 * Easy to customize and extend 

Installation
------------
Add the namespaces to your autoloader in the app/autoload.php :

	``` php
	'Rebolon'          => __DIR__.'/../src',
	```

Add PaginatorBundle to your application kernel in the app/AppKernel.php :

	``` php
	new Rebolon\PagerBundle\RebolonPagerBundle(),
	```
 
Sample
------

All you need is to know how many data the pager may manage (the totalItem).
By default the pager has 5 items inside (1 to 5), and manage 15 items in the list that you want to display.
So you can use the container to simply use the pager : 

	``` php
	$this->get('rebolon_pager.pager');
	```
	
There is a sample in the default controller with route rebolon/pager/test

You can also configure your own Pager for all your bundle, simply add this to 
the services.xml of your bundle :

	``` xml
    <service id="mybundle.pager" class="%rebolon_pager.pager.class%">
        <call method="setContainer">
             <argument type="service" id="service_container" />
        </call>
        <call method="setSuffixName">
             <argument>pagerForWS</argument>
        </call>
        <call method="setItemPerPage">
             <argument>5</argument>
        </call>
        <call method="setMaxPagerItem">
             <argument>3</argument>
        </call>
    </service>
	```
	
To get your offset/limit values, use the methods specified in PagerInterface :
 * first you need to do a count(*) on the list you want to display. Then give the value to the init method of the pager.
 * use the methods getOffset(), and getItemPerPageParam() to retreive offset and limit values for your query
 
Inside the template, the pager gives you anything you need to create display your pager :
 * buildPager will create the pager item list (first/prev/X...Y/next/last). It returns an array of associative array which keys are uri/label/title.
 * isCurrentPage($pageId) allow you to identity if item your looping into is the currentPage
 * isToDisplay($itemIndex) allow you to know if an item of the list must be displayed or not
Have a look at the PagerTplInterface for others methods.

There is few twig template to illustrate the possibility of the pager :
 * standard is a classic pager
 * twt-bs is a pager that use bootstrap twitter css framework
 * test and tewtWithLargeList are used by the demo controller of the bundle. They are samples for first and second case i spoke about at the begining of the README
 
Have fun with it !
 