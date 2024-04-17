<?php

namespace App\Http\Controllers;

use App\Models\PersonnelArea;
use App\Models\PersonnelDepartment;
use App\Models\PersonnelEmployee;
use App\Models\PersonnelPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PegawaiController extends Controller
{
    public function index()
    {
        if (Auth::user()->unit_id == 999) {
            $unit = PersonnelDepartment::where('id', '!=', 1)->orderBy('dept_name', 'asc')->get();
            $position = PersonnelPosition::orderBy('position_name', 'asc')->get();
            $area = PersonnelArea::where('id', '!=', 1)->orderBy('area_name', 'asc')->get();
        } else {
            $unitId = Auth::user()->unit_id;

            $unit = PersonnelDepartment::where('id', '!=', 1)
                ->where('dept_code', $unitId)
                ->orderBy('dept_name', 'asc')
                ->get();

            $position = PersonnelPosition::orderBy('position_name', 'asc')->get();

            $area = PersonnelArea::where('id', '!=', 1)
                ->orderBy('area_name', 'asc')
                ->get();
        }

        return view('pegawai', compact('unit', 'position', 'area'));
    }

    public function getData(Request $request)
    {
        $unit_login =  Auth::user()->unit_id;

        if ($unit_login == 999) {
            $strSQL = PersonnelEmployee::select('id', 'emp_code', 'nickname', 'first_name', 'last_name', 'department_id', 'position_id', 'company_id', 'hire_date')
                ->with('department', 'position', 'area_pegawai.area');
        } else {
            $strSQL = PersonnelEmployee::select('id', 'emp_code', 'nickname', 'first_name', 'last_name', 'department_id', 'position_id', 'company_id', 'hire_date')
                ->with('department', 'position', 'area_pegawai.area')
                ->whereHas('department', function ($q) use ($unit_login) {
                    $q->where('dept_code', $unit_login);
                });
        }

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

        // return response()->json($strSQL);
        return DataTables::of($strSQL)->make(true);
    }
}
