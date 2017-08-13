# Form Field

## Description

*Form Field* is a library to render HTML form fields.

## Usage

Install via composer:

`composer require grottopress/form-field`

Instantiate and use thus:

    <?php

    use GrottoPress\Form\Field;

    // Text field
    $text = new Field( [
        'id' => 'field-id',
        'name' => 'field-name',
        'type' => 'text',
        'value' => 'My awesome text field',
        'label' => 'My text field',
    ] );

    // Render text field
    echo $text->render();

    // Radio buttons
    $radio = new Field( [
        'id' => 'field-id',
        'name' => 'field-name',
        'type' => 'radio',
        'value' => 'my-choice',
        'choices' => [
            'one' => 'One',
            'my-choice' => 'My Choice',
            'two' => 'Two',
        ],
    ] );

    // Render radio field
    echo $radio->render();

    // Dropdown
    $dropdown = new Field( [
        'id' => 'field-id',
        'name' => 'field-name',
        'type' => 'select',
        'value' => 'my-choice',
        'choices' => [
            'one' => 'One',
            'my-choice' => 'My Choice',
            'two' => 'Two',
        ],
    ] );

    // Render dropdown field
    echo $dropdown->render();

    // Multi-select dropdown
    $mdrop = new Field( [
        'id' => 'field-id',
        'name' => 'field-name[]',
        'type' => 'radio',
        'value' => 'my-choice',
        'choices' => [
            'one' => 'One',
            'my-choice' => 'My Choice',
            'two' => 'Two',
        ],
        'meta' => [
            'multiple' => 'multiple',
        ],
    ] );

    // Render multi-select dropdown
    echo $mdrop->render();
