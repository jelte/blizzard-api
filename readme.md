PHP Client for Blizzard Web Api
===============================

Overview
--------
This is a PHP client for Blizzard's Battle.net Web API.

Requirements
------------
* PHP 5.3

Quickstart
----------
If you are working with PHP 5.3, you are hopefully working with some form of autoloader.
If not see the example.php how to easily add autoloading to your project.

Once you have added the library to your autoloader you can create a new instance 
of te WowApi class.

	<?php
	include 'sdk/WowAPI.php';
	$wowApi = new WowApi();

### Realm Status
To get a list of all realms you can use the following code.

	$wowApi->getRealmStatus();
To get the status of a specific realm you will need to use the following code.
The `getRealmStatus` function takes a a string as its argument. 

	$api->Realm->getRealmStatus($realmname);
	
### Character Information
To get a characters information

	$wowApi->getCharacter($realm, $name[, $fields])
This will return a stdClass. 

	$wowApi->getCharacter($realm, $name[, $fields[, true]])
Or use this to recieve associative arrays
(Note: this works on every call)

### Guild Information
To get guild information
	
	$wowApi->getGuild($realm, $name, $fields)
To get all guild members aswell

	$wowApi->getGuild($realm, $name, array('members'))

### Item Information
This hasn't been implemented by Blizzard yet, but is in their examples already.

	$wowApi->getItem($itemid);

Caching
--------

Currently there is no caching interface included in this source. But it is supported.
To keep depenancies low, caching interfaces will be included, but for now you can use Doctrine Caching

Use Doctrine Caching
^^^^^^^^^^^^^^^^^^^^^

to make use of the Doctrine Caching you will need to autoload doctrine-common
and change AbstractApi:24

	use Khepri\BattleNet\Cache\Cache;
to

	use Doctrine\Common\Cache\Cache;
Once you have changed this line you will be able to use any of the Doctrine Caching interfaces:

	$cache = new \Doctrine\Common\Cache\ApcCache();
	$wowApi = new WowApi(array('region'=>'eu', 'cache'=>$cache));
or

	$cache = new \Doctrine\Common\Cache\ApcCache();
	$wowApi = new WowApi(array('region'=>'eu'));
	$wowApi->setCache($cache);	

Todo
----

- Have the Api return Call specific responses. 
when calling getGuild you should get a Guild object as response.
when calling getItem you should get a Item object as response
- Add more error handling.
Currently only the "correct" usage of the api is supported.
- Add Caching interface
- Add more unit tests

Contributing Developers
-----------------------

Adding new calls is relatively easy.

First you will need to create a new <Resource>Call class that extends AbstractCall.
The main attribute you will need to change is "_path". This defines what url will be called.

Once you have your Call class setup you can request it.
There are 2 ways to request something.
First you can append the <Game>Api Class with your new <Resource>Call 
or you can request the call a little bit more manually

	$wowApi->request(new <Recourse>Call())->getData();
The request method is public for this reason.   