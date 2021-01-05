<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
class VTCTest extends TestCase {
	function testAssertValidColor() {
		for($i=30;$i<=37;$i++) {
			$this->assertEquals(NULL, VTC::validateColor($i));
		}
	}
	
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
	
	function testAssertInvalidColor() {
		$this->expectException(UnexpectedValueException::class);
		VTC::validateColor(17);
	}
	
	function testForeground() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}
	
	function testBackground() {
		$vtc = new VTC();
		$vtc->setBackground(VTC::RED);
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
	}
	
	function testForegroundAndBackground() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->setBackground(VTC::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
	}
	
	function testAttributeDim() {
		$vtc = new VTC();
		$vtc->addAttribute(VTC::DIM);
		$this->assertEquals($this->hex(chr(27)."[".VTC::DIM."m"), $this->hex($vtc->getAC()));
	}

	function testAttributeDimAndUnderscore() {
		$vtc = new VTC();
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
	}

	function testRemoveAttributeDim() {
		$vtc = new VTC();
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
		$vtc->removeAttribute(VTC::DIM);
		$this->assertEquals($this->hex(chr(27)."[".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
	}
	
	function testResetForeground() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->setBackground(VTC::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetForeground();
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
		
	}
	
	function testResetBackground() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->setBackground(VTC::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetBackground();
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}
	
	function testResetAttributes() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::RED.";".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
		$vtc->resetAttributes();
		$this->assertEquals($this->hex(chr(27)."[".VTC::RED."m"), $this->hex($vtc->getAC()));
	}
	
	function testResetColor() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->setBackground(VTC::BLUE);
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::RED.";".(VTC::BLUE+10).";".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
		$vtc->resetColor();
		$this->assertEquals($this->hex(chr(27)."[".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
	}

	
	function testResetAll() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::RED.";".VTC::DIM.";".VTC::UNDERSCORE."m"), $this->hex($vtc->getAC()));
		$vtc->reset();
		$this->assertEquals($this->hex(chr(27)."[m"), $this->hex($vtc->getAC()));
	}

	function testGetReset() {
		$this->assertEquals($this->hex(chr(27)."[0m"), $this->hex(VTC::getReset()));
	}
	
	function testGetStringRedForegroundBlueBackgroundDimmedUnderscore() {
		$vtc = new VTC();
		$vtc->setForeground(VTC::RED);
		$vtc->setBackground(VTC::BLUE);
		$vtc->addAttribute(VTC::DIM);
		$vtc->addAttribute(VTC::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTC::RED.";".(VTC::BLUE+10).";".VTC::DIM.";".VTC::UNDERSCORE."mString".chr(27)."[0m"), $this->hex($vtc->getACString("String")));
	}

}
