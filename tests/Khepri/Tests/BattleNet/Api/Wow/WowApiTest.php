<?php
namespace Khepri\Tests\BattleNet\Api\Wow;

use Khepri\BattleNet\Api\Wow\WowApi;
use Khepri\Tests\TestCase;

class WowApiTest
    extends \PHPUnit_Framework_TestCase
{
    public function provideRealmData() {
        return array(
                    array('Kilrogg'),
                    array('Doomhammer')
                    );
    }
    
    public function provideCharacterData() {
        return array(
                    array('Kilrogg', 'Khepri'),
                    array('Kilrogg', 'Miguez'),
                    array('Kilrogg', 'Xanetia'),
                    array('Kilrogg', 'Mabs')
                );
    }

    public function provideGuildData() {
        return array(
                    array('Kilrogg', 'Rebel Knights'),
                    array('Kilrogg', 'Anarchy')
                );
    }
    
    public function provideItemData() {
        return array(
                    array(71053),
                    array(71054)
                );
    }
    
    /**
     * @test
     * @dataProvider provideCharacterData
     */
    public function GetCharacter($realm, $character)
    {
         $client = new WowApi(array('region'=>'eu', 'locale'=>'en_GB'));

         $result = $client->getCharacter($realm, $character,array('guild'));

         $this->assertObjectHasAttribute('name',$result);
         $this->assertEquals($character, $result->name);
         $this->assertObjectHasAttribute('realm',$result);
         $this->assertEquals($realm, $result->realm);
    }
    
    /**
     * @test
     * @dataProvider provideRealmData
     */
    public function GetRealmStatus($realm)
    {
         $client = new WowApi(array('region'=>'eu'));

         $result = $client->getRealmStatus($realm);
         $this->assertObjectHasAttribute('realms',$result);
         $this->assertEquals(count($result->realms), 1);
         $this->assertObjectHasAttribute('name',$result->realms[0]);
         $this->assertEquals($realm, $result->realms[0]->name);
    }
    
    /**
     * @test
     * @dataProvider provideGuildData
     */
    public function GetGuild($realm, $guild)
    {
         $client = new WowApi(array('region'=>'eu'));

         $result = $client->getGuild($realm, $guild,array());
         
         $this->assertObjectHasAttribute('name',$result);
         $this->assertObjectHasAttribute('realm',$result);
         $this->assertObjectHasAttribute('level',$result);
         $this->assertObjectHasAttribute('side',$result);
         $this->assertObjectHasAttribute('achievementPoints',$result);
         
         $this->assertEquals($guild, $result->name);
         $this->assertEquals($realm, $result->realm);
    }   
    
    /**
     * The functionality to request item information isn't available yet.
     * Thus we simple check if the result is null. This way when they enable it this test will fail.
     * 
     * @test
     * @dataProvider provideItemData
     */
    public function GetItem($itemId)
    {
         $client = new WowApi(array('region'=>'eu'));

         $result = $client->getItem($itemId);
         $this->assertNull($result);
    }
       
    /**
     * @test
     */
    public function GetRaces()
    {
         $client = new WowApi(array('region'=>'eu'));

         $result = $client->getRaces();
         $this->assertObjectHasAttribute('races',$result);
         $this->assertGreaterThanOrEqual(1,count($result->races));
         $this->assertObjectHasAttribute('id',$result->races[0]);
         $this->assertObjectHasAttribute('mask',$result->races[0]);
         $this->assertObjectHasAttribute('side',$result->races[0]);
         $this->assertObjectHasAttribute('name',$result->races[0]);
    }
       
    /**
     * @test
     */
    public function GetClasses()
    {
         $client = new WowApi(array('region'=>'eu'));

         $result = $client->getClasses();
         $this->assertObjectHasAttribute('classes',$result);
         $this->assertGreaterThanOrEqual(1,count($result->classes));
         $this->assertObjectHasAttribute('id',$result->classes[0]);
         $this->assertObjectHasAttribute('mask',$result->classes[0]);
         $this->assertObjectHasAttribute('powerType',$result->classes[0]);
         $this->assertObjectHasAttribute('name',$result->classes[0]);
    }
    
}