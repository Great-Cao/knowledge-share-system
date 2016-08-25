<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class ToDoListDaoTest extends BaseTestCase
{
    public function testFindToDoListByUserId()
    {
        $field1 = array(
            'userId' => 1,
            'knowledgeId' => 1
        );

        $field2 = array(
            'userId' => 1,
            'knowledgeId' => 2
        );

        $this->getToDoListDao()->create($field1);
        $this->getToDoListDao()->create($field2);

        $result = $this->getToDoListDao()->findByUserId(array(1));
        $this->assertEquals(2,count($result));        
    }

    public function testGetToDoListByFields()
    {
        $field1 = array(
            'userId' => 1,
            'knowledgeId' => 1
        );

        $field2 = array(
            'userId' => 1,
            'knowledgeId' => 2
        );

        $this->getToDoListDao()->create($field1);
        $this->getToDoListDao()->create($field2);    
        
        $result1 = $this->getToDoListDao()->getToDoListByFields($field1);
        $result2 = $this->getToDoListDao()->getToDoListByFields($field2);

        $this->assertEquals($field1['userId'],$result1['userId']); 
        $this->assertEquals($field1['knowledgeId'],$result1['knowledgeId']);  
        $this->assertEquals($field2['userId'],$result2['userId']); 
        $this->assertEquals($field2['knowledgeId'],$result2['knowledgeId']);  
    }

    protected function getToDoListDao()
    {
        return self::$kernel['todolist_dao'];
    }
}