@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('content')
<div class="container mx-auto p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 rounded-2xl shadow-xl">
    <h1 class="text-4xl font-bold text-center text-slate-800 mb-8">Editar Producto</h1>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nombre -->
        <div>
            <label for="name" class="block text-lg font-medium text-slate-700">Nombre</label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Descripción -->
        <div>
            <label for="description" class="block text-lg font-medium text-slate-700">Descripción</label>
            <textarea name="description" id="description" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="4" required>{{ $product->description }}</textarea>
        </div>

        <!-- Precio y Categoría -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="price" class="block text-lg font-medium text-slate-700">Precio</label>
                <input type="number" name="price" id="price" value="{{ $product->price }}" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="category_id" class="block text-lg font-medium text-slate-700">Categoría</label>
                <select name="category_id" id="category_id" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    @foreach(\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Imagen -->
        <div>
            <label for="image" class="block text-lg font-medium text-slate-700">Imagen</label>
            <input type="file" name="image" id="image" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Dimensiones -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="height" class="block text-lg font-medium text-slate-700">Alto (cm)</label>
                <input type="number" name="height" id="height" value="{{ $product->height }}" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="width" class="block text-lg font-medium text-slate-700">Ancho (cm)</label>
                <input type="number" name="width" id="width" value="{{ $product->width }}" step="0.01" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <!-- Visible en Galería -->
        <div class="flex items-center">
            <input type="checkbox" name="is_gallery_visible" id="is_gallery_visible" value="1" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" {{ $product->is_gallery_visible ? 'checked' : '' }}>
            <label for="is_gallery_visible" class="ml-2 text-lg font-medium text-slate-700">¿Visible en Galería?</label>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white font-bold py-3 px-6 rounded-lg shadow-md">Actualizar Producto</button>
    </form>
</div>
@endsection