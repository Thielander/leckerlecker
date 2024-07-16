<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Facts</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Food Facts</h1>
        <form action="{{ route('search') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="barcode">Enter Barcode:</label>
                <input type="text" id="barcode" name="barcode" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Search</button>
        </form>
        @isset($product)
            <div class="mt-5">
                <h2>Product Information</h2>
                <p><strong>Name:</strong> {{ $product['product_name'] ?? 'N/A' }}</p>
                <p><strong>Image:</strong> <img src="{{ $product['image_url'] }}" alt="Product Image"></p>
                <p><strong>Ingredients:</strong> {{ $product['ingredients_text'] ?? 'N/A' }}</p>
                <p><strong>Nutriments:</strong> {{ $product['nutriments'] ?? 'N/A' }}</p>
            </div>
        @endisset
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
