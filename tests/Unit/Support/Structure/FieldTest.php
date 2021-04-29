<?php

namespace Uccello\Core\Tests\Unit\Support\Structure;

use PHPUnit\Framework\TestCase;
use Uccello\Core\Support\Structure\Field;

class FieldTest extends TestCase
{
    private function makeField($params)
    {
        $data = json_decode(json_encode($params));

        return new Field($data);
    }

    public function testIfColumnIsOverrided()
    {
        $field = $this->makeField([
            'name' => 'label',
            'type' => 'string',
            'column' => 'the_label'
        ]);

        $this->assertEquals($field->column, 'the_label');
    }

    public function testIfColumnIsOverridedWithEntityField()
    {
        $field = $this->makeField([
            'name' => 'user',
            'type' => 'entity',
            'column' => 'id_user'
        ]);

        $this->assertEquals($field->column, 'id_user');
    }

    public function testIfColumnIsSameAsFieldName()
    {
        $field = $this->makeField([
            'name' => 'label',
            'type' => 'string'
        ]);

        $this->assertEquals($field->column, 'label');
    }

    public function testIfColumnIsOverridedAutomaticalyWithEntityField()
    {
        $field = $this->makeField([
            'name' => 'user',
            'type' => 'entity'
        ]);

        $this->assertEquals($field->column, 'user_id');
    }

    public function testIfFieldIsVisibleEverywhere()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => true
        ]);

        $this->assertTrue($field->isVisible('everywhere'));
        $this->assertTrue($field->isVisible('create'));
        $this->assertTrue($field->isVisible('edit'));
        $this->assertTrue($field->isVisible('detail'));
        $this->assertTrue($field->isVisible('list'));

        $this->assertTrue($field->isVisibleEverywhere());
        $this->assertTrue($field->isVisibleInCreateView());
        $this->assertTrue($field->isVisibleInEditView());
        $this->assertTrue($field->isVisibleInDetailView());
        $this->assertTrue($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleEverywhere2()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'create' => true,
                'edit' => true,
                'detail' => true,
                'list' => true
            ]
        ]);

        $this->assertTrue($field->isVisible('everywhere'));
        $this->assertTrue($field->isVisible('create'));
        $this->assertTrue($field->isVisible('edit'));
        $this->assertTrue($field->isVisible('detail'));
        $this->assertTrue($field->isVisible('list'));

        $this->assertTrue($field->isVisibleEverywhere());
        $this->assertTrue($field->isVisibleInCreateView());
        $this->assertTrue($field->isVisibleInEditView());
        $this->assertTrue($field->isVisibleInDetailView());
        $this->assertTrue($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleOnlyInCreateView()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'create' => true
            ]
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertTrue($field->isVisible('create'));
        $this->assertFalse($field->isVisible('edit'));
        $this->assertFalse($field->isVisible('detail'));
        $this->assertFalse($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertTrue($field->isVisibleInCreateView());
        $this->assertFalse($field->isVisibleInEditView());
        $this->assertFalse($field->isVisibleInDetailView());
        $this->assertFalse($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleOnlyInEditView()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'edit' => true
            ]
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertFalse($field->isVisible('create'));
        $this->assertTrue($field->isVisible('edit'));
        $this->assertFalse($field->isVisible('detail'));
        $this->assertFalse($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertFalse($field->isVisibleInCreateView());
        $this->assertTrue($field->isVisibleInEditView());
        $this->assertFalse($field->isVisibleInDetailView());
        $this->assertFalse($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleOnlyInDetailView()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'detail' => true
            ]
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertFalse($field->isVisible('create'));
        $this->assertFalse($field->isVisible('edit'));
        $this->assertTrue($field->isVisible('detail'));
        $this->assertFalse($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertFalse($field->isVisibleInCreateView());
        $this->assertFalse($field->isVisibleInEditView());
        $this->assertTrue($field->isVisibleInDetailView());
        $this->assertFalse($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleOnlyInListView()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'list' => true
            ]
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertFalse($field->isVisible('create'));
        $this->assertFalse($field->isVisible('edit'));
        $this->assertFalse($field->isVisible('detail'));
        $this->assertTrue($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertFalse($field->isVisibleInCreateView());
        $this->assertFalse($field->isVisibleInEditView());
        $this->assertFalse($field->isVisibleInDetailView());
        $this->assertTrue($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleNowhere()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => false
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertFalse($field->isVisible('create'));
        $this->assertFalse($field->isVisible('edit'));
        $this->assertFalse($field->isVisible('detail'));
        $this->assertFalse($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertFalse($field->isVisibleInCreateView());
        $this->assertFalse($field->isVisibleInEditView());
        $this->assertFalse($field->isVisibleInDetailView());
        $this->assertFalse($field->isVisibleInListView());
    }

    public function testIfFieldIsVisibleNowhere2()
    {
        $field = $this->makeField([
            'name' => 'user',
            'visible' => [
                'create' => false,
                'edit' => false,
                'detail' => false,
                'list' => false
            ]
        ]);

        $this->assertFalse($field->isVisible('everywhere'));
        $this->assertFalse($field->isVisible('create'));
        $this->assertFalse($field->isVisible('edit'));
        $this->assertFalse($field->isVisible('detail'));
        $this->assertFalse($field->isVisible('list'));

        $this->assertFalse($field->isVisibleEverywhere());
        $this->assertFalse($field->isVisibleInCreateView());
        $this->assertFalse($field->isVisibleInEditView());
        $this->assertFalse($field->isVisibleInDetailView());
        $this->assertFalse($field->isVisibleInListView());
    }

    public function testGetter()
    {
        $field = $this->makeField([
            'name' => 'label',
            'type' => 'string'
        ]);

        $this->assertEquals($field->name, 'label');
        $this->assertEquals($field->type, 'string');
    }

    public function testSetter()
    {
        // Original name
        $field = $this->makeField([
            'name' => 'label',
        ]);
        $this->assertEquals($field->name, 'label');

        // Change name
        $field->name = 'title';
        $this->assertEquals($field->name, 'title');

        // Add attribute
        $field->type = 'string';
        $this->assertEquals($field->type, 'string');
    }
}
