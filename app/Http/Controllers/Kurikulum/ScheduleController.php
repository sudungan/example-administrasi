<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index() {
        return view('schedules.index');
    }

    public function storeTimetable(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required',
                'end_time' => 'required',
                'activity'  => 'nullable',
                'category'  => 'required',
                ], [
                'start_time.required'=> 'Jam awal wajib disi',
                'end_time.min'  => 'Jam awal wajib disi',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'activity.nullable'   => 'Aktifitas tidak harus disi..',
                'category.required' => 'kategory jam wajib dipilih..',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            TimeSlot::create([
                'activity'      => $validated['activity'],
                'category'      => $validated['category'],
                'start_time'    => $validated['start_time'],
                'end_time'      => $validated['end_time'],
            ]);

            return response()->json([
                'message'   => 'created successfully'
            ], HttpCode::CREATED);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getTimeSlotBy($timeId) {
        try {
            $timeSlot = TimeSlot::findOrFail($timeId);
            return response()->json([
                'data'  => $timeSlot,
                'message'  => 'get time slot successfully'
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function updateTimeSlotBy(Request $request, $timeId) {
        try {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required',
                'end_time' => 'required',
                'activity'  => 'nullable',
                'category'  => 'required',
                ], [
                'start_time.required'=> 'Jam awal wajib disi',
                'end_time.min'  => 'Jam awal wajib disi',
                'name.regex'   => 'Nama Jurusan hanya boleh berisi huruf dan spasi.',
                'activity.nullable'   => 'Aktifitas tidak harus disi..',
                'category.required' => 'kategory jam wajib dipilih..',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            $validated['start_time'] = substr($validated['start_time'], 0, 5);
            $validated['end_time'] = substr($validated['end_time'], 0, 5);

            $timeSlot = TimeSlot::findOrFail($timeId);
            
            $timeSlot->update([
                'activity'      => $validated['activity'],
                'category'      => $validated['category'],
                'start_time'    => $validated['start_time'],
                'end_time'      => $validated['end_time'],
            ]);

            return response()->json([
                'message'   => 'updated successfully'
            ], HttpCode::OK);
        } catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getListTimetable() {
        try {
            $listTimetable = TimeSlot::get();
            return response()->json([
                'message'   => 'get-list timetable successfully',
                'data'  => $listTimetable,
                'weekdays'  => TimeSlot::getWeekDays(),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'mesesage'  => $error->getMessage()
            ]);
        }
    }
}
