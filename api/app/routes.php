<?php

// Route::get('dbmigrate', 'DbmigrateController@index');
Route::get('/', 'BaseController@getIndex');
Route::get('/index.html', 'BaseController@getIndex');
Route::get('/get_etoc.html', 'ETOCController@getForm');
Route::get('/get_etoc_request_pdf.html', 'ETOCController@getFormRequestPdf');
Route::post('/post_etoc', 'ETOCController@postForm');
Route::post('/post_etoc_request_pdf', 'ETOCController@postFormRequestPdf');
Route::post('/post_etoc_fax_disposition', 'ETOCController@postFaxDisposition');
Route::post('/post_etoc_trackedevent', 'ETOCController@postTrackedEvent');


Route::get('/get_epaf.html', 'EPAFController@getForm');
Route::get('/get_epaf_request_pdf.html', 'EPAFController@getFormRequestPdf');
Route::post('/post_epaf', 'EPAFController@postForm');
Route::post('/post_epaf_request_pdf', 'EPAFController@postFormRequestPdf');
Route::post('/post_epaf_fax_disposition', 'EPAFController@postFaxDisposition');
Route::post('/post_epaf_trackedevent', 'EPAFController@postTrackedEvent');





