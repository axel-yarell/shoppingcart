<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    public function saveSlider(Request $request)
    {
        // $request->validate([
        //     'description1' => 'required',
        //     'description2' => 'required',
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        $slider = new \App\Models\Slider();
        $slider->description1 = $request->input('description1');
        $slider->description2 = $request->input('description2');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // On déplace le fichier physique dans le dossier public/slider_images
            $file->move(public_path('slider_images'), $filename);
            
            // On enregistre UNIQUEMENT le nom du fichier (ex: 1793809189_banner-02.jpg)
            $slider->image = $filename; 
        }

        $slider->save();

        return redirect()->back()->with('status', 'Slider added successfully!');
    }

    public function deleteSlider($id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            // Supprimer le fichier image du dossier public/slider_images
            $imagePath = public_path('slider_images/' . $slider->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $slider->delete();
            return redirect()->back()->with('status', 'Slider deleted successfully!');
        } else {
            return redirect()->back()->with('status', 'Slider not found!');
        }
    }
    
    public function editSlider($id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            return view('admin.editslider')->with('slider', $slider);
        } else {
            return redirect()->back()->with('status', 'Slider not found!');
        }
    }

    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::find($id);
        if ($slider) {
            $slider->description1 = $request->input('description1');
            $slider->description2 = $request->input('description2');

            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                $imagePath = public_path('slider_images/' . $slider->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Enregistrer la nouvelle image
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('slider_images'), $filename);
                $slider->image = $filename;
            }

            $slider->update();
            return redirect()->back()->with('status', 'Slider updated successfully!');
        } else {
            return redirect()->back()->with('status', 'Slider not found!');
        }
    }
}