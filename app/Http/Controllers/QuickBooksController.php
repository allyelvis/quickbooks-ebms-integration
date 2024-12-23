<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Exception\ServiceException;
use Illuminate\Support\Facades\Cache;

class QuickBooksController extends Controller
{
    public function connect()
    {
        $config = config('quickbooks');
        $dataService = DataService::Configure($config);

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        $config = config('quickbooks');
        $code = $request->input('code');
        $realmId = $request->input('realmId');

        try {
            $dataService = DataService::Configure($config);
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);

            Cache::put('quickbooks_access_token', $accessToken->getAccessToken(), now()->addMinutes(50));
            Cache::put('quickbooks_refresh_token', $accessToken->getRefreshToken(), now()->addDays(100));

            return response()->json(['message' => 'QuickBooks authenticated successfully.']);
        } catch (ServiceException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
