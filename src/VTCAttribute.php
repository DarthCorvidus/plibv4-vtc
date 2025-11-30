<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
namespace plibv4\vtc;
enum VTCAttribute: int {
	case RESET = 0;
	case BRIGHT = 1;
	case DIM = 2;
	case UNDERSCORE = 4;
	case BLINK = 5;
	case HIDDEN = 8;
}
