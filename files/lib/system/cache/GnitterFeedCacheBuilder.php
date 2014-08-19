<?php
namespace gms\system\cache\builder;
use wcf\system\cache\builder\AbstractCacheBuilder;

/**
 * Caches gnitter feeds.
 * 
 * @author  Jeffrey Reichardt
 * @copyright	2012-2014 DevLabor UG (haftungsbeschränkt)
 * @license	CC BY-NC-SA 3.0 <http://creativecommons.org/licenses/by-nc-sa/3.0/deed>
 * @package	com.guilded.gms.game.wow
 * @subpackage	system.cache
 * @category	Guilded 2.0
 */
class GnitterFeedCacheBuilder extends AbstractCacheBuilder {
	/**
	 * @see	\wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
	 */
	protected function rebuild(array $parameters) {
		// @todo build gnitter feed upon options
	}
}
