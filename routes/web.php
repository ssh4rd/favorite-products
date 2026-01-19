<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => []], function () {
    Route::get('/api/documentation', function () {
        $documentation = 'default';
        $config = config('l5-swagger.documentations.' . $documentation);
        $urlToDocs = route('l5-swagger.' . $documentation . '.docs', [], true);

        $operationsSorter = config('l5-swagger.defaults.operations_sort', null);
        $configUrl = config('l5-swagger.defaults.additional_config_url', null);
        $validatorUrl = config('l5-swagger.defaults.validator_url', null);
        $useAbsolutePath = config('l5-swagger.documentations.' . $documentation . '.paths.use_absolute_path', true);

        $urlsToDocs = [$config['api']['title'] => $urlToDocs];
        $documentationTitle = $config['api']['title'];

        return view('vendor.l5-swagger.index', compact(
            'documentation',
            'urlsToDocs',
            'operationsSorter',
            'configUrl',
            'validatorUrl',
            'useAbsolutePath',
            'documentationTitle'
        ));
    })->name('l5-swagger.default.api');

    Route::get('/docs', function () {
        $path = storage_path('api-docs/api-docs.json');

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/json'
        ]);
    })->name('l5-swagger.default.docs');

    Route::get('/docs/{jsonFile}', function ($jsonFile) {
        $path = storage_path('api-docs/' . $jsonFile);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/json'
        ]);
    })->name('l5-swagger.default.docs.file');

    // Swagger UI Assets
    Route::get('/docs/asset/{asset}', function ($asset) {
        $assetPath = base_path('vendor/swagger-api/swagger-ui/dist/' . $asset);

        if (!file_exists($assetPath)) {
            abort(404);
        }

        $mimeType = match(pathinfo($asset, PATHINFO_EXTENSION)) {
            'js' => 'application/javascript',
            'css' => 'text/css',
            'png' => 'image/png',
            'ico' => 'image/x-icon',
            default => 'text/plain'
        };

        return response()->file($assetPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    })->where('asset', '.*')->name('l5-swagger.default.asset');
});
