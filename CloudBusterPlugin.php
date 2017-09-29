<?php
/**
 * CloudBuster plugin for Craft CMS
 *
 * Busts caching on Cloudflare when entries or assets are updated/uploaded.
 *
 * @author    Adam Burton
 * @copyright Copyright (c) 2017 Adam Burton
 * @link      https://bluestorm.design
 * @package   CloudBuster
 * @since     1.0.0
 */

namespace Craft;

class CloudBusterPlugin extends BasePlugin
{
	public function init()
	{
		parent::init();

		$events = [
			'entries.onSaveEntry' => function(Event $event) { return $event->params['entry']['url']; },
			'entries.onDeleteEntry' => function(Event $event) { return $event->params['entry']['url']; },
			'categories.onSaveCategory' => function(Event $event) { return $event->params['category']['url']; },
			'categories.onDeleteCategory' => function(Event $event) { return $event->params['category']['url']; },
			'assets.onSaveAsset' => function(Event $event) { return $event->params['asset']['url']; },
			'assets.onDeleteAsset' => function(Event $event) { return $event->params['asset']['url']; }
		];

		foreach($events as $event => $callback)
		{
			craft()->on($event, function(Event $event) use ($callback)
			{
				try
				{
					$url = $callback($event);

					if($url)
					{
						craft()->cloudBuster->purge($url);
					}
				}
				catch(\Exception $e)
				{
					// Callback failed, probably

					self::log($e->getMessage(), LogLevel::Error);

					throw $e;
				}
			});
		}
	}

	public function getName()
	{
		return Craft::t('CloudBuster');
	}

	public function getDescription()
	{
		return Craft::t('Busts caching on Cloudflare when entries or assets are updated/uploaded.');
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/bluestorm/cloudbuster/blob/master/README.md';
	}

	public function getVersion()
	{
		return '1.0.0';
	}

	public function getSchemaVersion()
	{
		return '1.0.0';
	}

	public function getDeveloper()
	{
		return 'Adam Burton';
	}

	public function getDeveloperUrl()
	{
		return 'https://bluestorm.design';
	}

	public function hasCpSection()
	{
		return true;
	}

	protected function defineSettings()
	{
		return [
			'apiKey' => [ AttributeType::String, 'required' => true, 'label' => 'API Key' ],
			'zone' => [ AttributeType::String, 'required' => true, 'label' => 'Zone ID' ],
			'email' => [ AttributeType::String, 'required' => true, 'label' => 'Cloudflare Account Email' ]
		];
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('cloudbuster/_settings', [
			'settings' => $this->getSettings()
		]);
	}
}
