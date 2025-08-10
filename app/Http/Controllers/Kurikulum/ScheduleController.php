<?php

namespace App\Http\Controllers\Kurikulum;

use App\Helpers\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Carbon\Carbon;
use App\Exceptions\{ConflictException, NotFoundException};
use App\Models\Major;
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
                ], [
                'start_time.required'=> 'Jam awal wajib disi',
                'end_time.min'  => 'Jam awal wajib disi',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            TimeSlot::create([
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
                ], [
                'start_time.required'=> 'Jam awal wajib disi',
                'end_time.min'  => 'Jam awal wajib disi',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], HttpCode::UNPROCESABLE_CONTENT);
            }

            $validated = $validator->validate();

            // $validated['start_time'] = substr($validated['start_time'], 0, 5);
            // $validated['end_time'] = substr($validated['end_time'], 0, 5);

            $timeSlot = TimeSlot::findOrFail($timeId);

            $timeSlot->update([
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
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'mesesage'  => $error->getMessage()
            ]);
        }
    }

    public function deleteTimeSlotBy($timeSlotId) {
        try {
            $timeSlot = TimeSlot::where('id', $timeSlotId)->first();

            if ($timeSlot->whereHas('schedules')->exists()) {
                 throw new ConflictException('time slot telah digunakan', [
                    'time_slot_id' => 'time slot telah digunakan'
                ]);
            }

            $timeSlot->delete();

            return response()->json([
                'message'   => 'time deleted successfully'
            ], HttpCode::OK);
        } catch(ConflictException $exception) {
            return $exception->render(request());
        }catch (\Exception $error) {
            return response()->json([
                'message'   => $error->getMessage()
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }
    }
}
