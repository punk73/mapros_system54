<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');
    });

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to this item is only for authenticated user. Provide a token in your request!'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);

        $api->get('/me', function (){
            $user = Auth::user();
            return $user->toArray();
        });

        
        
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });

    $api->group(['prefix' => 'ticket_masters'], function (Router $api) {
        $api->post('/', 'App\\Api\\V1\\Controllers\\TicketMasterController@store' );
    });

    $api->group(['prefix' => 'main'], function (Router $api) {
        $api->post('/', 'App\\Api\\V1\\Controllers\\MainController@store' )->name('api-main');
        $api->delete('/', 'App\\Api\\V1\\Controllers\\MainController@destroy' );

    });

    $api->group(['prefix' => 'get_instruction_manual_qty'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\ManualInstructionController@index');
    });

    $api->get('/test', 'App\\Api\\V1\\Controllers\\TestController@index' );
    $api->post('/test', 'App\\Api\\V1\\Controllers\\TestController@store' );

    $api->get('/aoies', 'App\\Api\\V1\\Controllers\\AoiController@index' );


    $api->group(['prefix' => 'configs'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\ConfigController@index' );
    });

    $api->group(['prefix' => 'locations'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\LocationController@index' );
    });

    $api->group(['prefix' => 'model_headers'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\ModelHeaderController@index' );
    });

    $api->group(['prefix' => 'pwbs'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\PwbController@index' );
    });

    $api->group(['prefix' => 'symptoms'], function (Router $api) {
        $api->get('/all', 'App\\Api\\V1\\Controllers\\SymptomController@all' );
    });


    $api->group(['prefix' => 'grades'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\GradeController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\GradeController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\GradeController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\GradeController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\GradeController@show' );
    });

    $api->group(['prefix' => 'lines'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\LineController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\LineController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\LineController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\LineController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\LineController@show' );
    });

    $api->group(['prefix' => 'sequences'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\SequenceController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\SequenceController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\SequenceController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\SequenceController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\SequenceController@show' );
    });

    $api->group(['prefix' => 'accesses'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\AccessController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\AccessController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\AccessController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\AccessController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\AccessController@show' );
    });

    $api->group(['prefix' => 'departments'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\DepartmentController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\DepartmentController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\DepartmentController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\DepartmentController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\DepartmentController@show' );
    });

    $api->group(['prefix' => 'scanners'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\ScannerController@index' );
        $api->get('/all', 'App\\Api\\V1\\Controllers\\ScannerController@all' );

        $api->post('/', 'App\\Api\\V1\\Controllers\\ScannerController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\ScannerController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\ScannerController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\ScannerController@show' );
    });
    
    $api->group(['prefix' => 'linetypes'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\LinetypeController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\LinetypeController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\LinetypeController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\LinetypeController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\LinetypeController@show' );
    });

    $api->group(['prefix' => 'lineprocesses'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\LineprocessController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\LineprocessController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\LineprocessController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\LineprocessController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\LineprocessController@show' );
    });

    $api->group(['prefix' => 'endpoints'], function (Router $api) {
        $api->get('/', 'App\\Api\\V1\\Controllers\\EndpointController@index' );
        $api->post('/', 'App\\Api\\V1\\Controllers\\EndpointController@store' );
        $api->put('/{id}', 'App\\Api\\V1\\Controllers\\EndpointController@update' );
        $api->delete('/{id}', 'App\\Api\\V1\\Controllers\\EndpointController@delete' );
        $api->get('/{id}', 'App\\Api\\V1\\Controllers\\EndpointController@show' );
    });

    $api->post('/configlog', 'App\\Api\\V1\\Controllers\\ConfigLogController@store');
    $api->get('/getconfig', 'App\\Api\\V1\\Controllers\\ConfigLogController@index' );
});
