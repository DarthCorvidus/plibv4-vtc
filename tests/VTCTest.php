<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
class VTCTest extends TestCase {
	static function hex($string) {
		$new = "";
		for($i=0;$i<strlen($string);$i++) {
			if($string[$i]==chr(27)) {
				$new .= "0x27";
				continue;
			}
			$new .= $string[$i];
		}
	return $new;
	}
	
	function testForeground() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}
	
	function testBackground() {
		$vtc = new VTC();
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
	}
	
	function testForegroundAndBackground() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
	}
	
	function testAttributeDim() {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value."m"), $this->hex($vtc->getAC()));
	}

	function testAttributeDimAndUnderscore() {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}

	function testRemoveAttributeDim() {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->removeAttribute(VTCAttribute::DIM);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}
	
	function testResetForeground() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetForeground();
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
		
	}
	
	function testResetBackground() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetBackground();
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}
	
	function testResetAttributes() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";". VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->resetAttributes();
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value."m"), $this->hex($vtc->getAC()));
	}
	
	function testResetColor() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::BLUE);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".(VTCColor::BLUE->value+10).";". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->resetColor();
		$this->assertEquals($this->hex(chr(27)."[".VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}

	
	function testResetAll() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->reset();
		$this->assertEquals($this->hex(""), $this->hex($vtc->getAC()));
	}

	function testGetReset() {
		$this->assertEquals($this->hex(chr(27)."[0m"), $this->hex(VTC::getReset()));
	}
	
	function testGetStringRedForegroundBlueBackgroundDimmedUnderscore() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::BLUE);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".(VTCColor::BLUE->value+10).";". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."mString".chr(27)."[0m"), $this->hex($vtc->getACString("String")));
	}
	
	function testNeutral() {
		$vtc = new VTC();
		$this->assertEquals(TRUE, $vtc->isNeutral());
	}

	function testNotNeutral() {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$this->assertEquals(FALSE, $vtc->isNeutral());
		$vtc = new VTC();
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals(FALSE, $vtc->isNeutral());
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$this->assertEquals(FALSE, $vtc->isNeutral());
	}

	function testGetNeutralAC() {
		$vtc = new VTC();
		$this->assertEquals("", $this->hex($vtc->getAC()));
	}
	
	function testNeutralString() {
		$vtc = new VTC();
		$this->assertEquals("String", $this->hex($vtc->getACString("String")));
	}
}
