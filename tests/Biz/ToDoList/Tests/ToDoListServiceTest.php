<?php

use Codeages\Biz\Framework\UnitTests\BaseTestCase;

class ToDoListServiceTest extends BaseTestCase
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

        $result = $this->getToDoListService()->findToDoListByUserId('1');
        $this->assertEquals(2,count($result));
    }

    public function testCreateToDoListKnowledge()
    {
        $knowledge = array(
            'title' => '21天精通ｐｈｐ',
            'summary' => '简洁的摘要',
            'content' => '没什么内容',
            'type' => 'file',
            'userId' => 1,
        );
        $this->getKnowledgeDao()->create($knowledge);
        $this->getToDoListService()->createToDoListKnowledge(1, 1);
        $result = $this->getToDoListDao()->get(1);

        $this->assertEquals(1, $result['userId']);
        $this->assertEquals(1, $result['knowledgeId']);
    }

    /**
     * @expectedException Exception
     */
    public function testCreateToDoListKnowledgeWithNoKnowledgeException()
    {
        $this->getToDoListService()->createToDoListKnowledge(1, 1);

        $result = $this->getToDoListDao()->get(1);

        $this->assertEquals(1, $result['userId']);
        $this->assertEquals(1, $result['knowledgeId']);
    }

    /**
     * @expectedException Exception
     */
    public function testCreateToDoListKnowledgeWithRecordRepeatException()
    {
        $knowledge = array(
            'title' => '21天精通ｐｈｐ',
            'summary' => '简洁的摘要',
            'content' => '没什么内容',
            'type' => 'file',
            'userId' => 1,
        );
        $this->getKnowledgeDao()->create($knowledge);
        $this->getToDoListService()->createToDoListKnowledge(1, 1);
        $this->getToDoListService()->createToDoListKnowledge(1, 1);

        $this->assertEquals(1, $result['userId']);
        $this->assertEquals(1, $result['knowledgeId']);
    }

    protected function getToDoListService()
    {
        return self::$kernel['todolist_service'];
    }

    protected function getToDoListDao()
    {
        return self::$kernel['todolist_dao'];
    }

    protected function getKnowledgeDao()
    {
        return self::$kernel['knowledge_dao'];
    }
}
