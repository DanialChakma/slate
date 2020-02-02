<?php

namespace App\TokenStore;
use League\OAuth2\Client\Provider\Exception;
use Microsoft\Graph\Connect\Constants;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class TokenCache {
    public function storeTokens($access_token, $refresh_token, $expires) {
        $_SESSION['access_token'] = $access_token;
        $_SESSION['refresh_token'] = $refresh_token;
        $_SESSION['token_expires'] = $expires;
    }

    public function clearTokens() {
        unset($_SESSION['access_token']);
        unset($_SESSION['refresh_token']);
        unset($_SESSION['token_expires']);
    }

    public function getAccessToken() {
        // Check if tokens exist
        if (empty($_SESSION['access_token']) ||
            empty($_SESSION['refresh_token']) ||
            empty($_SESSION['token_expires'])) {
            return '';
        }

        // Check if token is expired
        //Get current time + 5 minutes (to allow for time differences)
        $now = time() + 300;

        if ($_SESSION['token_expires'] <= $now) {
            // Token is expired (or very close to it)
            // so let's refresh

            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId'                => Constants::CLIENT_ID,
                'clientSecret'            => Constants::CLIENT_SECRET,
                'redirectUri'             => Constants::REDIRECT_URI,
                'urlAuthorize'            => Constants::AUTHORITY_URL . Constants::AUTHORIZE_ENDPOINT,
                'urlAccessToken'          => Constants::AUTHORITY_URL . Constants::TOKEN_ENDPOINT,
                'urlResourceOwnerDetails' => '',
                'scopes'                  => Constants::SCOPES
            ]);

            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $_SESSION['refresh_token']
                ]);

                // Store the new values
                $this->storeTokens($newToken->getToken(), $newToken->getRefreshToken(),
                    $newToken->getExpires());

                return $newToken->getToken();
            }
            catch (IdentityProviderException $e) {
                return '';
            }
        }
        else {
            // Token is still valid, just return it
            return $_SESSION['access_token'];
        }
    }
}