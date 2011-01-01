<?php
/*
This file is part of MapleLeaf Guestbook.

MapleLeaf Guestbook is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License  version 2 published by
the Free Software Foundation.

MapleLeaf Guestbook is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/gpl-2.0.html/>.
*/
session_start();
define('IN_MP',true);
define('APPROOT', dirname(__FILE__));
#define('DEBUG_MODE', true);
define('DEBUG_MODE', false);
require_once('./includes/preload.php');
$webapp= ZFramework::createApp();
$webapp->run();