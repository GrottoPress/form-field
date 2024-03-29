<?php
declare (strict_types = 1);

namespace GrottoPress\Form;

use GrottoPress\Getter\GetterTrait;
use Laminas\Escaper\Escaper;

class Field
{
    use GetterTrait;

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
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param array $args Field arguments supplied as associative array
     */
    public function __construct(array $args = [])
    {
        $this->setAttributes($args);
        $this->sanitizeAttributes();

        $this->escaper = new Escaper('utf-8');
    }

    protected function getWrap(): string
    {
        return $this->wrap;
    }

    protected function getID(): string
    {
        return $this->id;
    }

    protected function getName(): string
    {
        return $this->name;
    }

    protected function getType(): string
    {
        return $this->type;
    }

    protected function getLabel(): string
    {
        return $this->label;
    }

    protected function getLabelPos(): string
    {
        return $this->labelPos;
    }

    protected function getLayout(): string
    {
        return $this->layout;
    }

    protected function getChoices(): array
    {
        return $this->choices;
    }

    protected function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @return mixed
     */
    protected function getValue()
    {
        return $this->value;
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

        if ('radio' !== $this->type && $this->label) {
            if ('before_field' === $this->labelPos) {
                if ($this->id) {
                    $html .= '<label for="'.
                        $this->escapeHtmlAttr($this->id).'" '.
                        $this->labelIdString().'>'.$this->label.'</label>';
                } else {
                    $html .= '<label>'.$this->label;
                }

                if ('checkbox' !== $this->type && 'block' === $this->layout) {
                    $html .= '<br />';
                }
            } elseif (!$this->id) {
                $html .= '<label>';
            }
        }

        return $html;
    }

    protected function endRender(): string
    {
        $html = '';

        if ('radio' !== $this->type && $this->label) {
            if ('before_field' !== $this->labelPos) {
                if ('checkbox' !== $this->type && 'block' === $this->layout) {
                    $html .= '<br />';
                }

                if ($this->id) {
                    $html .= '<label for="'.
                        $this->escapeHtmlAttr($this->id).'"'.
                        $this->labelIdString().'>'.$this->label.'</label>';
                } else {
                    $html .= $this->label.'</label>';
                }
            } elseif (!$this->id) {
                $html .= '</label>';
            }
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
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).
            '" value="'.$this->escapeHtmlAttr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'email'
     */
    protected function render_email(): string
    {
        return '<input type="email" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).
            '" value="'.$this->escapeHtmlAttr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'number'
     */
    protected function render_number(): string
    {
        return '<input type="number" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).
            '" value="'.$this->escapeHtmlAttr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'url'
     */
    protected function render_url(): string
    {
        return '<input type="text" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).
            '" value="'.$this->escapeHtmlAttr($this->value).'" />';
    }

    /**
     * Called if $this->type === 'file'
     */
    protected function render_file(): string
    {
        return '<input type="file" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).'" />';
    }

    /**
     * Called if $this->type === 'textarea'
     */
    protected function render_textarea(): string
    {
        return '<textarea '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).'">'.
            $this->escapeHtmlAttr($this->value).'</textarea>';
    }

    /**
     * Called if $this->type === 'checkbox'
     */
    protected function render_checkbox(): string
    {
        return '<input type="checkbox" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).
            '" value="1" '.$this->checked(1, $this->value).' />';
    }

    /**
     * Called if $this->type === 'submit'
     */
    protected function render_submit(): string
    {
        return '<button type="submit" '.$this->metaString().
            ' id="'.$this->escapeHtmlAttr($this->id).
            '" name="'.$this->escapeHtmlAttr($this->name).'">'.
            $this->escapeHtmlAttr($this->value).
        '</button>';
    }

    /**
     * Called if $this->type === 'radio'
     */
    protected function render_radio(): string
    {
        $html = '';

        if ($this->label) {
            $html .= '<label for="'.$this->escapeHtmlAttr($this->id).
                '" '.$this->labelIdString().'>'.$this->label.'</label><br />';
        }

        if (!$this->choices) {
            return $html;
        }

        foreach ($this->choices as $value => $label) {
            $id = $this->id.'-'.$this->slugify($value);

            if ('before_field' === $this->labelPos) {
                $html .= '<label for="'.$this->escapeHtmlAttr($id).'">'.
                    $label.
                '</label> ';
            }

            $html .= '<input type="radio" '.$this->metaString().
                ' id="'.$this->escapeHtmlAttr($id).
                '" name="'.$this->escapeHtmlAttr($this->name).
                '" value="'.$this->escapeHtmlAttr($value).
                '" '.$this->checked($value, $this->value).' />';

            if ('after_field' === $this->labelPos) {
                $html .= ' <label for="'.
                    $this->escapeHtmlAttr($id).'">'.$label.'</label>';
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
            $this->escapeHtmlAttr($this->id).'" name="'.
            $this->escapeHtmlAttr($this->name).'">';

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
                $this->escapeHtmlAttr($value).'">'.$label.'</option>';
        }

        $html .= '</select>';

        return $html;
    }

    /**
     * Convert meta to string
     */
    protected function metaString(): string
    {
        if (!$this->meta) {
            return '';
        }

        return \join(' ', \array_map(function (string $key, $value): string {
            return $this->slugify($key).'="'.
                $this->escapeHtmlAttr($value).'"';
        }, \array_keys($this->meta), \array_values($this->meta)));
    }

    protected function labelIdString(): string
    {
        return ($this->id ? 'id="'.$this->id.'-label"' : '');
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

    protected function setAttributes(array $args)
    {
        if (!($vars = \get_object_vars($this))) {
            return;
        }

        unset($vars['escaper']);

        unset($args['meta']['id']);
        unset($args['meta']['type']);
        unset($args['meta']['name']);
        unset($args['meta']['value']);

        foreach ($vars as $key => $value) {
            $this->$key = $args[$key] ?? null;
        }
    }

    protected function sanitizeAttributes()
    {
        $this->wrap = $this->wrap ? $this->slugify($this->wrap, '_') : 'p';
        $this->id = $this->slugify((string)$this->id);
        $this->name = $this->slugify((string)$this->name, '-', '[]');

        $this->type = $this->slugify((string)$this->type, '_');
        $this->meta = $this->meta ? (array)$this->meta : [];
        $this->choices = $this->choices ? (array)$this->choices : [];

        $this->label = $this->label ?: '';
        $this->value = $this->value ?: '';

        $this->layout = (\in_array(
            $this->layout,
            ['block', 'inline']
        ) ? $this->layout : 'inline');

        $this->labelPos = (\in_array(
            $this->labelPos,
            ['before_field', 'after_field']
        ) ? $this->labelPos : 'after_field');
    }

    protected function slugify(
        string $string,
        string $replace = '-',
        string $exempt = ''
    ): string {
        if (!$string) {
            return $string;
        }

        $replace = \in_array($replace, ['-', '_', '']) ? $replace : '-';
        $exempt = $exempt ? \preg_quote($exempt, '/') : '';

        return \trim(\preg_replace(
            "/[^a-z\d\-\_$exempt]/",
            $replace,
            \strtolower($string)
        ), " -\t\n\r\0\x0B");
    }

    protected function escapeHtmlAttr($value): string
    {
        return $this->escaper->escapeHtmlAttr("$value");
    }
}
