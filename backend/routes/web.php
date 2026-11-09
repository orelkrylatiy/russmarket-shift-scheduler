<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/api/health', fn () => ['status' => 'ok', 'service' => 'russmarket-scheduler']);
Route::get('/api/schedule', function (Request $request) {
    $week = $request->query('week', '2024-06-03');
    return response()->json([
        'employees' => DB::table('employees')->orderBy('name')->get(),
        'shifts' => DB::table('shifts')->whereBetween('date', [$week, date('Y-m-d', strtotime($week.' +6 days'))])->orderBy('date')->get(),
        'templates' => DB::table('shift_templates')->orderBy('name')->get(),
    ]);
});
Route::post('/api/shifts', function (Request $request) {
    $data=$request->validate(['employee'=>'required|string','date'=>'required|date','kind'=>'required|string','start'=>'required|date_format:H:i','end'=>'required|date_format:H:i','color'=>'required|string']);
    $id=DB::table('shifts')->insertGetId($data); return response()->json(DB::table('shifts')->find($id), 201);
});
Route::put('/api/shifts/{id}', function (Request $request, int $id) {
    $data=$request->validate(['employee'=>'required|string','date'=>'required|date','kind'=>'required|string','start'=>'required|date_format:H:i','end'=>'required|date_format:H:i','color'=>'required|string']);
    abort_unless(DB::table('shifts')->where('id',$id)->exists(),404); DB::table('shifts')->where('id',$id)->update($data); return DB::table('shifts')->find($id);
});
Route::delete('/api/shifts/{id}', function (int $id) { DB::table('shifts')->where('id',$id)->delete(); return response()->noContent(); });
