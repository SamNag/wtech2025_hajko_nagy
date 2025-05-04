@extends('admin')

@section('admin-content')
    <div class="py-2">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold mb-1 inconsolata-bold">Products</h1>
                <h2 class="text-md lg:text-lg text-gray-500 inconsolata-regular">Here is your product list data</h2>
            </div>
            <a href="{{ route('admin.products.create') }}"
               class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Add Product
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.products') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by product name or description..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Categories</option>
                        @foreach(DB::table('products')->select('category')->distinct()->orderBy('category')->pluck('category') as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-800  text-white rounded-lg">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border border-gray-300 shadow rounded-lg">
                <thead>
                <tr class="border-b border-gray-300 text-black text-left bg-gray-50">
                    <th class="p-4">Product Name</th>
                    <th class="p-4">Price Range</th>
                    <th class="p-4">Total Stock</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Tags</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-gray-300 hover:bg-gray-50">
                        <td class="p-4">
                            <div class="flex items-center">
                                <img src="{{ asset('storage/' . $product->image1) }}"
                                     alt="{{ $product->name }}"
                                     class="w-10 h-10 object-cover rounded mr-3">
                                <span>{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            €{{ number_format($product->packages->min('price'), 2) }} -
                            €{{ number_format($product->packages->max('price'), 2) }}
                        </td>
                        <td class="p-4">{{ $product->packages->sum('stock') }}</td>
                        <td class="p-4">
                                <span class="px-3 py-1 text-sm font-semibold rounded-md
                                    @if($product->category == 'minerals') bg-blue-100 text-blue-800
                                    @elseif($product->category == 'vitamins') bg-green-100 text-green-800
                                    @elseif($product->category == 'oils') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($product->category) }}
                                </span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->tags as $tag)
                                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">
                                            {{ $tag->tag_name }}
                                        </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="openProductDropdown(event, '{{ $product->id }}')"
                                        class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">No products found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="flex flex-col gap-4 md:hidden">
            @forelse($products as $product)
                <div class="border border-gray-300 rounded-lg p-4 shadow">
                    <div class="flex items-center mb-2">
                        <img src="{{ asset('storage/' . $product->image1) }}"
                             alt="{{ $product->name }}"
                             class="w-16 h-16 object-cover rounded mr-3">
                        <div>
                            <div class="font-semibold">{{ $product->name }}</div>
                            <div class="text-sm text-gray-600">
                                {{ ucfirst($product->category) }}
                            </div>
                        </div>
                    </div>
                    <div><span class="font-semibold">Price Range:</span>
                        €{{ number_format($product->packages->min('price'), 2) }} -
                        €{{ number_format($product->packages->max('price'), 2) }}
                    </div>
                    <div><span class="font-semibold">Total Stock:</span> {{ $product->packages->sum('stock') }}</div>
                    <div class="mt-2">
                        <span class="font-semibold">Packages:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($product->packages as $package)
                                <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                                    {{ $package->size }} ({{ $package->stock }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="font-semibold">Tags:</span>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($product->tags as $tag)
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $tag->tag_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-3 flex gap-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="text-blue-600 hover:text-blue-900 underline text-sm">
                            Edit Product
                        </a>
                        <button onclick="openProductDropdown(event, '{{ $product->id }}')"
                                class="text-gray-600 hover:text-gray-900 underline text-sm">
                            More Actions
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">No products found</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Product Action Dropdown -->
    <div id="product-dropdown" class="hidden absolute bg-white shadow-lg rounded-lg p-3 z-50 border border-gray-200">
        <a id="edit-product-btn" href="#" class="block w-full text-left px-4 py-2 text-indigo-600 hover:bg-gray-100">
            <i class="fas fa-edit mr-2"></i>Edit Product
        </a>
        <button id="delete-product-btn" onclick="deleteProduct()" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
            <i class="fas fa-trash mr-2"></i>Delete Product
        </button>
    </div>

    <!-- Delete Form (hidden) -->
    <form id="delete-product-form" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            let selectedProductId = null;

            function openProductDropdown(event, productId) {
                event.stopPropagation();
                selectedProductId = productId;

                const dropdown = document.getElementById('product-dropdown');
                const editBtn = document.getElementById('edit-product-btn');

                // Update edit button href
                editBtn.href = `/admin/products/${productId}/edit`;

                // Position dropdown
                dropdown.classList.remove('hidden');
                dropdown.style.top = `${event.clientY + window.scrollY}px`;
                dropdown.style.left = `${event.clientX}px`;
            }

            function deleteProduct() {
                if (selectedProductId && confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                    const form = document.getElementById('delete-product-form');
                    form.action = `/admin/products/${selectedProductId}`;
                    form.submit();
                }
                document.getElementById('product-dropdown').classList.add('hidden');
            }

            // Close dropdown on click outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('product-dropdown');
                if (!dropdown.contains(event.target) && !event.target.closest('button[onclick*="openProductDropdown"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    @endpush
@endsection

