<?php
declare (strict_types = 1);

namespace GrottoPress\Form;

use Aura\Html\HelperLocatorFactory as Helper;
use function Stringy\create as S;

class Field
{
    /**
     * @var string $wrap Wrapper HTML tag.
     */
    protected $wrap;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string $labelPos Label position relative to field
     */
    protected $labelPos;

    /**
     * @var string $layout 'block' or 'inline'
     */
    protected $layout;

    /**
     * Field choices (for radio buttons and dropdowns)
     *
     * @var array
     */
    protected $choices;

    /**
     * Additional field attributes
     *
     * @var array
     */
    protected $meta;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * HTML Escaper
     *
     * @var \Aura\Html\Escaper
     */
    private $escape;

    /**
     * @param array $args Field arguments supplied as associative array
     */
    public function __construct(array $args = [])
    {
        $this->setAttributes($args);
        $this->sanitizeAttributes();

        $this->escape = (new Helper())->newInstance()->escape();
    }

    public function render(): string
    {
        $field = "render_{$this->type}";

        if (\is_callable([$this, $field])) {
            return $this->startRender().$this->$field().$this->endRender();
        }

        return '';
    }

    protected function startRender(): string
    {
        $html = '';

        if ($this->wrap) {
            $html .= '<'.$this->wrap.'>';
        }

        if ('radio' !== $this->type &&
            'before_field' === $this->labelPos &&
            $this->label
        ) {
            $html .= '<label for="'.$this->escape->attr($this->id).'" '.
                $this->labelIdString().'>'.$this->label.'</label> ';

            if ('checkbox' !== $this->type) {
                if ('block' === $this->layout) {
                    $html .= '<br />';
                }
            }
        }

        return $html;
    }

    protected function endRender(): string
    {
        $html = '';

        if ('radio' !== $this->type &&
            'after_field' === $this->labelPos &&
            $this->label
        ) {
            if ('checkbox' !== $this->type) {
                if ('block' === $this->layout) {
                    $html .= '<br />';
                }
            }

            $html .= ' <label for="'.$this->escape->attr($this->id).'" '.
                $this->labelIdString().'>'.$this->label.'</label>';
        }

        if ($this->wrap) {
            $html .= '</'.$this->wrap.'>';
        }

        return $html;
    }

    /**
     * Called if $this->type === 'text'
     */
    protected function render_text(): string
    {
        return '<input type="text" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'email'
     */
    protected function render_email(): string
    {
        return '<input type="email" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'number'
     */
    protected function render_number(): string
    {
        return '<input type="number" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'url'
     */
    protected function render_url(): string
    {
        return '<input type="text" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="'.$this->escape->attr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'file'
     */
    protected function render_file(): string
    {
        return '<input type="file" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).'" />';
    }

    /**
     * Called if $this->type === 'textarea'
     */
    protected function render_textarea(): string
    {
        return '<textarea '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).'">'.
            $this->escape->attr($this->value).'</textarea>';
    }

    /**
     * Called if $this->type === 'checkbox'
     */
    protected function render_checkbox(): string
    {
        return '<input type="checkbox" '.$this->metaString().
            ' id="'.$this->escape->attr($this->id).
            '" name="'.$this->escape->attr($this->name).
            '" value="1" '.$this->checked(1, $this->value).' />';
    }

    /**
     * Called if $this->type === 'submit'
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
     * Called if $this->type === 'radio'
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

            if ('before_field' === $this->labelPos) {
                $html .= '<label for="'.
                    $this->escape->attr($id).'">'.$label.'</label> ';
            }

            $html .= '<input type="radio" '.$this->metaString().
                ' id="'.$this->escape->attr($id).
                '" name="'.$this->escape->attr($this->name).
                '" value="'.$this->escape->attr($value).
                '" '.$this->checked($value, $this->value).' />';

            if ('after_field' === $this->labelPos) {
                $html .= ' <label for="'.
                    $this->escape->attr($id).'">'.$label.'</label>';
            }

            $html .= '<br />';
        }

        return $html;
    }

    /**
     * Called if $this->type === 'select'
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
     * Convert meta to string
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
     * @param mixed $a
     * @param mixed $b
     */
    protected function selected($a, $b): string
    {
        return ($this->equiv($a, $b) ? 'selected="selected"' : '');
    }

    /**
     * Build 'checked' html attribute for radio and checkbox
     *
     * @param mixed $a
     * @param mixed $b
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
     *   where any one of the variables is a set/array.
     *
     * @param mixed $a
     * @param mixed $b
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

    private function setAttributes(array $args)
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

    private function sanitizeAttributes()
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

        $this->labelPos = (\in_array(
            $this->labelPos,
            ['before_field', 'after_field']
        ) ? $this->labelPos : 'after_field');
    }
}
