<?php
/**
 * CloudBuster plugin for Craft CMS
 *
 * CloudBuster_CloudBuster Service
 *
 * --snip--
 * All of your plugin’s business logic should go in services, including saving data, retrieving data, etc. They
 * provide APIs that your controllers, template variables, and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 * --snip--
 *
 * @author    Adam Burton
 * @copyright Copyright (c) 2017 Adam Burton
 * @link      https://bluestorm.design
 * @package   CloudBuster
 * @since     1.0.0
 */

namespace Craft;

class CloudBusterService extends BaseApplicationComponent
{
	public function purge($urls)
	{
		$settings = craft()->plugins->getPlugin('cloudbuster')->getSettings();

		if(!$settings->apiKey || !$settings->email || !$settings->zone)
		{
			CloudBusterPlugin::log('Please configure your API key, email and zone in the plugin settings.');

			return;
		}

		$headers = [
			'Content-Type' => 'application/json',
			'X-Auth-Key' => $settings->apiKey,
			'X-Auth-Email' => $settings->email
		];

		$urls = !is_array($urls) ? [ $urls ] : $urls;

		try
		{
			CloudBusterPlugin::log('Config: [API key] ' . $settings->apiKey . ' [Email] ' . $settings->email . ' [Zone] ' . $settings->zone, LogLevel::Info);
			CloudBusterPlugin::log('Busting: ' . implode(', ', $urls), LogLevel::Info);

			$client = new \Guzzle\Http\Client();

			$request = $client->delete('https://api.cloudflare.com/client/v4/zones/' . $settings->zone . '/purge_cache', $headers, json_encode([ 'files' => $urls ]));
			$response = $request->send();
		}
		catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
		{
			$data = (string) $e->getResponse()->getBody();
			$json = json_decode($data);

			foreach($json->errors as $error)
			{
				CloudBusterPlugin::log($error->message, LogLevel::Error);
			}
		}
		catch(\Exception $e)
		{
			CloudBusterPlugin::log($e->getMessage(), LogLevel::Error);
		}
	}
}
