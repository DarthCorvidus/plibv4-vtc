<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
namespace plibv4\vtc;
class VTC {
	private ?int $foreground = null;
	private ?int $background = null;
	/** @var list<int> */
	private array $attributes = array();
	/**
	 * Test if instance is 'neutral'
	 * 
	 * An instance of VTC is considered 'neutral' if no color or attribute is
	 * set.
	 * @return bool
	 */
	function isNeutral(): bool {
		if($this->foreground!==NULL) {
			return FALSE;
		}
		if($this->background!==NULL) {
			return FALSE;
		}
		if(!empty($this->attributes)) {
			return FALSE;
		}
	return TRUE;
	}

	/**
	 * Set foreground color (use class constant)
	 * @param VTCColor $color
	 */
	function setForeground(VTCColor $color): void {
		if($color===VTCColor::RESET) {
			$this->foreground = NULL;
			return;
		}
		$this->foreground = $color->value;
	}
	
	/**
	 * Set background color (use class constant)
	 * @param VTCColor $color
	 */
	function setBackground(VTCColor $color): void {
		if($color===VTCColor::RESET) {
			$this->background = NULL;
			return;
		}
		$this->background = $color->value;
	}
	
	/**
	 * Add attribute
	 * @param VTCAttribute $attr
	 */
	function addAttribute(VTCAttribute $attr): void {
		if(in_array($attr->value, $this->attributes, true)) {
			return;
		}
		$this->attributes[] = $attr->value;
	}
	/**
	 * Set attributes
	 * 
	 * Attributes are replaced by the contents of $attr.
	 * @param list<VTCAttribute> $attr
	 */
	function setAttributes(array $attr): void {
		$this->attributes = array();
		foreach($attr as $value) {
			$this->addAttribute($value);
		}
	}
	
	/**
	 * Remove attribute
	 * @param VTCAttribute $attr
	 */
	function removeAttribute(VTCAttribute $attr): void {
		$this->attributes = array_values(
			array_diff($this->attributes, [$attr->value])
		);
	}
	
	/**
	 * Get command for attributes and colors
	 * 
	 * Get command to set the terminal color(s) and attribute(s)
	 * @return string
	 */
	function getAC(): string {
		if($this->isNeutral()) {
			return "";
		}
		$array = array();
		if($this->foreground!==NULL) {
			$array[] = $this->foreground;
		}
		if($this->background!==NULL) {
			$array[] = $this->background+10;
		}
		$merged = array_merge($array, $this->attributes);
		/** @psalm-suppress MixedArgumentTypeCoercion */
	return chr(27)."[". implode(";", $merged)."m";
	}
	
	/**
	 * Reset foreground color
	 * 
	 * Resets foreground color to the default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetForeground(): void {
		$this->foreground = NULL;
	}
	
	/**
	 * Reset background color
	 * 
	 * Resets background color to the default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetBackground(): void {
		$this->background = NULL;
	}
	
	/**
	 * Reset attributes
	 * 
	 * Resets attributes to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetAttributes(): void {
		$this->attributes = array();
	}
	
	/**
	 * Reset colors
	 * 
	 * Resets colors to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetColor(): void {
		$this->resetForeground();
		$this->resetBackground();
	}
	
	/**
	 * Reset everything
	 * 
	 * Resets everything to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function reset(): void {
		$this->resetColor();
		$this->resetAttributes();
	}
	
	/**
	 * Get reset command without changing the internal class value.
	 * @return string
	 */
	static function getReset(): string {
		return chr(27)."[".VTCAttribute::RESET->value."m";
	}
	
	/**
	 * Get string with attributes and colors, reset afterwards
	 * 
	 * Gets a string with colors and attributes, resetting the terminal to its
	 * default value afterwards.
	 * This is how VTC should be used: set your colors & attributes and print
	 * your string, returning the terminal to its defaults afterwards, allowing
	 * you to echo normal text afterwards. Also, your users won't end up with a
	 * wildly colored terminal should your program end prematurely.
	 * Note that if the instance of VTC is 'neutral', no control sequences will
	 * be added and the input string will be returned unchanged.
	 * @param string $string
	 * @return string
	 */
	function getACString(string $string): string {
		if($this->isNeutral()) {
			return $string;
		}
		return $this->getAC().$string.$this->getReset();
	}
	
	
}