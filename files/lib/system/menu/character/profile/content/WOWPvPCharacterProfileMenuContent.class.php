<?php
namespace gms\system\menu\character\profile\content;
use gms\data\character\Character;
use wcf\system\WCF;

/**
 * Extends character profile with wow pvp data.
 *
 * @author  Jeffrey Reichardt
 * @copyright	2012-2014 DevLabor UG (haftungsbeschränkt)
 * @license	CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.wow
 * @subpackage	system.menu.character.profile.content
 * @category	Guilded 2.0
 */
class WOWPvPCharacterProfileMenuContent extends AbstractGameCharacterProfileMenuContent implements ICharacterProfileMenuContent {
	/**
	 * @see	\wcf\system\menu\character\profile\content\ICharacterProfileMenuContent::getContent()
	 */
	public function getContent(Character $character) {
		if (!$this->checkGame($character->getGame())) {
			return '';
		}

		return WCF::getTPL()->fetch('wowCharacterProfilePvP');
	}
}
