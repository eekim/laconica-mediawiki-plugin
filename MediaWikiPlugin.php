<?php
/**
 * Laconica, the distributed open-source microblogging tool
 *
 * Plugin to parse MediaWiki-style freelinks into the Wiki of your choice.
 *
 * PHP version 5
 *
 * LICENCE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Plugin
 * @package   Laconica
 * @author    Eugene Eric Kim <eekim@blueoxen.com>
 * @copyright 2009 Blue Oxen Associates
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://laconi.ca/
 *
 * @see      Event
 */

if (!defined('LACONICA')) {
    exit(1);
}

define('MEDIAWIKIPLUGIN_VERSION', '0.1');
define('WIKI_BASE_URL', 'http://foo/wiki/');

/**
 * Plugin to parse MediaWiki-style freelinks into the Wiki of your choice.
 *
 * Before notices are saved, we parse MediaWiki-style freelinks into URLs.
 *
 * @category  Plugin
 * @package   Laconica
 * @author    Eugene Eric Kim <eekim@blueoxen.com>
 * @copyright 2009 Blue Oxen Associates
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://laconi.ca/
 *
 * @see       Event
 */

class MediaWikiPlugin extends Plugin
{
    var $base_url = null;

    function __construct()
    {
        parent::__construct();
    }

    function onStartNoticeSave($notice)
    {
        $notice->rendered = preg_replace_callback('/\[\[[^\]]+\]\]/',
                                          "renderWikiLink",
                                           $notice->rendered);
        return true;
    }

    function userAgent()
    {
        return 'MediaWikiPlugin/'.MEDIAWIKIPLUGIN_VERSION .
          ' Laconica/' . LACONICA_VERSION;
    }
}

function renderWikiLink($match)
{
    $match[0] = preg_replace('/^\[\[/', '', $match[0]);
    $match[0] = preg_replace('/\]\]$/', '', $match[0]);

    $url = preg_replace('/ /', '_', $match[0]);
    $url = WIKI_BASE_URL . $url;
    return "<a href=\"$url\">" . $match[0] . "</a>";
}
