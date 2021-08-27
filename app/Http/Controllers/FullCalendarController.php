<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class FullCalendarController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $start = $request->input('start', '');
            $end = $request->input('end', '');

            $data = Event::where('start', '>=', $start)
                ->where('end', '<=', $end)
                ->get(['id', 'title', 'start', 'end']);

            return response()->json($data);
        }
        return view('fullcalendar');
    }

    public function create(Request $request) {
        $data = [
            'title' => $request->input('title', ''),
            'start' => $request->input('start', ''),
            'end' => $request->input('end', ''),
        ];
        $event = Event::create($data);

        return response()->json($data);
    }

    public function update(Request $request) {
        $data = [
            'title' => $request->input('title'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
        ];
        $event = Event::find($request->input('id'))->update($data);

        return response()->json($event);
    }

    public function delete(Request $request) {
        $event = Event::find($request->input('id'))->delete();

        return response()->json($event);
    }
}
