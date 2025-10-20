use App\Http\Controllers\GroupController;

Route::get('/grupos', [GroupController::class, 'index']);
Route::get('/grupos/{group}', [GroupController::class, 'show']);
Route::put('/grupos/{group}', [GroupController::class, 'update']);
Route::patch('/grupos/{group}/desactivar', [GroupController::class, 'deactivate']);
