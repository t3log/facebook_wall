<?php
namespace Facebookwall\FacebookWall\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Krzysztof Adamczyk <ka@t3log.pls>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * FacebookController
 */
class FacebookController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	protected  $facebookData  = null;

	/**
	 * This function return Array with Faccebook post
	 *
	 * @return array|void
	 */
	public function initializeAction() {
		$url = $this->settings['graphUrl'].'/'.
			$this->settings['profileId'].
			'/posts/?access_token='.
			$this->settings['appId'].'|'.
			$this->settings['appSecret'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result= json_decode(curl_exec($ch),true);
		if(array_key_exists('error',$result)){
			throw new \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException('Facebook:' . $result['error']['message']);
		}
		$result= $result['data'];


		return $this->facebookData = array_slice($result, 0, $this->settings['limit']);
	}

	/**
	 * override Ts setup
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$mergedSettings = array();
		$this->configurationManager = $configurationManager;
		$tsSettings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);

		$tsSettings = $tsSettings['plugin.']['tx_facebookwall.']['settings.'];

		$flexFormSettings = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
		);

		foreach($flexFormSettings as $key=>$value) {
			if (array_key_exists($key,$flexFormSettings) && !empty($flexFormSettings[$key])) {
				$mergedSettings[$key] = $flexFormSettings[$key];
			}else{
				$mergedSettings[$key] = $tsSettings[$key];
			}
		}

		if(!empty($mergedSettings)){
			$this->settings = $mergedSettings;
		}
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('posts', $this->facebookData);
	}

}