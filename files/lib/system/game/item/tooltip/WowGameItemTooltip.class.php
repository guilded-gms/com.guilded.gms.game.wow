<?php
namespace gms\system\game\item\tooltip;

/**
 * Implementation of specified tooltip for World of Warcraft.
 *
 * @author	Jeffrey Reichardt
 * @copyright	2012-2014 DevLabor UG (haftungsbeschränkt)
 * @license	Creative Commons 3.0 <BY-NC-SA> <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.wow
 * @subpackage	system.game.provider
 * @category	Guilded 2.0
 */
class WowGameItemTooltip extends AbstractGameItemTooltip implements IGameItemTooltip {	
	/**
	 * @see wcf\system\game\item\tooltip\AbstractGameItemTooltip::$templateName
	 */
	protected $templateName = 'wowGameItemTooltip';
}
