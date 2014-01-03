<?php
namespace gms\system\menu\character\profile\content;
use gms\data\character\Character;
use wcf\system\WCF;

class WOWPvPCharacterProfileMenuContent extends GameCharacterProfileMenuContent implements ICharacterProfileMenuContent {
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
