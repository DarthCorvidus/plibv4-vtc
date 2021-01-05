<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
class VTC {
	const RESET = 0;
	const BRIGHT = 1;
	const DIM = 2;
	const UNDERSCORE = 4;
	const BLINK = 5;
	const HIDDEN = 8;
	const BLACK = 30;
	const RED = 31;
	const GREEN = 32;
	const YELLOW = 33;
	const BLUE = 34;
	const MAGENTA = 35;
	const CYAN = 36;
	const WHITE = 37;
	private $foreground;
	private $background;
	private $attributes = array();
	/**
	 * Set foreground color (use class constant)
	 * @param int $color
	 */
	function setForeground(int $color) {
		$this->validateColor($color);
		$this->foreground = $color;
	}
	
	/**
	 * Set background color (use class constant)
	 * @param int $color
	 */
	function setBackground(int $color) {
		$this->validateColor($color);
		$this->background = $color;
	}
	
	/**
	 * Add attribute
	 * @param int $attr
	 */
	function addAttribute(int $attr) {
		$this->validateAttribute($attr);
		if(in_array($attr, $this->attributes)) {
			return;
		}
		$this->attributes[] = $attr;
	}

	/**
	 * Remove attribute
	 * @param int $attr
	 */
	function removeAttribute(int $attr) {
		$new = array();
		foreach ($this->attributes as $value) {
			if($value==$attr) {
				continue;
			}
			$new[] = $value;
		}
		$this->attributes = $new;
	}
	
	/**
	 * Get command for attributes and colors
	 * 
	 * Get command to set the terminal color(s) and attribute(s)
	 * @return type
	 */
	function getAC() {
		$array = array();
		if($this->foreground!==NULL) {
			$array[] = $this->foreground;
		}
		if($this->background!==NULL) {
			$array[] = $this->background+10;
		}
		$merged = array_merge($array, $this->attributes);
	return chr(27)."[". implode(";", $merged)."m";
	}
	/**
	 * Validates color
	 * 
	 * Validates if value is a valid color class constant. If not, an exception
	 * is thrown.
	 * @param int $color
	 * @return type
	 * @throws UnexpectedValueException
	 */
	static function validateColor(int $color) {
		if($color>=self::BLACK && $color<=self::WHITE) {
			return;
		}
	throw new UnexpectedValueException($color." is not a valid color.");
	}
	
	/**
	 * Validates attribute
	 * 
	 * Validates if value is a valid attribute class constant. If not, an
	 * exception is thrown.
	 * @param type $attribute
	 * @return type
	 * @throws UnexpectedValueException
	 */
	static function validateAttribute(int $attribute) {
		if($attribute==self::HIDDEN) {
			return;
		}
		if($attribute>=self::BRIGHT && $attribute<=self::BLINK) {
			return;
		}
	throw new UnexpectedValueException($attribute." is not a valid attribute.");
	}

	/**
	 * Reset foreground color
	 * 
	 * Resets foreground color to the default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetForeground() {
		$this->foreground = NULL;
	}
	
	/**
	 * Reset background color
	 * 
	 * Resets background color to the default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetBackground() {
		$this->background = NULL;
	}
	
	/**
	 * Reset attributes
	 * 
	 * Resets attributes to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetAttributes() {
		$this->attributes = array();
	}
	
	/**
	 * Reset colors
	 * 
	 * Resets colors to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function resetColor() {
		$this->resetForeground();
		$this->resetBackground();
	}
	
	/**
	 * Reset everything
	 * 
	 * Resets everything to default value.
	 * Note that this only resets the internal class value, not the terminal.
	 */
	function reset() {
		$this->resetColor();
		$this->resetAttributes();
	}
	
	/**
	 * Get reset command without changing the internal class value.
	 * @return type
	 */
	static function getReset() {
		return chr(27)."[".self::RESET."m";
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
	 * @param string $string
	 * @return type
	 */
	function getACString(string $string) {
		return $this->getAC().$string.$this->getReset();
	}
	
	
}