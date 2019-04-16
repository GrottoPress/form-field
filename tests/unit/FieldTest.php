<?php
declare (strict_types = 1);

namespace GrottoPress\Form;

use Codeception\Test\Unit;

class FieldTest extends Unit
{
    private $dom;

    public function _before()
    {
        $this->dom = new \DOMDocument();
    }

    public function testTextFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'text',
            'value' => 'Some text',
            'label' => 'Field label',
            'labelPos' => 'before_field',
            'wrap' => 'div',
            'meta' => ['class' => 'my-class', 'placeholder' => 'Nothing'],
        ]);

        $this->dom->loadHTML($field->render());
        $divs = $this->dom->getElementsByTagName('div');
        $inputs = $this->dom->getElementsByTagName('input');
        $labels = $this->dom->getElementsByTagName('label');

        $this->assertCount(1, $divs);
        $this->assertCount(1, $inputs);
        $this->assertCount(1, $labels);
        $this->assertSame(
            'field-id',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'text',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'Some text',
            $inputs->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'Nothing',
            $inputs->item(0)->attributes->getNamedItem('placeholder')->value
        );
        $this->assertSame(
            'field-id-label',
            $labels->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'Field label',
            $labels->item(0)->childNodes->item(0)->ownerDocument
                ->saveHTML($labels->item(0)->childNodes->item(0))
        );
    }

    public function testEmailFieldRender()
    {
        $field = new Field([
            'id' => '',
            'name' => 'field-name',
            'type' => 'email',
            'label' => 'Field label',
            'value' => 'a@b.c',
            'meta' => ['class' => 'my-class', 'placeholder' => 'Nothing'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');
        $labels = $this->dom->getElementsByTagName('label');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $inputs);
        $this->assertCount(1, $labels);
        $this->assertSame(
            '',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'email',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'a@b.c',
            $inputs->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'Nothing',
            $inputs->item(0)->attributes->getNamedItem('placeholder')->value
        );
        $this->assertSame(
            'Field label',
            $labels->item(0)->childNodes->item(1)->ownerDocument
                ->saveHTML($labels->item(0)->childNodes->item(1))
        );
    }

    public function testCheckboxFieldChecked()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'checkbox',
            'value' => '1',
            'meta' => ['class' => 'my-class'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $inputs);
        $this->assertSame(
            'field-id',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'checkbox',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'checked',
            $inputs->item(0)->attributes->getNamedItem('checked')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
    }

    public function testCheckboxFieldUnchecked()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'checkbox',
            'value' => '0',
            'meta' => ['class' => 'my-class'],
        ]);

        $this->dom->loadHTML($field->render());
        $inputs = $this->dom->getElementsByTagName('input');

        $this->assertEmpty(
            $inputs->item(0)->attributes->getNamedItem('checked')
        );
    }

    public function testNumberFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'number',
            'value' => 10,
            'meta' => ['class' => 'my-class', 'placeholder' => 45],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $inputs);
        $this->assertSame(
            'field-id',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'number',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertEquals(
            10,
            $inputs->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertEquals(
            45,
            $inputs->item(0)->attributes->getNamedItem('placeholder')->value
        );
    }

    public function testUrlFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'url',
            'value' => 'https://www.grottopress.com',
            'meta' => ['class' => 'my-class', 'placeholder' => 'URL here'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $inputs);
        $this->assertSame(
            'field-id',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'text',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'https://www.grottopress.com',
            $inputs->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'URL here',
            $inputs->item(0)->attributes->getNamedItem('placeholder')->value
        );
    }

    public function testFileFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'file',
            'meta' => [
                'class' => 'my-class',
                'accept' => 'image/png, image/jpeg'
            ],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $inputs);
        $this->assertSame(
            'field-id',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'file',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'image/png, image/jpeg',
            $inputs->item(0)->attributes->getNamedItem('accept')->value
        );
    }

    public function testTextareaFieldRender()
    {
        $field = new Field([
            'id' => 'f|ield-ID',
            'name' => '//field- ~name',
            'type' => 'textarea',
            'value' => 'Some text here',
            'meta' => ['class' => 'my-class', 'placeholder' => 'Your text here'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $textareas = $this->dom->getElementsByTagName('textarea');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $textareas);
        $this->assertSame(
            'f-ield-id',
            $textareas->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field---name',
            $textareas->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'Some text here',
            $textareas->item(0)->childNodes->item(0)->ownerDocument
                ->saveHTML($textareas->item(0)->childNodes->item(0))
        );
        $this->assertSame(
            'my-class',
            $textareas->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'Your text here',
            $textareas->item(0)->attributes->getNamedItem('placeholder')->value
        );
    }

    public function testRadioFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'radio',
            'value' => 'no',
            'label' => 'Field label',
            'choices' => ['yes' => 'Yes', 'no' => 'No'],
            'meta' => ['class' => 'my-class'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $inputs = $this->dom->getElementsByTagName('input');
        $labels = $this->dom->getElementsByTagName('label');

        $this->assertCount(1, $ps);
        $this->assertCount(2, $inputs);
        $this->assertCount(3, $labels);

        $this->assertSame(
            'field-id-yes',
            $inputs->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-id-no',
            $inputs->item(1)->attributes->getNamedItem('id')->value
        );

        $this->assertSame(
            'field-name',
            $inputs->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'field-name',
            $inputs->item(1)->attributes->getNamedItem('name')->value
        );

        $this->assertSame(
            'radio',
            $inputs->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'radio',
            $inputs->item(1)->attributes->getNamedItem('type')->value
        );

        $this->assertEmpty(
            $inputs->item(0)->attributes->getNamedItem('checked')
        );
        $this->assertSame(
            'checked',
            $inputs->item(1)->attributes->getNamedItem('checked')->value
        );

        $this->assertSame(
            'my-class',
            $inputs->item(0)->attributes->getNamedItem('class')->value
        );
        $this->assertSame(
            'my-class',
            $inputs->item(1)->attributes->getNamedItem('class')->value
        );

        $this->assertSame(
            'field-id-label',
            $labels->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'Field label',
            $labels->item(0)->childNodes->item(0)->ownerDocument
                ->saveHTML($labels->item(0)->childNodes->item(0))
        );
        $this->assertSame(
            'Yes',
            $labels->item(1)->childNodes->item(0)->ownerDocument
                ->saveHTML($labels->item(1)->childNodes->item(0))
        );
        $this->assertSame(
            'No',
            $labels->item(2)->childNodes->item(0)->ownerDocument
                ->saveHTML($labels->item(2)->childNodes->item(0))
        );
    }

    public function testSelectFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'select',
            'value' => 'no',
            'choices' => ['yes' => 'Yes', 'no' => 'No'],
            'meta' => ['class' => 'my-class'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $selects = $this->dom->getElementsByTagName('select');
        $options = $this->dom->getElementsByTagName('option');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $selects);
        $this->assertCount(2, $options);

        $this->assertSame(
            'field-id',
            $selects->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $selects->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'my-class',
            $selects->item(0)->attributes->getNamedItem('class')->value
        );

        $this->assertSame(
            'yes',
            $options->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'no',
            $options->item(1)->attributes->getNamedItem('value')->value
        );

        $this->assertEmpty(
            $options->item(0)->attributes->getNamedItem('selected')
        );
        $this->assertSame(
            'selected',
            $options->item(1)->attributes->getNamedItem('selected')->value
        );

        $this->assertSame(
            'Yes',
            $options->item(0)->childNodes->item(0)->ownerDocument
                ->saveHTML($options->item(0)->childNodes->item(0))
        );
        $this->assertSame(
            'No',
            $options->item(1)->childNodes->item(0)->ownerDocument
                ->saveHTML($options->item(1)->childNodes->item(0))
        );
    }

    public function testMultiSelectFieldRender()
    {
        $field = new Field([
            'id' => 'field-id',
            'name' => 'field-name[]',
            'type' => 'select',
            'value' => 'no',
            'choices' => ['yes' => 'Yes', 'no' => 'No'],
            'meta' => ['class' => 'my-class', 'multiple' => 'multiple'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $selects = $this->dom->getElementsByTagName('select');
        $options = $this->dom->getElementsByTagName('option');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $selects);
        $this->assertCount(2, $options);

        $this->assertSame(
            'field-id',
            $selects->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name[]',
            $selects->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'my-class',
            $selects->item(0)->attributes->getNamedItem('class')->value
        );

        $this->assertSame(
            'yes',
            $options->item(0)->attributes->getNamedItem('value')->value
        );
        $this->assertSame(
            'no',
            $options->item(1)->attributes->getNamedItem('value')->value
        );

        $this->assertEmpty(
            $options->item(0)->attributes->getNamedItem('selected')
        );
        $this->assertSame(
            'selected',
            $options->item(1)->attributes->getNamedItem('selected')->value
        );

        $this->assertSame(
            'Yes',
            $options->item(0)->childNodes->item(0)->ownerDocument
                ->saveHTML($options->item(0)->childNodes->item(0))
        );
        $this->assertSame(
            'No',
            $options->item(1)->childNodes->item(0)->ownerDocument
                ->saveHTML($options->item(1)->childNodes->item(0))
        );
    }

    public function testSubmitButtonRender()
    {
        $field = new Field([
            'name' => 'field-name',
            'type' => 'submit',
            'value' => 'Save',
            'meta' => ['class' => 'my-class'],
        ]);

        $this->dom->loadHTML($field->render());
        $ps = $this->dom->getElementsByTagName('p');
        $buttons = $this->dom->getElementsByTagName('button');

        $this->assertCount(1, $ps);
        $this->assertCount(1, $buttons);
        $this->assertSame(
            '',
            $buttons->item(0)->attributes->getNamedItem('id')->value
        );
        $this->assertSame(
            'field-name',
            $buttons->item(0)->attributes->getNamedItem('name')->value
        );
        $this->assertSame(
            'submit',
            $buttons->item(0)->attributes->getNamedItem('type')->value
        );
        $this->assertSame(
            'my-class',
            $buttons->item(0)->attributes->getNamedItem('class')->value
        );
    }
}
