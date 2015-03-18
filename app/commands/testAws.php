<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class testAws extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'test:aws';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$aws = App::make('aws');

		$bucketName = getenv('bucket_name');

		$aws->get('CloudFront')->createDistribution(
			array(
				'CallerReference' => 'test-cloudfront',
				'Aliases' => array(
					'Quantity' => 0
				),
				'DefaultRootObject' => '',
				'Origins' => array(
					'Quantity' => 1,
					'Items' => array(
						array(
							'Id' => 'test-cloudfront',
							'DomainName' => $bucketName . '.s3.amazonaws.com',
							'S3OriginConfig' => array(
								'OriginAccessIdentity' => ''
							)
						)
					)
				),
				'DefaultCacheBehavior' => array(
					'TargetOriginId' => 'test-cloudfront',
					'ForwardedValues' => array(
						'QueryString' => false,
						'Cookies' => array(
							'Forward' => 'none'
						)
					),
					'TrustedSigners' => array(
						'Enabled' => false,
						'Quantity' => 0
					),
					'ViewerProtocolPolicy' => 'allow-all',
					'MinTTL' => 31536000, // Seconds in a year
					'AllowedMethods' => array(
						'Quantity' => 2,
						'Items' => array('GET', 'HEAD')
					)
				),
				'CacheBehaviors' => array(
					'Quantity' => 0,
				),
				'Comment' => 'CDN for testing PHP SDK',
				'Logging' => array(
					'Enabled' => false,
					'IncludeCookies' => true,
					'Bucket' => '',
					'Prefix' => ''
				),
				'PriceClass' => 'PriceClass_All',
				'Enabled' => true
			)
		);
	}

}
