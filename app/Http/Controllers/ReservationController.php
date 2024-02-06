<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        return csrf_token(); 
    }

    public function list()
    {
        return Reservation::all();
    }

    public function detail($id)
    {
        return Reservation::find($id);
    }

    public function create(Request $req)
    {
        $validatedData = Validator::make($req->all(), [
            'name' => 'required',
            'table_no' => 'required',
            'menu_category' => 'required',
            'datetime_start' => 'date_format:Y-m-d H:i'
        ], [
            'name.required' => 'Fill out the name field!',
            'table_no.required' => 'Fill out the table_no!',
            'menu_category.required' => 'Fill out the menu_category!',
            'datetime_start.required' => 'datetime_start must follow this format : YYYY-MM-DD HH:MM'
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 422);
        }

        $data = [];
        $message = null;
        $table_no = $req->table_no;
        $datetime_start = Carbon::parse($req->datetime_start);

        switch ($req->menu_category) {
            case 'short':
                $datetime_end = $datetime_start->addMinutes(30);
                break;

            case 'middle':
                $datetime_end = $datetime_start->addMinutes(45);
                break;

            case 'long':
                $datetime_end = $datetime_start->addHour();
                break;
            
            default:
                return response()->json(['message' => 'menu_category must be one of : short, middle, or long'], 422);
                break;
        }

        $total_record_same_table_same_time  = DB::table('reservation')
        ->whereIn('table_no',function($query) use ($datetime_start, $datetime_end, $table_no){
			$query->select('table_no')->from('reservation')
			->where('table_no', $table_no)
            ->where(function ($query) use ($datetime_start, $datetime_end)  {
                $query->whereRaw('datetime_start BETWEEN ? AND ?',[$datetime_start,$datetime_end])
                ->whereRaw('? BETWEEN datetime_start AND datetime_end',[$datetime_start])
                ->orWhereRaw('datetime_end BETWEEN ? AND ?',[$datetime_start,$datetime_end])
                ->orWhereRaw('? BETWEEN datetime_start AND datetime_end',[$datetime_end]);
            });
        })
		->count();

        if($total_record_same_table_same_time > 0){
            $data = [];
            $message = 'There\'s no available slot. Change table or time!';
        }else{
            $record = new Reservation();
    
            $record->name = $req->name;
            $record->table_no = $req->table_no;
            $record->menu_category = $req->menu_category;
            $record->datetime_start = $req->datetime_start;
            $record->datetime_end = $datetime_end;
    
            $record->save();

            $data = $record->toArray();
            $message = 'New Reservation Created!';
        }

        return response()->json([
            'data' => $data,
            'message' => $message
        ]);
    }

    public function destroy($id)
    {
        $exists = Reservation::where('id', $id)->exists();

        if ($exists) {
            Reservation::destroy($id);
            $message = 'Reservation with id='.$id.' has been deleted.';
        } else {
            $message = 'No record for id='.$id;
        }

        
        return response()->json([
            'message' => $message
        ]);
    }
}
