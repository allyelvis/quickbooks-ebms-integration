use App\Http\Controllers\QuickBooksController;

Route::get('/quickbooks/connect', [QuickBooksController::class, 'connect']);
Route::get('/quickbooks/callback', [QuickBooksController::class, 'callback']);
