<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

/**
 * Class CampaignController
 * @package App\Http\Controllers
 */
class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::all();

        if(request()->ajax()){
            return DataTables()
                ->of($campaigns)
                ->addColumn('action', 'crm.campaign.actions')
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }

        return view('crm.campaign.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $campaign = new Campaign();
        
        $statuses = [
            '' => '-- Selecciona una opción --',
            '1' => 'Activo',
            '0' => 'Inactivo',
        ];

        return view('crm.campaign.create', compact('campaign', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Campaign::$rules);
        
        $campaign = new Campaign;
        $campaign->nombre_campana = $request->nombre_campana;
        $campaign->descripcion_campana = $request->descripcion_campana;
        $campaign->status = $request->status;
        $campaign->empresa = $request->empresa;
        $campaign->tipo_proyecto = $request->tipo_proyecto;

        $campaign->save();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaña creada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);

        return view('crm.campaign.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::find($id);

        $statuses = [
            '' => '-- Selecciona una opción --',
            '1' => 'Activo',
            '0' => 'Inactivo',
        ];

        return view('crm.campaign.edit', compact('campaign', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        request()->validate(Campaign::$rules);

        $campaign = Campaign::find($campaign->id);
        $campaign->nombre_campana = $request->nombre_campana;
        $campaign->descripcion_campana = $request->descripcion_campana;
        $campaign->status = $request->status;
        $campaign->empresa = $request->empresa;
        $campaign->tipo_proyecto = $request->tipo_proyecto;

        $campaign->save();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaña actualizada correctamente');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $campaign = Campaign::findOrfail($id);

        $campaign->delete();

        return response()->json([
            'code' => 200,
            'message' => 'Campaña eliminada correctamente'
        ]);
    }
}
