<?php
namespace gms\system\dashboard\box;
use gms\data\game\Game;
use gms\data\guild\GuildList;
use gms\page\GuildPage;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\WCF;

/**
 * DashboardBox for showing server status.
 *
 * @author	Jeffrey Reichardt
 * @copyright	Creative Commons 3.0 <BY-NC-SA> <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.wow
 * @subpackage	system.dashboard.box
 * @category	Guilded 2.0
 */
class GnitterDashboardBox extends AbstractSidebarDashboardBox {
	/**
	 * guild list
	 * @var	array<\gms\data\guild\Guild>
	 */
	public $guilds = null;

	/**
	 * feed entries
	 * @var    array
	 */
	public $entries = array();
	
	/**
	 * @see	\wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);

		$game = Game::getGameByAbbreviation('wow');

		// show specific entries for GuildPage
		if ($page instanceof GuildPage && $page->guild !== null) {
			if ($page->guild->gameID != $game->gameID) {
				return;
			}

			$this->guilds[] = $page->guild;
		}
		else {
			$guildList = new GuildList();
			$guildList->getConditionBuilder()->add('guild.gameID = ?', array($game->gameID));
			$guildList->getConditionBuilder()->add('guild.isSystem = ?', array(1));
			$guildList->readObjects();
			$this->guilds = $guildList->getObjects();
		}

		// @todo get entries from rss feed (CacheBuilder)

		$this->fetched();
	}
	
	/**
	 * @see	\wcf\system\dashboard\box\AbstractContentDashboardBox::render()
	 */
	protected function render() {
		if (!count($this->entries)) {
			return '';
		}
		
		WCF::getTPL()->assign(array(
			'entries' => $this->entries
		));
		
		return WCF::getTPL()->fetch('wowDashboardBoxGnitter');
	}
}
