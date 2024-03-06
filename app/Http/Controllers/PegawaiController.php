<?php

namespace App\Http\Controllers;

use App\Models\PersonnelArea;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use App\Models\PersonnelPosition;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();
        $position = PersonnelPosition::orderBy('position_name', 'asc')->get();
        $area = PersonnelArea::where('id', '!=', 1)->orderBy('area_name', 'asc')->get();

        return view('pegawai', compact('unit', 'position', 'area'));
    }

    public function getData(Request $request)
    {
        $strSQL = PersonnelEmployee::select('id', 'emp_code', 'first_name', 'last_name', 'department_id', 'position_id', 'company_id', 'hire_date')
            ->with('department', 'position', 'area_pegawai.area');

        if ($request->formPerangkat !== null) {
            $strSQL = $strSQL->whereHas('area_pegawai', function ($query) use ($request) {
                $query->where('area_id', $request->formPerangkat);
            });
        }

        if ($request->formPegawai != Null) {
            $strSQL = $strSQL->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($request->formPegawai) . '%'])->orWhere('last_name', 'LIKE', '%' . $request->formPegawai . '%');
        }

        if ($request->formUnit != Null) {
            $strSQL = $strSQL->where('department_id', $request->formUnit);
        }

        if ($request->formPosisi != Null) {
            $strSQL = $strSQL->where('position_id', $request->formPosisi);
        }

        $strSQL = $strSQL
            ->orderBy('department_id', 'asc')
            ->orderBy('emp_code', 'asc')
            ->get();

        return response()->json($strSQL);
    }
}
