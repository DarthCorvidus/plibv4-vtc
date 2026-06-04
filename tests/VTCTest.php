<?php
declare(strict_types=1);
namespace plibv4\vtc;
use PHPUnit\Framework\TestCase;
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
final class VTCTest extends TestCase {
	static function hex(string $string): string {
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
	
	function testSetForeground(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}

	function testSetForegroundNone(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
		$vtc->setForeground(VTCColor::NONE);
		$this->assertEquals("", $this->hex($vtc->getAC()));
	}
	
	function testBackground(): void {
		$vtc = new VTC();
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
	}

	function testBackgroundNone(): void {
		$vtc = new VTC();
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
		$vtc->setBackground(VTCColor::NONE);
		$this->assertEquals("", $this->hex($vtc->getAC()));

	}
	
	function testForegroundAndBackground(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
	}
	
	function testAttributeDim(): void {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value."m"), $this->hex($vtc->getAC()));
	}

	function testSetAttributes(): void {
		$vtc = new VTC();
		$vtc->setAttributes([VTCAttribute::DIM, VTCAttribute::BLINK]);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value.";".VTCAttribute::BLINK->value."m"), $this->hex($vtc->getAC()));
	}

	function testAttributeDimAndUnderscore(): void {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}

	function testRemoveAttributeDim(): void {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->removeAttribute(VTCAttribute::DIM);
		$this->assertEquals($this->hex(chr(27)."[". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}
	
	function testDuplicateAttributeNotAdded(): void {
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::DIM);
		$this->assertEquals(
			$this->hex(chr(27)."[".VTCAttribute::DIM->value."m"),
			$this->hex($vtc->getAC())
		);
	}

	function testResetForeground(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetForeground();
		$this->assertEquals($this->hex(chr(27)."[41m"), $this->hex($vtc->getAC()));
		
	}
	
	function testResetBackground(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals($this->hex(chr(27)."[31;41m"), $this->hex($vtc->getAC()));
		$vtc->resetBackground();
		$this->assertEquals($this->hex(chr(27)."[31m"), $this->hex($vtc->getAC()));
	}
	
	function testResetAttributes(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";". VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->resetAttributes();
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value."m"), $this->hex($vtc->getAC()));
	}
	
	function testResetColor(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::BLUE);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".(VTCColor::BLUE->value+10).";". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->resetColor();
		$this->assertEquals($this->hex(chr(27)."[".VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
	}

	
	function testResetAll(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".VTCAttribute::DIM->value.";".VTCAttribute::UNDERSCORE->value."m"), $this->hex($vtc->getAC()));
		$vtc->reset();
		$this->assertEquals($this->hex(""), $this->hex($vtc->getAC()));
	}

	function testGetReset(): void {
		$this->assertEquals($this->hex(chr(27)."[0m"), $this->hex(VTC::getReset()));
	}
	
	function testGetStringRedForegroundBlueBackgroundDimmedUnderscore(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$vtc->setBackground(VTCColor::BLUE);
		$vtc->addAttribute(VTCAttribute::DIM);
		$vtc->addAttribute(VTCAttribute::UNDERSCORE);
		$this->assertEquals($this->hex(chr(27)."[".VTCColor::RED->value.";".(VTCColor::BLUE->value+10).";". VTCAttribute::DIM->value.";". VTCAttribute::UNDERSCORE->value."mString".chr(27)."[0m"), $this->hex($vtc->getACString("String")));
	}
	
	function testNeutral(): void {
		$vtc = new VTC();
		$this->assertEquals(true, $vtc->isNeutral());
	}

	function testNotNeutral(): void {
		$vtc = new VTC();
		$vtc->setForeground(VTCColor::RED);
		$this->assertEquals(false, $vtc->isNeutral());
		$vtc = new VTC();
		$vtc->setBackground(VTCColor::RED);
		$this->assertEquals(false, $vtc->isNeutral());
		$vtc = new VTC();
		$vtc->addAttribute(VTCAttribute::DIM);
		$this->assertEquals(false, $vtc->isNeutral());
	}

	function testGetNeutralAC(): void {
		$vtc = new VTC();
		$this->assertEquals("", $this->hex($vtc->getAC()));
	}
	
	function testNeutralString(): void {
		$vtc = new VTC();
		$this->assertEquals("String", $this->hex($vtc->getACString("String")));
	}
}
