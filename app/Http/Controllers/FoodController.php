<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenFoodFacts;

class FoodController extends Controller
{
    public function search(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = OpenFoodFacts::barcode($barcode);

        return view('welcome', compact('product'));
    }
}
