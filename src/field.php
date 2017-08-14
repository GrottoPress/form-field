<?php

/**
 * Form Field
 *
 * Renders a field based on given args
 *
 * @package GrottoPress\Form
 * @since 0.1.0
 *
 * @author GrottoPress (https://www.grottopress.com)
 * @author N Atta Kus Adusei (https://twitter.com/akadusei)
 */

declare ( strict_types = 1 );

namespace GrottoPress\Form;

use Aura\Html\HelperLocatorFactory as Helper;
use function Stringy\create as S;

/**
 * Form field
 *
 * @since 0.1.0
 */
class Field {
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
	 * @var	array $args Field arguments supplied as associative array
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function __construct( array $args = [] ) {
	    $this->set_attributes( $args );
	    $this->sanitize_attributes();

		$this->escape = ( new Helper() )->newInstance()->escape();
	}

	/**
	 * Render form field.
	 *
	 * @since 0.1.0
	 * @access public
	 *
	 * @return string Form field html.
	 */
	public function render(): string {
		$field = ( string ) S( $this->type )->prepend( 'render_' );
		
		if ( \is_callable( [ $this, $field ] ) ) {
			return $this->render_start() . $this->$field() . $this->render_end();
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
	protected function render_start(): string {
		$html = '<p>';
		
		if ( 'radio' != $this->type ) {
			if ( 'before_field' == $this->label_pos && $this->label ) {
				$html .= '<label for="' . $this->escape->attr( $this->id ) . '" ' . $this->label_id_string() . '>' . $this->label . '</label> ';
			}
			
			if ( 'checkbox' != $this->type ) {
				if ( 'block' == $this->layout ) {
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
	protected function render_end(): string {
		$html = '';
		
		if ( 'radio' != $this->type ) {
			if ( 'checkbox' != $this->type ) {
				if ( 'block' == $this->layout ) {
					$html .= '<br />';
				}
			}
			
			if ( 'after_field' == $this->label_pos && $this->label ) {
				$html .= ' <label for="' . $this->escape->attr( $this->id ) . '" ' . $this->label_id_string() . '>' . $this->label . '</label>';
			}
		}
		
		$html .= '</p>';
		
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
	protected function render_text(): string {
		return '<input type="text" ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '" value="' . $this->escape->attr( $this->value ) . '" />';
	}
	
	/**
	 * Render email field.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_email(): string {
		return '<input type="email" ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '" value="' . $this->escape->attr( $this->value ) . '" />';
	}

	/**
	 * Render number field.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_number(): string {
		return '<input type="number" ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '" value="' . $this->escape->attr( $this->value ) . '" />';
	}
	
	/**
	 * Render URL field.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_url(): string {
		return '<input type="text" ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '" value="' . $this->escape->attr( $this->value ) . '" />';
	}
	
	/**
	 * Render textarea.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_textarea(): string {
		return '<textarea ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '">' . $this->escape->attr( $this->value ) . '</textarea>';
	}

	/**
	 * Render checkbox field.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_checkbox(): string {
		return '<input type="checkbox" ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '" value="1" ' . $this->checked( 1, $this->value ) . ' />';
	}

	/**
	 * Render radio.
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Form field html.
	 */
	protected function render_radio(): string {
		$html = '';
		
		if ( $this->label ) {
			$html .= '<label for="' . $this->escape->attr( $this->id ) . '" ' . $this->label_id_string() . '>' . $this->label . '</label><br />';
		}

		if ( ! $this->choices ) {
			return $html;
		}
		
		foreach ( $this->choices as $value => $label ) {
			$id = $this->id . '-' . S( $value )->slugify();

			if ( 'before_field' == $this->label_pos ) {
				$html .= '<label for="' . $this->escape->attr( $id ) . '">' . $label . '</label> ';
			}
			
			$html .= '<input type="radio" ' . $this->meta_string() . ' id="' . $this->escape->attr( $id )
				. '" name="' . $this->escape->attr( $this->name ) . '" value="' . $this->escape->attr( $value ) . '" '
				. $this->checked( $value, $this->escape->attr( $this->value ) ) . ' />';
				
			if ( 'after_field' == $this->label_pos ) {
				$html .= ' <label for="' . $this->escape->attr( $id ) . '">' . $label . '</label>';
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
	protected function render_select(): string {
		if ( ! $this->choices ) {
			return '';
		}

		$html = '<select ' . $this->meta_string() . ' id="' . $this->escape->attr( $this->id ) . '" name="' . $this->escape->attr( $this->name ) . '">';
			
		foreach ( $this->choices as $value => $label ) {
			if ( isset( $this->meta['multiple'] ) ) {
				$selected = $this->selected( $value, ( \in_array( $value, ( array ) $this->value ) ? $value : '' ), false );
			} else {
				$selected = $this->selected( $value, $this->value );
			}
			
			$html .= '<option ' . $selected . ' value="' . $this->escape->attr( $value ) . '">' . $label . '</option>';
		}
			
		$html .= '</select>';
		
		return $html;
	}

	/**
	 * Set attributes
	 *
	 * @var array $args	Arguments supplied to this object.
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	protected function set_attributes( array $args ) {
		if ( ! $args ) {
			return;
		}

		unset( $args['meta']['id'] );
		unset( $args['meta']['type'] );
		unset( $args['meta']['name'] );
		unset( $args['meta']['value'] );

		$vars = \get_object_vars( $this );

		foreach ( $vars as $key => $value ) {
			$this->$key = $args[ $key ] ?? '';
		}
	}

	/**
	 * Sanitize attributes
	 *
	 * @since 0.1.0
	 * @access protected
	 */
	protected function sanitize_attributes() {
		$this->id = S( $this->id )->slugify();
		$this->name = S( $this->name )->toAscii()->regexReplace( '[^\w\d\[\]\-\_]', '' );
		$this->type = S( $this->type )->slugify();
		$this->meta = ( array ) $this->meta;
		$this->choices = ( array ) $this->choices;

		$this->layout = ( \in_array( $this->layout, [ 'block', 'inline' ] )
			? $this->layout : 'inline' );

		$this->label_pos = ( \in_array( $this->label_pos, [ 'before_field', 'after_field' ] )
			? $this->label_pos : 'after_field' );
	}

	/**
	 * Convert meta to string
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Meta string.
	 */
	protected function meta_string(): string {
		if ( ! $this->meta ) {
			return '';
		}
		
		$meta_string = '';

		\array_walk( $this->meta, function ( string $value, string $key ) use ( &$meta_string ) {
			$meta_string .= S( $key )->slugify() . '="' . $this->escape->attr( $value ) . '" ';
		} );

		return trim( $meta_string );
	}

	/**
	 * Label ID
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string Label ID.
	 */
	protected function label_id_string(): string {
		if ( ! $this->id ) {
			return '';
		}
		
		return 'id="' . $this->id . '-label"';
	}

	/**
	 * Build 'selected' html attribute for dropdowns
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string 'selected' html attribute
	 */
	protected function selected( $a, $b ): string {
		return ( $a == $b ? 'selected="selected"' : '' );
	}

	/**
	 * Build 'checked' html attribute for radio and checkbox
	 *
	 * @since 0.1.0
	 * @access protected
	 *
	 * @return string 'checked' html attribute
	 */
	protected function checked( $a, $b ): string {
		return ( $a == $b ? 'checked="checked"' : '' );
	}
}
