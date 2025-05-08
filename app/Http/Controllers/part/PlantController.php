<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function plant_index(Request $request)
    {
        $plants = Plant::select('id', 'name' )->orderBy("created_at", "desc")->paginate(10);
        $data = [
            'main' => $plants,
            'route' => 'plant',
        ];
        return view('part.index' , $data);
    }

    public function plant_create()
    {
        $data = [
            'route' => 'plant',
        ];
        return view('part.create' , $data);
    }

    public function plant_store(Request $request)
    { 
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $plant = Plant::create([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.plant')->with('success', __('general.added_successfully'));
    }
    public function plant_edit($id)
    {
        $plant = Plant::findOrFail($id); 
        $data = [
            'main' => $plant,
            'route' => 'plant',
        ];
        return view('part.edit', $data);
    }
    public function plant_update(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);
       
        $request->validate([
            'name' => 'required|string|max:255|unique:plants,name,'.$id.',id',
        ]);
        $plant->update([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.plant')->with('success', __('general.updated_successfully'));
    }
    public function plant_destroy($id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return redirect()->route('admin.plant')->with('success', __('general.deleted_successfully'));
    }
}
