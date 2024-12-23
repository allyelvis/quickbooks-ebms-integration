<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;

class QuickBooksController extends Controller
{
    public function connect()
    {
        $config = config('quickbooks');
        $dataService = DataService::Configure($config);

        // Get OAuth URL for QuickBooks
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        return redirect($authUrl);
    }
}
