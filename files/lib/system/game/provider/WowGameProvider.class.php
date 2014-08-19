<?php
namespace gms\system\game\provider;
use gms\data\game\server\GameServer;
use wcf\system\WCF;
use wcf\util\JSON;

/**
 * Implementation of GameProvider for World of Warcraft
 *
 * @author	Jeffrey Reichardt
 * @copyright	2012-2014 DevLabor UG (haftungsbeschränkt)
 * @license	Creative Commons 3.0 <BY-NC-SA> <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.wow
 * @subpackage	system.game.provider
 * @category	Guilded 2.0
 */
class WowGameProvider extends AbstractGameProvider implements IGameProvider {
	/**
	 * @see	\wcf\system\game\provider\AbstractGameProvider::$baseUrl
	 */
	protected $baseUrl  = 'http://%s.battle.net/api/wow/';

	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getServer()
	 */
	public function getServer($name) {
		$realm = $this->getData(array(
			'realm',
			'status'
		), array(
			'realms' => $name
		));

		switch ($realm->population) {
			case 'low': $population = GameServer::POPULATION_LOW;
			break;
			case 'medium': $population = GameServer::POPULATION_MEDIUM;
			break;
			case 'high': $population = GameServer::POPULATION_HIGH;
			break;
			default: $population = GameServer::POPULATION_MEDIUM;
		}

		return new GameServer(null, array(
			'name' => $realm->name,
			'type' => $realm->type,
			'isOnline' => $realm->status,
			'queue' => $realm->queue,
			'population' => $population
		));
	}	
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getCharacter()
	 */
	public function getCharacter($server, $name) {
		$character = $this->getData(array(
			'character',
			$server,
			$name
		), array(
			'fields' => 'guild,items,professions,reputation,stats,talents,reputation,pvp'
		));

		return array(
			'name' => $character->name,
			'level' => $character->level
		);
	}	
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getGuild()
	 */
	public function getGuild($server, $name) {
		$guild = $this->getData(array(
			'guild',
			$server,
			$name
		));

		return array(
			'name' => $guild->name,
			'server' => $guild->realm,
			'level' => $guild->level,
			'members' => $guild->members
		);
	}	
	
	/**
	 * @see	\wcf\system\game\provider\IGameProvider::getItem()
	 */
	public function getItem($itemID) {
		$itemData = $this->getData(array(
			'item',
			$itemID
		));

		return array(
			'id' => $itemData->id,
			'name' => $itemData->name,
			'quality' => $itemData->quality,
			'requiredLevel' => $itemData->requiredLevel
		);
	}
	
	/**
	 * @see	\wcf\system\game\provider\AbstractGameProvider::buildURL()
	 */
	protected function buildURL($route = array(), $queries = array()) {
		if (!isset($queries['locale'])) {
			$queries['locale'] = array('locale' => strtolower(WCF::getLanguage()->languageCode) . '_' . strtoupper(WCF::getLanguage()->countryCode));
		}

		$this->baseUrl = $this->getHostByLocale($queries['locale']);

		return $this->buildURL($route, $queries);
	}

	/**
	 * Returns api host by given locale.
	 *
	 * @param	string	$locale
	 * @return	string
	 */
	protected function getHostByLocale($locale) {
		if ($locale == 'zh_CN') {
			return 'www.battlenet.com.cn/api/wow/';
		}

		switch($locale) {
			case 'en_US':
			case 'es_MX':
			case 'pt_BR':
				$region = 'us';
				break;
			case 'ko_KR':
				$region = 'kr';
				break;
			case 'zh_TW':
				$region = 'tw';
				break;
			case 'en_GB':
			case 'es_ES':
			case 'fr_FR':
			case 'ru_RU':
			case 'de_DE':
			case 'pt_PT':
			case 'it_IT':
			default:
				$region = 'eu';
		}

		return sprintf($this->baseUrl, $region);
	}

	/**
	 * Sending request and returns response data.
	 */
	protected function sendRequest($url) {
		parent::sendRequest($url);

		$this->data = JSON::decode($this->data, false);
	}
}
