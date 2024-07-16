<!DOCTYPE html>
<html>
<head>
    <title>Open Food Facts</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Open Food Facts</h1>
        <form action="{{ route('search') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="barcode">Barcode:</label>
                <input type="text" name="barcode" id="barcode" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Search</button>
        </form>
        @isset($product)
            <div class="mt-5">
                <h2>Product Information</h2>
                <p><strong>Name:</strong> {{ $product['product_name'] ?? 'N/A' }}</p>
                <p><strong>Image:</strong> <img src="{{ $product['image_url'] }}" alt="Product Image"></p>
                <p><strong>Ingredients:</strong> {{ $product['ingredients_text'] ?? 'N/A' }}</p>
                <p><strong>Nutriments:</strong>
                    @if(isset($product['nutriments']) && is_array($product['nutriments']))
                        @foreach($product['nutriments'] as $key => $value)
                            <br>{{ $key }}: {{ $value }}
                        @endforeach
                    @else
                        {{ $product['nutriments'] ?? 'N/A' }}
                    @endif
                </p>
            </div>
        @endisset
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
