<?php

namespace AppBundle\Service\Favorite\Tests;

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class FavoriteServiceTest extends BaseTestCase
{
    public function testGetFavoriteCount()
    {
        $favorite1 = array(
            'userId' => 1,
            'knowledgeId' => 1,
            'createdTime' => '1464591741'
        );
        $favorite2 = array(
            'userId' => 1,
            'knowledgeId' => 2,
            'createdTime' => '1464591742'
        );
        $this->getFavoriteService()->createFavorite($favorite1);
        $this->getFavoriteService()->createFavorite($favorite2);
        $ids           = array(
            $favorite1['id'] = 1,
            $favorite2['id'] = 2
        );

        $count = $this->getFavoriteService()->getFavoritesCount($ids);
        $this->assertEquals(2, $count);
    }

    public function testCreateFavorite()
    {
        $likeFields = array(
            'userId' => 1,
            'knowledgeId'=>1
        );

        $like = $this->getFavoriteService()->createFavorite($likeFields);
        $this->assertEquals(1,$like['userId']);
    }

    public function testDeleteFavoriteByIdAndUserId()
    {
        $FavoriteFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            ),
            'Fields2' => array(
                'userId' => 2,
                'knowledgeId'=>2
            )
        );

        $this->getFavoriteService()->createFavorite($FavoriteFields['Fields1']);
        $this->getFavoriteService()->createFavorite($FavoriteFields['Fields2']);

        $this->getFavoriteService()->deleteFavoriteByIdAndUserId('2','2');
        $favorite2 = $this->getFavoriteDao()->get('2');

        $this->assertNull($favorite2);
    }

    public function testFindFavoriteByUserId()
    {
        $FavoriteFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            ),
            'Fields2' => array(
                'userId' => 1,
                'knowledgeId'=>2
            )
        );

        $this->getFavoriteService()->createFavorite($FavoriteFields['Fields1']);
        $this->getFavoriteService()->createFavorite($FavoriteFields['Fields2']);

        $likes = $this->getFavoriteService()->findFavoritesByUserId('1');

        $this->assertEquals(2,count($likes));
    }

    public function testHasFavoritedKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'themeId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => '1',
                'likeNum' => '1'
            ),
            'singleKnowledge2' => array(
                'id' => 2,
                'title' => '知识分享1',
                'summary' => '这是测试1',
                'type' => 'link',
                'themeId' => '1',
                'userId' => '1',
                'createdTime' => '1464591742',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => '233',
                'likeNum' => '233'
            )
        );

        $FavoriteFields = array(
            'Fields1' => array(
                'userId' => 1,
                'knowledgeId'=>1
            )
        );

        $this->getFavoriteService()->createFavorite($FavoriteFields['Fields1']);
        $hasliked = $this->getFavoriteService()->hasFavoritedKnowledge($knowledge,$userId);

        $this->assertTrue($hasliked[0]['isFavorited']);

    }
    public function testFavoriteKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'themeId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => 0,
                'likeNum' => 0
            )
        );

        $this->getKnowledgeDao()->create($knowledge['singleKnowledge1']);

        $result = $this->getFavoriteService()->favoriteKnowledge('1','1');

        $this->assertEquals(1,$result['favoriteNum']);
    }

    public function testUnfavoriteKnowledge()
    {
        $userId = '1';
        $knowledge = array(
            'singleKnowledge1' => array(
                'id' => 1,
                'title' => '知识分享',
                'summary' => '这是测试',
                'type' => 'link',
                'themeId' => '1',
                'userId' => '1',
                'createdTime' => '1464591741',
                'updatedTime' => '1470837949',
                'content' => 'www.baidu.com',
                'favoriteNum' => 0,
                'likeNum' => 1
            )
        );

        $this->getKnowledgeDao()->create($knowledge['singleKnowledge1']);

        $result = $this->getFavoriteService()->favoriteKnowledge('1','1'); 

        $result = $this->getFavoriteService()->unfavoriteKnowledge('1','1');

        $this->assertEquals(0,$result['favoriteNum']);        
    }

    protected function getFavoriteService()
    {
        return self::$kernel['favorite_service'];
    }

    protected function getFavoriteDao()
    {
        return self::$kernel['favorite_dao'];
    }

    protected function getKnowledgeDao()
    {
        return self::$kernel['knowledge_dao'];
    }
}