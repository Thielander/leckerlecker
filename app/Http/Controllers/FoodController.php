<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts;
use Milon\Barcode\DNS1D;

class FoodController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function search(Request $request)
    {
        $barcode = $request->input('barcode');
        $product = OpenFoodFacts::barcode($barcode);

        if (!$product) {
            return view('welcome', ['productData' => null]);
        }

        $productData = [
            'id' => $product['_id'] ?? 'N/A',
            'product_name' => $product['product_name_de'] ?? $product['product_name'] ?? 'N/A',
            'brands' => $product['brands'] ?? 'N/A',
            'categories' => $product['categories'] ?? 'N/A',
            'countries' => $product['countries'] ?? 'N/A',
            'image_front_url' => $product['image_front_url'] ?? null,
            'nutriments' => $product['nutriments'] ?? [],
            'nutriscore_grade' => $product['nutriscore_grade'] ?? [],
            'barcode' => $barcode
        ];

        $dailyValues = [
            'energy_kcal' => 2000,
            'sugars' => 50,
            'salt' => 6
        ];

        $percentages = [
            'energy_kcal' => isset($productData['nutriments']['energy-kcal_100g']) ? ($productData['nutriments']['energy-kcal_100g'] / $dailyValues['energy_kcal']) * 100 : 0,
            'sugars' => isset($productData['nutriments']['sugars_100g']) ? ($productData['nutriments']['sugars_100g'] / $dailyValues['sugars']) * 100 : 0,
            'salt' => isset($productData['nutriments']['salt_100g']) ? ($productData['nutriments']['salt_100g'] / $dailyValues['salt']) * 100 : 0,
        ];

        return view('search', compact('productData', 'percentages'));
    }

    public function getHealthAssessment(Request $request)
    {
        $productData = $request->input('productData');

        $prompt = "Bewerten Sie die Gesundheit dieses Lebensmittels basierend auf den folgenden Nährwerten pro 100g: Kalorien: {$productData['nutriments']['energy-kcal_100g']} kcal, Zucker: {$productData['nutriments']['sugars_100g']} g, Salz: {$productData['nutriments']['salt_100g']} g. Sollte man dieses Lebensmittel oft essen oder lieber darauf verzichten?";
        $apiKey = config('app.openai_key');
        $healthAssessment = $this->queryOpenAI($prompt, $apiKey);

        return response()->json(['healthAssessment' => $healthAssessment]);
    }

    private function queryOpenAI($prompt, $apiKey)
    {
        $apiUrl = "https://api.openai.com/v1/completions";

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ];

        $data = [
            "model" => "gpt-3.5-turbo-instruct",
            "prompt" => $prompt,
            "temperature" => 0,
            "max_tokens" => 1000,
            "top_p" => 0.9,
            "frequency_penalty" => 0,
            "presence_penalty" => 0.6
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return 'Fehler bei der Anfrage an die OpenAI API';
        }

        $responseData = json_decode($response, true);
        curl_close($ch);

        return $responseData['choices'][0]['text'] ?? 'Keine Antwort vom Modell';
    }
}
