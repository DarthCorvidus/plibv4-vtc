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
	function setForeground(int $color) {
		$this->foreground = $color;
	}
	
	function setBackground(int $color) {
		$this->background = $color;
	}
	
	function addAttribute(int $attr) {
		if(in_array($attr, $this->attributes)) {
			return;
		}
		$this->attributes[] = $attr;
	}
	
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
	
	function getAC() {
		$array = array();
		if($this->foreground!==NULL) {
			$array[] = $this->foreground;
		}
		if($this->background!==NULL) {
			$array[] = $this->background+10;
		}
		$array = array_merge($array, $this->attributes);
	return chr(27)."[". implode(";", $array)."m";
	}
	
	
	static function validateColor(int $color) {
		if($color==self::RESET) {
			return;
		}
		if($color>=self::BLACK && $color<=self::WHITE) {
			return;
		}
	throw new UnexpectedValueException($color." is not a valid color.");
	}
	
	static function validateAttribute($attribute) {
		if($attribute==self::RESET or $attribute==self::HIDDEN) {
			return;
		}
		if($attribute>=self::BRIGHT && $attribute<=self::BLINK) {
			return;
		}
	throw new UnexpectedValueException($attribute." is not a valid attribute.");
	}

	function resetForeground() {
		$this->foreground = NULL;
	}
	
	function resetBackground() {
		$this->background = NULL;
	}
	
	function resetAttributes() {
		$this->attributes = array();
	}
	
	function resetColor() {
		$this->resetForeground();
		$this->resetBackground();
	}
	
	function reset() {
		$this->resetColor();
		$this->resetAttributes();
	}
	
	static function getReset() {
		return chr(27)."[".self::RESET."m";
	}
	
	function getACString(string $string) {
		return $this->getAC().$string.$this->getReset();
	}
	
	
}