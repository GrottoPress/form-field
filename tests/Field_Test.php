<?php

/**
 * Field Tests
 *
 * @package GrottoPress\WordPress\Form\Tests
 *
 * @since 0.1.0
 *
 * @author GrottoPress <info@grottopress.com>
 * @author N Atta Kus Adusei
 */

declare ( strict_types = 1 );

namespace GrottoPress\Form;

use GrottoPress\Form\Field;
use PHPUnit\Framework\TestCase;

/**
 * Field Tests
 *
 * @since 0.1.0
 */
class Field_Test extends TestCase {
    private $dom;

    public function setUp() {
        $this->dom = new \DOMDocument();

        parent::setUp();
    }

	public function test_text_field_render() {
		$field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'text',
            'value' => 'Some text',
            'label' => 'Field label',
            'wrap' => 'div',
            'meta' => [ 'class' => 'my-class', 'placeholder' => 'Nothing' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $divs = $this->dom->getElementsByTagName( 'div' );
        $inputs = $this->dom->getElementsByTagName( 'input' );
        $labels = $this->dom->getElementsByTagName( 'label' );

        $this->assertCount( 1, $divs );
        $this->assertCount( 1, $inputs );
        $this->assertCount( 1, $labels );
        $this->assertSame( 'field-id', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'text', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'Some text', $inputs->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertSame( 'Nothing', $inputs->item( 0 )->attributes->getNamedItem( 'placeholder' )->value );
        $this->assertSame( 'field-id-label', $labels->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'Field label', $labels->item( 0 )->childNodes->item( 0 )->ownerDocument->saveHTML( $labels->item( 0 )->childNodes->item( 0 ) ) );
	}

    public function test_email_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'email',
            'value' => 'info@grottopress.com',
            'meta' => [ 'class' => 'my-class', 'placeholder' => 'Nothing' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $inputs = $this->dom->getElementsByTagName( 'input' );
        $labels = $this->dom->getElementsByTagName( 'label' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $inputs );
        $this->assertCount( 0, $labels );
        $this->assertSame( 'field-id', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'email', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'info@grottopress.com', $inputs->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertSame( 'Nothing', $inputs->item( 0 )->attributes->getNamedItem( 'placeholder' )->value );
    }

    public function test_checkbox_field_checked() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'checkbox',
            'value' => '1',
            'meta' => [ 'class' => 'my-class' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $inputs = $this->dom->getElementsByTagName( 'input' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $inputs );
        $this->assertSame( 'field-id', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'checkbox', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'checked', $inputs->item( 0 )->attributes->getNamedItem( 'checked' )->value );
        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
    }

    public function test_checkbox_field_unchecked() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'checkbox',
            'value' => '0',
            'meta' => [ 'class' => 'my-class' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $inputs = $this->dom->getElementsByTagName( 'input' );

        $this->assertEmpty( $inputs->item( 0 )->attributes->getNamedItem( 'checked' ) );
    }

    public function test_number_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'number',
            'value' => 10,
            'meta' => [ 'class' => 'my-class', 'placeholder' => 45 ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $inputs = $this->dom->getElementsByTagName( 'input' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $inputs );
        $this->assertSame( 'field-id', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'number', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertEquals( 10, $inputs->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertEquals( 45, $inputs->item( 0 )->attributes->getNamedItem( 'placeholder' )->value );
    }

    public function test_url_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'url',
            'value' => 'https://www.grottopress.com',
            'meta' => [ 'class' => 'my-class', 'placeholder' => 'URL here' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $inputs = $this->dom->getElementsByTagName( 'input' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $inputs );
        $this->assertSame( 'field-id', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'text', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'https://www.grottopress.com', $inputs->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertSame( 'URL here', $inputs->item( 0 )->attributes->getNamedItem( 'placeholder' )->value );
    }

    public function test_textarea_field_render() {
        $field = new Field( [
            'id' => 'f|ield-ID',
            'name' => '//field- ~name',
            'type' => 'textarea',
            'value' => 'Some text here',
            'meta' => [ 'class' => 'my-class', 'placeholder' => 'Your text here' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $textareas = $this->dom->getElementsByTagName( 'textarea' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $textareas );
        $this->assertSame( 'field-id', $textareas->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $textareas->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'Some text here', $textareas->item( 0 )->childNodes->item( 0 )->ownerDocument->saveHTML( $textareas->item( 0 )->childNodes->item( 0 ) ) );
        $this->assertSame( 'my-class', $textareas->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertSame( 'Your text here', $textareas->item( 0 )->attributes->getNamedItem( 'placeholder' )->value );
    }

    public function test_radio_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'radio',
            'value' => 'no',
            'label' => 'Field label',
            'choices' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'meta' => [ 'class' => 'my-class' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $inputs = $this->dom->getElementsByTagName( 'input' );
        $labels = $this->dom->getElementsByTagName( 'label' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 2, $inputs );
        $this->assertCount( 3, $labels );

        $this->assertSame( 'field-id-yes', $inputs->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-id-no', $inputs->item( 1 )->attributes->getNamedItem( 'id' )->value );

        $this->assertSame( 'field-name', $inputs->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'field-name', $inputs->item( 1 )->attributes->getNamedItem( 'name' )->value );

        $this->assertSame( 'radio', $inputs->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'radio', $inputs->item( 1 )->attributes->getNamedItem( 'type' )->value );

        $this->assertEmpty( $inputs->item( 0 )->attributes->getNamedItem( 'checked' ) );
        $this->assertSame( 'checked', $inputs->item( 1 )->attributes->getNamedItem( 'checked' )->value );

        $this->assertSame( 'my-class', $inputs->item( 0 )->attributes->getNamedItem( 'class' )->value );
        $this->assertSame( 'my-class', $inputs->item( 1 )->attributes->getNamedItem( 'class' )->value );

        $this->assertSame( 'field-id-label', $labels->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'Field label', $labels->item( 0 )->childNodes->item( 0 )->ownerDocument->saveHTML( $labels->item( 0 )->childNodes->item( 0 ) ) );
        $this->assertSame( 'Yes', $labels->item( 1 )->childNodes->item( 0 )->ownerDocument->saveHTML( $labels->item( 1 )->childNodes->item( 0 ) ) );
        $this->assertSame( 'No', $labels->item( 2 )->childNodes->item( 0 )->ownerDocument->saveHTML( $labels->item( 2 )->childNodes->item( 0 ) ) );
    }

    public function test_select_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'select',
            'value' => 'no',
            'choices' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'meta' => [ 'class' => 'my-class' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $selects = $this->dom->getElementsByTagName( 'select' );
        $options = $this->dom->getElementsByTagName( 'option' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $selects );
        $this->assertCount( 2, $options );

        $this->assertSame( 'field-id', $selects->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $selects->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'my-class', $selects->item( 0 )->attributes->getNamedItem( 'class' )->value );

        $this->assertSame( 'yes', $options->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'no', $options->item( 1 )->attributes->getNamedItem( 'value' )->value );

        $this->assertEmpty( $options->item( 0 )->attributes->getNamedItem( 'selected' ) );
        $this->assertSame( 'selected', $options->item( 1 )->attributes->getNamedItem( 'selected' )->value );

        $this->assertSame( 'Yes', $options->item( 0 )->childNodes->item( 0 )->ownerDocument->saveHTML( $options->item( 0 )->childNodes->item( 0 ) ) );
        $this->assertSame( 'No', $options->item( 1 )->childNodes->item( 0 )->ownerDocument->saveHTML( $options->item( 1 )->childNodes->item( 0 ) ) );
    }

    public function test_multi_select_field_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name[]',
            'type' => 'select',
            'value' => 'no',
            'choices' => [ 'yes' => 'Yes', 'no' => 'No' ],
            'meta' => [ 'class' => 'my-class', 'multiple' => 'multiple' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $selects = $this->dom->getElementsByTagName( 'select' );
        $options = $this->dom->getElementsByTagName( 'option' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $selects );
        $this->assertCount( 2, $options );

        $this->assertSame( 'field-id', $selects->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name[]', $selects->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'my-class', $selects->item( 0 )->attributes->getNamedItem( 'class' )->value );

        $this->assertSame( 'yes', $options->item( 0 )->attributes->getNamedItem( 'value' )->value );
        $this->assertSame( 'no', $options->item( 1 )->attributes->getNamedItem( 'value' )->value );

        $this->assertEmpty( $options->item( 0 )->attributes->getNamedItem( 'selected' ) );
        $this->assertSame( 'selected', $options->item( 1 )->attributes->getNamedItem( 'selected' )->value );

        $this->assertSame( 'Yes', $options->item( 0 )->childNodes->item( 0 )->ownerDocument->saveHTML( $options->item( 0 )->childNodes->item( 0 ) ) );
        $this->assertSame( 'No', $options->item( 1 )->childNodes->item( 0 )->ownerDocument->saveHTML( $options->item( 1 )->childNodes->item( 0 ) ) );
    }

    public function test_submit_button_render() {
        $field = new Field( [
            'id' => 'field-id',
            'name' => 'field-name',
            'type' => 'submit',
            'value' => 'Save',
            'meta' => [ 'class' => 'my-class' ],
        ] );

        $this->dom->loadHTML( $field->render() );
        $ps = $this->dom->getElementsByTagName( 'p' );
        $buttons = $this->dom->getElementsByTagName( 'button' );

        $this->assertCount( 1, $ps );
        $this->assertCount( 1, $buttons );
        $this->assertSame( 'field-id', $buttons->item( 0 )->attributes->getNamedItem( 'id' )->value );
        $this->assertSame( 'field-name', $buttons->item( 0 )->attributes->getNamedItem( 'name' )->value );
        $this->assertSame( 'submit', $buttons->item( 0 )->attributes->getNamedItem( 'type' )->value );
        $this->assertSame( 'my-class', $buttons->item( 0 )->attributes->getNamedItem( 'class' )->value );
    }
}
