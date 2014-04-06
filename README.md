## FitbitBundle ##

This project is a bundle suitable for use in Symfony >=2.3.x for interfacing to [FitBit](http://fitbit.com)'s
[REST API](http://dev.fitbit.com).

Please note that the FitBit API is considered to be a **beta** release.  As such, this bundle should also be
considered to be a **beta** release.

## Credits ##

This repo has been branched from [jsamos/fitbitphp](https://github.com/jsamos/fitbitphp) which was branched from
[popthestack/fitbitphp](https://github.com/popthestack/fitbitphp) which was branched from
[TheSaviour/fitbitphp](https://github.com/TheSaviour/fitbitphp) which was originally branched from
[heyitspavel/fitbitphp](https://github.com/heyitspavel/fitbitphp).

## Installation ##

This package can be installed with composer.  Simply add the following to your composer.json within the require section:
```json
{
	[...]
	"require": {
		[...]
		"nibynool/fitbit-bundle": "~0.1.0",
		[...]
	},
	[...]
}
```

You will also need to call this package from your AppKernel.php by adding the following to your $bundles array:
```php
class AppKernel extends Kernel
{
	[...]
	public function registerBundles()
	{
		$bundles = array(
			[...]
			new NibyNool\FitBitBundle\NibyNoolFitBitBundle(),
			[...]
		);
		[...]
	}
	[...]
}
```

Prior to use, you will require a consumer key and secret.  These can be obtained by registering an application with
[FitBit](https://dev.fitbit.com/apps/new).  If you have already registered an application you can get your key and
secret from [FitBit](https://dev.fitbit.com/apps).

You will need to add your consumer key and secret as well as your callback url to your parameters.yml file:
```yaml
parameters:
    fitbit_key:      <consumer_key>
    fitbit_secret:   <consumer_secret>
    fitbit_callback: http://your.site.com/callback/url
```

## Usage ##

Usage is fairly simple, although there is still room to optimise it further.  Simply put the following in a controller
and adjust it to your needs:
```php
// Acme/DemoBundle/Controller/FitBitController.php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NibyNool\FitBitBundle\FitBit\TokenStorage;

class FitBitController extends Controller
{
	public function AllInOneAction()
	{
		// Begin by calling the FitBit Service
		/** @var \NibyNool\FitBitBundle\FitBit\ApiGatewayFactory $fitbit **/
		$fitbit = $this->get('fitbit');

		// Set the storage adapter
		/** @var \NibyNool\FitBitBundle\FitBit\TokenStorage $tokenStorage **/
		$tokenStorage = new TokenStorage();
		$fitbit->setStorageAdapter($tokenStorage->getApapter());

		/** @var \Symfony\Component\HttpFoundation\Request $request */
		$request = $this->get('request');

		// Get the FitBit authentication gateway
		/** @var \NibyNool\FitBitBundle\FitBit\AuthenticationGateway $fitbitAuthGateway */
		$fitbitAuthGateway = $fitbit->getAuthenticationGateway();

		if ($request->query->get('oauth_token') && $request->query->get('oauth_verifier'))
		{   // These parameters are passed back from FitBit, so if we get them then we can try and
		    // authenticate.  Ideally we should check the referrer here to make sure the request
		    // really is from FitBit.
		    $fitbitAuthGateway->authenticateUser(
		        $request->query->get('oauth_token'),
		        $request->query->get('oauth_verifier')
		    );
		    /** @var \OAuth\Common\Storage\TokenStorageInterface $storage */
		    $storage = $fitbit->getStorageAdapter();
		    /** @var \OAuth\OAuth1\Token\TokenInterface $token */
		    $token   = $storage->retrieveAccessToken('FitBit');
		    $oauth_access_token  = $token->getRequestToken();
		    $oauth_access_secret = $token->getRequestTokenSecret();
		    // At this point we can save the access token and secret so we can reload it when
		    // required (maybe as part of the user login process)
		}
		elseif ($request->query->get('connect'))
		{   // Redirect to FitBit to login
		    $fitbitAuthGateway->initiateLogin();
		}

		if ($fitbitAuthGateway->isAuthorized())
		{   // Once we've confirmed a successful login we can store the auth token
		    /** @var \NibyNool\FitBitBundle\FitBit\UserGateway $fitbitUserGateway */
		    $fitbitUserGateway = $fitbit->getUserGateway();
		    echo '<pre>';
		    print_r($fitbitUserGateway->getProfile());
		    echo '</pre>';
		}
		else
		{   // We aren't trying to do anything, so just display a message
		    echo 'Not connected.';
		}
	}

	public function RequestAuthorisationAction()
	{
		// Begin by calling the FitBit Service
		/** @var \NibyNool\FitBitBundle\FitBit\ApiGatewayFactory $fitbit **/
		$fitbit = $this->get('fitbit');

		// Set the storage adapter
		/** @var \NibyNool\FitBitBundle\FitBit\TokenStorage $tokenStorage **/
		$tokenStorage = new TokenStorage();
		$fitbit->setStorageAdapter($tokenStorage->getApapter());

		// Get the FitBit authentication gateway
		/** @var \NibyNool\FitBitBundle\FitBit\AuthenticationGateway $fitbitAuthGateway */
		$fitbitAuthGateway = $fitbit->getAuthenticationGateway();

		// Redirect to FitBit to login
		$fitbitAuthGateway->initiateLogin();
	}

	public function HandleCallbackAction()
	{
		// Begin by calling the FitBit Service
		/** @var \NibyNool\FitBitBundle\FitBit\ApiGatewayFactory $fitbit **/
		$fitbit = $this->get('fitbit');

		// Set the storage adapter
		/** @var \NibyNool\FitBitBundle\FitBit\TokenStorage $tokenStorage **/
		$tokenStorage = new TokenStorage();
		$fitbit->setStorageAdapter($tokenStorage->getApapter());

		// Get the request
		/** @var Request $request */
		$request = $this->get('request');

		// Get the FitBit authentication gateway
		/** @var \NibyNool\FitBitBundle\FitBit\AuthenticationGateway $fitbitAuthGateway */
		$fitbitAuthGateway = $fitbit->getAuthenticationGateway();

		// Ensure we have the required callback request parameters
		if (!$request->query->get('oauth_token') || !$request->query->get('oauth_verifier'))
			throw new HttpException(400, 'Insufficient data provided');

		// Process the authentication
		$fitbitAuthGateway->authenticateUser(
			$request->query->get('oauth_token'),
			$request->query->get('oauth_verifier')
		);

		// Ensure the authentication worked
		if (!$fitbitAuthGateway->isAuthorized())
			throw new HttpException(401, 'Invalid Authentication Provided');

		// Get the access token data and save it
		/** @var TokenStorageInterface $storage */
		$storage = $fitbit->getStorageAdapter();
		/** @var TokenInterface1 $token */
		$token = $storage->retrieveAccessToken('FitBit');

		// This function should save the request token and associated secret for later use
		customSaveFunction($token->getRequestToken(), $token->getRequestTokenSecret());
	}

	public function ShowFitBitProfileAction()
	{
		// This function should load the request token and associated secret as saved previously
		list($token, $secret) = customLoadFunction();

		// Call the FitBit Service
		/** @var \NibyNool\FitBitBundle\FitBit\ApiGatewayFactory $fitbit **/
		$fitbit = $this->get('fitbit');

		// Set the storage adapter
		/** @var \NibyNool\FitBitBundle\FitBit\TokenStorage $tokenStorage **/
		$tokenStorage = new TokenStorage($token, $secret);
		$fitbit->setStorageAdapter($tokenStorage->getApapter());

		// Get the FitBit authentication gateway
		/** @var \NibyNool\FitBitBundle\FitBit\AuthenticationGateway $fitbitAuthGateway */
		$fitbitAuthGateway = $fitbit->getAuthenticationGateway();

		if ($$fitbitAuthGateway->isAuthorized())
		{
		    /** @var FitBitUser $fitbitUser */
		    $fitbitUser = $fitbit->getUserGateway();
		    /** @var array $fitbitProfile */
		    $fitbitProfile = $fitbitUser->getProfile();
		}
		else
		{
			// Error
		}
	}
}
```

## EndPoint Test Status ##

FitBit has a large number of API end points.  To help navigate through these with this bundle here's
a matrix to display the end point, the data available and the date this bundle was last tested with
the end point.

End Point | API Call | Last Test
----------|----------|----------
[Get User Info](https://wiki.fitbit.com/display/API/API-Get-User-Info)|$fitbit->getUserGateway()->getProfile()|2014-04-06
[Update User Info](https://wiki.fitbit.com/display/API/API-Update-User-Info)|$fitbit->getUserGateway()->updateProfileFromArray($array)|

## Development Notes ##

This project is being developed with a GitFlow structure.  If you are developing and code for this
project, please respect and honour this methodology.

## FitBitBundle TODO ##

[ ] Test API Calls
[ ] Release first stable version
[ ] Write test functions
[ ] Adjust OAuth use to prefer built-in PHP OAuth if available
[ ] Write example code for "Login with FitBit"
[ ] Develop test site to demonstrate all functionality
[ ] Contact FitBit to get bundle added to dev.fitbit.com