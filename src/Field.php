<?php

/**
 * Form Field
 *
 * Renders a field based on given args
 *
 * @package GrottoPress\Form
 * @since 0.1.0
 *
 * @author GrottoPress <info@grottopress.com>
 * @author N Atta Kus Adusei
 */

declare (strict_types = 1);

namespace GrottoPress\Form;

use Aura\Html\HelperLocatorFactory as Helper;
use function Stringy\create as S;

/**
 * Form field
 *
 * @since 0.1.0
 */
class Field
{
    /**
     * Wrap tag
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $wrap  Wrapper HTML tag.
     */
    protected $wrap;

    /**
     * Field ID
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $id Field ID
     */
    protected $id;

    /**
     * Field name
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $name Field name
     */
    protected $name;

    /**
     * Field type
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $type Field type
     */
    protected $type;

    /**
     * Field label
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $label Field label
     */
    protected $label;

    /**
     * Label position
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $label_pos Label position relative to field
     */
    protected $label_pos;

    /**
     * Layout
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $layout Field layout
     */
    protected $layout;

    /**
     * Field choices (for radio buttons and dropdowns)
     *
     * @since 0.1.0
     * @access protected
     *
     * @var array $choices Field choices.
     */
    protected $choices;

    /**
     * Additional field attributes
     *
     * @since 0.1.0
     * @access protected
     *
     * @var array $meta Additional field attributes.
     */
    protected $meta;

    /**
     * Field value
     *
     * @since 0.1.0
     * @access protected
     *
     * @var string $value Field value
     */
    protected $value;

    /**
     * Escaper
     *
     * @since 0.1.0
     * @access protected
     *
     * @var object $escape
     */
    protected $escape;
    
    /**
     * Constructor
     *
     * @param array $args Field arguments supplied as associative array
     *
     * @since 0.1.0
     * @access public
     */
    public function __construct(array $args = [])
    {
        $this->setAttributes($args);
        $this->sanitizeAttributes();

        $this->escape = (new Helper())->newInstance()->escape();
    }

    /**
     * Render form field.
     *
     * @since 0.1.0
     * @access public
     *
     * @return string Form field html.
     */
    public function render(): string
    {
        $field = 'render_'.$this->type;
        
        if (\is_callable([$this, $field])) {
            return $this->renderStart().$this->$field().$this->renderEnd();
        }

        return '';
    }

