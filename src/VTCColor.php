<?php
/**
 * @copyright (c) 2021, Claus-Christoph Küthe
 * @author Claus-Christoph Küthe <plibv4@vm01.telton.de>
 * @license LGPLv2.1
 */
namespace plibv4\vtc;
enum VTCColor: int {
	case NONE = 0;
	case BLACK = 30;
	case RED = 31;
	case GREEN = 32;
	case YELLOW = 33;
	case BLUE = 34;
	case MAGENTA = 35;
	case CYAN = 36;
	case WHITE = 37;
}
