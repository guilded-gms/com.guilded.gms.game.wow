<?php
namespace gms\system\game\provider;
use gms\data\game\GameServer;
use wcf\util\JSON;

/**
 * Implementation of GameProvider for World of Warcraft
 *
 * @author	Jeffrey Reichardt
 * @copyright	2012-2013 DevLabor UG (haftungsbeschränkt)
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
		// @todo add us/kr/...
		switch(WCF::getLanguage()->languageCode) {			
			case 'de':
			case 'en':
			default: 
				$region = 'eu';
		}

		$this->baseUrl = sprintf($this->baseUrl, $region);

		return $this->buildURL($route, $queries);
	}


	/**
	 * Sending request and returns response data.
	 */
	protected function sendRequest($url) {
		parent::sendRequest($url);

		$this->data = JSON::decode($this->data, false);
	}
}