    /**
     * Render form field: Start.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function renderStart(): string
    {
        $html = '';

        if ($this->wrap) {
            $html .= '<'.$this->wrap.'>';
        }

        if ('radio' !== $this->type) {
            if ('before_field' === $this->label_pos && $this->label) {
                $html .= '<label for="'.$this->escape->attr($this->id).'" '.
                    $this->labelIdString().'>'.$this->label.'</label> ';
            }

            if ('checkbox' !== $this->type) {
                if ('block' === $this->layout) {
                    $html .= '<br />';
                }
            }
        }

        return $html;
    }

    /**
     * Render form field: End.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function renderEnd(): string
    {
        $html = '';

        if ('radio' !== $this->type) {
            if ('checkbox' !== $this->type) {
                if ('block' === $this->layout) {
                    $html .= '<br />';
                }
            }

            if ('after_field' === $this->label_pos && $this->label) {
                $html .= ' <label for="'.$this->escape->attr($this->id).'" '.
                    $this->labelIdString().'>'.$this->label.'</label>';
            }
        }

        if ($this->wrap) {
            $html .= '</'.$this->wrap.'>';
        }

        return $html;
    }

    /**
     * Render text field.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_text(): string
    {
        return '<input type="text" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Render email field.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_email(): string
    {
        return '<input type="email" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Render number field.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_number(): string
    {
        return '<input type="number" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Render URL field.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_url(): string
    {
        return '<input type="text" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Render textarea.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_textarea(): string
    {
        return '<textarea '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).'">'.
            $this->escape->attr($this->value).'</textarea>';
    }

    /**
     * Render checkbox field.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_checkbox(): string
    {
        return '<input type="checkbox" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="1" '.$this->checked(1, $this->value).' />';
    }

    /**
     * Render submit button.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_submit(): string
    {
        return '<button type="submit" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).'">'.
            $this->escape->attr($this->value).
        '</button>';
    }

    /**
     * Render radio.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_radio(): string
    {
        $html = '';

        if ($this->label) {
            $html .= '<label for="'.$this->escape->attr($this->id).
                '" '.$this->labelIdString().'>'.$this->label.'</label><br />';
        }

        if (!$this->choices) {
            return $html;
        }

        foreach ($this->choices as $value => $label) {
            $id = $this->id.'-'.(string)S($value)->slugify();

            if ('before_field' === $this->label_pos) {
                $html .= '<label for="'.
                    $this->escape->attr($id).'">'.$label.'</label> ';
            }

            $html .= '<input type="radio" '.$this->metaString().
                ' id="'.$this->escape->attr($id).
                '" name="'.$this->escape->attr($this->name).
                '" value="'.$this->escape->attr($value).
                '" '.$this->checked($value, $this->value).' />';

            if ('after_field' === $this->label_pos) {
                $html .= ' <label for="'.
                    $this->escape->attr($id).'">'.$label.'</label>';
            }

            $html .= '<br />';
        }

        return $html;
    }

    /**
     * Render select.
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Form field html.
     */
    protected function render_select(): string
    {
        if (!$this->choices) {
            return '';
        }

        $html = '<select '.$this->metaString().' id="'.
            $this->escape->attr($this->id).'" name="'.
            $this->escape->attr($this->name).'">';

        foreach ($this->choices as $value => $label) {
            if (isset($this->meta['multiple'])) {
                $selected = $this->selected(
                    $value,
                    (\in_array($value, (array)$this->value) ? $value : '')
                );
            } else {
                $selected = $this->selected($value, $this->value);
            }

            $html .= '<option '.$selected.' value="'.
                $this->escape->attr($value).'">'.$label.'</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Set attributes
     *
     * @param array $args Arguments supplied to this object.
     *
     * @since 0.1.0
     * @access protected
     */
    protected function setAttributes(array $args)
    {
        if (!($vars = \get_object_vars($this))) {
            return;
        }

        unset($args['meta']['id']);
        unset($args['meta']['type']);
        unset($args['meta']['name']);
        unset($args['meta']['value']);

        foreach ($vars as $key => $value) {
            $this->$key = $args[$key] ?? '';
        }
    }

    /**
     * Sanitize attributes
     *
     * @since 0.1.0
     * @access protected
     */
    protected function sanitizeAttributes()
    {
        $this->wrap = (
            $this->wrap
            ? (string)S($this->wrap)->slugify('_')
            : 'p'
        );

        $this->id = (string)S($this->id)->slugify();
        $this->name = (string)S($this->name)->toAscii()->regexReplace(
            '[^\w\d\[\]\-\_]',
            ''
        );
        $this->type = (string)S($this->type)->slugify('_');
        $this->meta = $this->meta ? (array)$this->meta : [];
        $this->choices = $this->choices ? (array)$this->choices : [];

        $this->layout = (\in_array(
            $this->layout,
            ['block', 'inline']
        ) ? $this->layout : 'inline');

        $this->label_pos = (\in_array(
            $this->label_pos,
            ['before_field', 'after_field']
        ) ? $this->label_pos : 'after_field');
    }

    /**
     * Convert meta to string
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Meta string.
     */
    protected function metaString(): string
    {
        $meta_string = '';
        
        if (!$this->meta) {
            return $meta_string;
        }

        \array_walk($this->meta, function (
            string $value,
            string $key
        ) use (&$meta_string) {
            $meta_string .= (string)S($key)->slugify().
                '="'.$this->escape->attr($value).'" ';
        });

        return trim($meta_string);
    }

    /**
     * Label ID
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string Label ID.
     */
    protected function labelIdString(): string
    {
        if (!$this->id) {
            return '';
        }

        return 'id="'.$this->id.'-label"';
    }

    /**
     * Build 'selected' html attribute for dropdowns
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string 'selected' html attribute
     */
    protected function selected($a, $b): string
    {
        return ($this->equiv($a, $b) ? 'selected="selected"' : '');
    }

    /**
     * Build 'checked' html attribute for radio and checkbox
     *
     * @since 0.1.0
     * @access protected
     *
     * @return string 'checked' html attribute
     */
    protected function checked($a, $b): string
    {
        return ($this->equiv($a, $b) ? 'checked="checked"' : '');
    }

    /**
     * Are two values equivalent.
     *
     * For the purposes of this class, equivalence is defined as:
     * - When the two variables have equal values;
     * - When the two variables have identical values;
     * - When one variable's value is contained in the other variable's value,
     *   where any one of the variables is a set.
     *
     * @var mixed $a
     * @var mixed $b
     *
     * @since 0.1.0
     * @access protected
     *
     * @return bool
     */
    protected function equiv($a, $b): bool
    {
        if (\is_array($a) && \is_scalar($b)) {
            return \in_array($b, $a);
        }

        if (\is_array($b) && \is_scalar($a)) {
            return \in_array($a, $b);
        }

        return ($a == $b);
    }
}
