<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CronJobConfig;
use Illuminate\Support\Facades\Validator;

class CronJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cronjobs = CronJobConfig::all();

        if(request()->ajax()){
            return DataTables()
                ->of($cronjobs)
                ->addColumn('action', 'crm.cronjobs.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.cronjobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crm.cronjobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name_cronJob' => 'required',
            'skilldata' => 'required',
            'idload' => 'required',
            'frequency' => 'required',
        ],
        [
            'name_cronJob.required' => 'El nombre del cron job es requerido',
            'skilldata.required' => 'El skill data es requerido',
            'idload.required' => 'El idload data es requerido',
            'frequency.required' => 'La frecuencia es requerida',
        ]);

        if($validate->fails()){
            return back()->with('errors', $validate->errors())->withInput();
        } else {
            $cronJob = new CronJobConfig;
            $cronJob->name_cronJob = $request->name_cronJob;
            $cronJob->skilldata = $request->skilldata;
            $cronJob->idload = $request->idload;
            $cronJob->frequency = $request->frequency;
            $cronJob->save();
            return redirect()->route('cronjobs.index')->with('success', 'Cron job creado correctamente');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cronJob = CronJobConfig::find($id);

        return view('crm.cronjobs.edit', compact('cronJob'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Buscamos el cron job a actualizar
        $cronJob = CronJobConfig::find($request->id);

        // Validamos los campos
        $validate = Validator::make($request->all(), [
            'name_cronJob' => 'required',
            'skilldata' => 'required',
            'frequency' => 'required',
        ],
        [
            'name_cronJob.required' => 'El nombre del cron job es requerido',
            'skilldata.required' => 'El skill data es requerido',
            'frequency.required' => 'La frecuencia es requerida',
        ]);

        // Si la validación falla, regresamos a la vista con los errores
        if($validate->fails()){
            return back()->with('errors', $validate->errors())->withInput();
        } else {
            // Si la validación no falla, actualizamos el cron job
            $cronJob->name_cronJob = $request->name_cronJob;
            $cronJob->skilldata = $request->skilldata;
            $cronJob->frequency = $request->frequency;
            $cronJob->save();
            return redirect()->route('cronjobs.index')->with('success', 'Cron job actualizado correctamente');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Buscamos el cron job a eliminar
        $cronJob = CronJobConfig::find($id);

        // Eliminamos el cron job
        $cronJob->delete();

        // Mandamos mensaje de confirmación en json
        return response()->json([
            'code' => 200,
            'message' => 'Cron job eliminado correctamente'
        ]);
    }
}
