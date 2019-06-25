<?php

namespace App\Http\Controllers\Oauth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use App\Services\Oauth\StoreTokenService;

class AuthController extends Controller
{
    /** @var \League\OAuth2\Client\Provider\GenericProvider */
    private $oauth;

    /**
     * Construct a new Oauth AuthController.
     *
     * @param  \League\OAuth2\Client\Provider\GenericProvider  $oauth
     */
    public function __construct(GenericProvider $oauth)
    {
        $this->oauth = $oauth;
    }

    /**
     * Initiate the sign-in process for Outlook.
     *
     * @return void
     */
    public function signin(Request $request)
    {
        $authorizationUrl = $this->oauth->getAuthorizationUrl();

        $request->session()->put('oauth_state', $this->oauth->getState());

        return redirect($authorizationUrl);
    }

    /**
     * Respond to the Outlook authorization redirect.
     *
     * @return void
     */
    public function gettoken(Request $request)
    {
          if ($request->has('code')) {
            if (! $request->has('state') || ($request->state !== $request->session()->get('oauth_state'))) {
              dump('State provided in redirect does not match expected value.');
              exit();
            }

            // Clear saved state
            $request->session()->forget('oauth_state');

            try {
              $accessToken = $this->oauth->getAccessToken('authorization_code', [
                'code' => $request->code,
              ]);

              StoreTokenService::call($accessToken->getToken(), $accessToken->getRefreshToken(), $accessToken->getExpires());

                return redirect()->route('home');
            } catch (IdentityProviderException $e) {
              exit('ERROR getting tokens: '.$e->getMessage());
            }
            exit();
          }
          elseif ($request->has('error')) {
            exit('ERROR: '.$request->error.' - '.$request->error_description);
          }
    }
}
