## FitbitBundle ##

This will soon become a bundle suitable for use in Symfony 2.3.x for interfacing to [FitBit](http://fitbit.com)'s
[REST API](http://dev.fitbit.com).

Whilst there are no tags on this project it is not suitable for use.

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
"nibynool/fitbit-bundle": "~1.0.0"
```

You will also need to call this package from your AppKernel.php by adding the following to your $bundles array:
```php
new NibyNool\FitBitBundle\NibyNoolFitBitBundle(),
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
// Begin by calling the FitBit Service
/** @var \NibyNool\FitBitBundle\FitBit\ApiGatewayFactory $fitbit **/
$fitbit = $this->get('fitbit');

// Determine if we already have a session (this is only for SymfonySession as the storage adapter)
/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $this->get('session');
if (!$session->isStarted()) $session->start();
// Set the storage adapter
$fitbit->setStorageAdapter(new \OAuth\Common\Storage\SymfonySession($session));

/** @var \Symfony\Component\HttpFoundation\Request $request */
$request = $this->get('request');

// Get the FitBit authentication gateway
/** @var \NibyNool\FitBitBundle\FitBit\AuthenticationGateway $fitbitAuthGateway */
$fitbitAuthGateway = $fitbit->getAuthenticationGateway();

if ($request->query->get('oauth_token') && $request->query->get('oauth_verifier'))
{   // These parameters are passed back from FitBit, so if we get them then we can try and
    // authenticate.  Ideally we should check the referrer here to make sure the request really is
    // from FitBit.
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
    // At this point we can save the access token and secret so we can reload it when required
    // (maybe as part of the user login process)
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
```

## EndPoint Test Status ##

FitBit has a large number of API end points.  TO help navigate through these with this bundle here's a matrix to display
the end point, the data available and the date this bundle was last tested with the end point.

End Point | API Call | Last Test
----------|----------|----------
[Get User Info](https://wiki.fitbit.com/display/API/API-Get-User-Info)|$fitbit->getUserGateway()->getProfile()|
[Update User Info](https://wiki.fitbit.com/display/API/API-Update-User-Info)|$fitbit->getUserGateway()->updateProfileFromArray($array)|
