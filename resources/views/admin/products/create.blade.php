@extends('admin')

@section('admin-content')
    <div class="py-2">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
            @csrf

            <!-- Top Buttons -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl lg:text-2xl font-bold inconsolata-bold">Add Product</h1>

                <!-- Desktop Buttons -->
                <div class="hidden sm:flex items-center">
                    <a href="{{ route('admin.products') }}"
                       class="px-4 py-2 rounded-lg text-gray-600 hover:text-gray-800 underline">
                        Discard
                    </a>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg px-4 py-2 ml-2">
                        Save Product
                    </button>
                </div>

                <!-- Mobile Icon Buttons -->
                <div class="flex sm:hidden items-center gap-2">
                    <a href="{{ route('admin.products') }}"
                       class="text-gray-600 rounded-lg p-2"
                       title="Discard">
                        <i class="fas fa-trash px-1"></i>
                    </a>
                    <button type="submit"
                            class="bg-indigo-500 text-white rounded-lg p-2"
                            title="Save">
                        <i class="fa-solid fa-floppy-disk px-1"></i>
                    </button>
                </div>
            </div>

            <!-- Main Grid: Media + Product Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Media Upload Section with drag & drop -->
                <div class="p-6 rounded-lg border border-gray-300">
                    <h2 class="text-lg font-bold mb-4">Media</h2>
                    <label class="block text-gray-700 font-semibold mb-2">Product images (max 2)</label>

                    <div id="drop-zone"
                         class="w-full h-60 border-2 border-dashed border-gray-300 rounded-lg flex flex-col justify-center items-center text-gray-400 text-lg">
                        <button type="button" id="select-button"
                                class="bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded-full transition-colors duration-300">
                            Select Image
                        </button>
                        <span class="mt-2 text-sm text-gray-500">Drag and drop files here</span>
                    </div>
                    <div id="selected-files-count" class="text-gray-500 text-sm font-medium mt-2"></div>
                    <div id="selected-images" class="flex flex-wrap -mx-2 mt-4"></div>
                    <input id="file-input" type="file" class="hidden" accept="image/*" multiple />
                </div>

                <!-- Product Information Section -->
                <div class="p-6 rounded-lg border border-gray-300">
                    <h2 class="text-lg font-bold mb-4">Product Information</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Title</label>
                        <input type="text" name="name" id="product-name" required
                               class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg @error('name') border-red-500 @enderror"
                               placeholder="Enter product title" value="{{ old('name') }}">
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Description</label>
                        <textarea name="description" id="product-description" required
                                  class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg h-32 @error('description') border-red-500 @enderror"
                                  placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Category, Tags & Packaging Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Category and Tags -->
                <div class="p-6 rounded-lg border border-gray-300">
                    <h2 class="text-lg font-bold mb-4">Category and Tags</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">Select Category</label>
                        <select name="category" id="product-category" required
                                class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg @error('category') border-red-500 @enderror">
                            <option value="">Choose a category</option>
                            <option value="minerals" {{ old('category') == 'minerals' ? 'selected' : '' }}>Minerals</option>
                            <option value="vitamins" {{ old('category') == 'vitamins' ? 'selected' : '' }}>Vitamins</option>
                            <option value="oils" {{ old('category') == 'oils' ? 'selected' : '' }}>Oils</option>
                        </select>
                        @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Product Tags</label>
                        <div id="tags-container" class="flex flex-wrap gap-2 mb-2">
                            <!-- Tags will be added here dynamically -->
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="tag-input"
                                   class="flex-1 bg-gray-200 border border-gray-300 p-2 rounded-lg"
                                   placeholder="Add a tag (e.g., 'skin', 'hair', 'bones')">
                            <button type="button" onclick="addTag()"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg">
                                Add
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Packaging / Stock Management -->
                <div class="p-6 rounded-lg border border-gray-300">
                    <h2 class="text-lg font-bold mb-4">Packaging Stock</h2>
                    <div id="packages-container">
                        <!-- Packages will be added here -->
                    </div>
                    <button type="button" onclick="addPackage()"
                            class="mt-4 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-plus mr-2"></i>Add Package Size
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let tags = [];
            let packageCount = 0;
            const MAX_IMAGES = 2;

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize image upload
                initializeImageUpload();

                // Add one empty package by default
                addPackage();

                // Listen for category changes
                document.getElementById('product-category').addEventListener('change', updatePackageOptions);
            });

            // Image upload functions
            function initializeImageUpload() {
                const fileInput = document.getElementById("file-input");
                const dropZone = document.getElementById("drop-zone");
                const selectedImages = document.getElementById("selected-images");
                const selectButton = document.getElementById("select-button");
                const selectedFilesCount = document.getElementById("selected-files-count");

                selectButton.addEventListener("click", () => {
                    if (selectedImages.children.length < MAX_IMAGES) {
                        fileInput.click();
                    }
                });

                fileInput.addEventListener("change", handleFiles);
                dropZone.addEventListener("dragover", handleDragOver);
                dropZone.addEventListener("dragleave", handleDragLeave);
                dropZone.addEventListener("drop", handleDrop);
            }

            function handleFiles() {
                const fileList = this.files;
                addImages(fileList);
            }

            function handleDragOver(event) {
                event.preventDefault();
                const dropZone = document.getElementById("drop-zone");
                dropZone.classList.add("border-blue-500", "text-blue-500");
            }

            function handleDragLeave(event) {
                event.preventDefault();
                const dropZone = document.getElementById("drop-zone");
                dropZone.classList.remove("border-blue-500", "text-blue-500");
            }

            function handleDrop(event) {
                event.preventDefault();
                const fileList = event.dataTransfer.files;
                addImages(fileList);
                const dropZone = document.getElementById("drop-zone");
                dropZone.classList.remove("border-blue-500", "text-blue-500");
            }

            function addImages(fileList) {
                const selectedImages = document.getElementById("selected-images");
                if (selectedImages.children.length >= MAX_IMAGES) return;

                for (const file of fileList) {
                    if (selectedImages.children.length < MAX_IMAGES) {
                        const imageWrapper = document.createElement("div");
                        imageWrapper.classList.add("relative", "mx-2", "mb-2");

                        const image = document.createElement("img");
                        image.src = URL.createObjectURL(file);
                        image.classList.add("preview-image", "w-32", "h-32", "object-cover", "rounded-lg");

                        // Create hidden input for the file
                        const fileInputHidden = document.createElement("input");
                        fileInputHidden.type = "file";
                        fileInputHidden.name = selectedImages.children.length === 0 ? "image1" : "image2";
                        fileInputHidden.style.display = "none";
                        fileInputHidden.files = createFileList([file]);

                        const removeButton = document.createElement("button");
                        removeButton.type = "button";
                        removeButton.innerHTML = "&times;";
                        removeButton.classList.add(
                            "absolute", "top-1", "right-1", "h-6", "w-6",
                            "bg-gray-700", "text-white", "text-xs", "rounded-full",
                            "flex", "items-center", "justify-center",
                            "opacity-50", "hover:opacity-100", "transition-opacity",
                            "focus:outline-none"
                        );

                        removeButton.addEventListener("click", () => {
                            imageWrapper.remove();
                            updateUI();
                        });

                        imageWrapper.appendChild(image);
                        imageWrapper.appendChild(removeButton);
                        imageWrapper.appendChild(fileInputHidden);
                        selectedImages.appendChild(imageWrapper);
                    }
                }
                updateUI();
            }

            function createFileList(files) {
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                return dataTransfer.files;
            }

            function updateUI() {
                const selectedImages = document.getElementById("selected-images");
                const selectButton = document.getElementById("select-button");
                const dropZone = document.getElementById("drop-zone");
                const selectedFilesCount = document.getElementById("selected-files-count");

                const count = selectedImages.children.length;
                selectedFilesCount.textContent = count > 0 ? `${count} file${count === 1 ? "" : "s"} selected` : "";

                if (count >= MAX_IMAGES) {
                    selectButton.classList.add("hidden");
                    dropZone.classList.add("hidden");
                } else {
                    selectButton.classList.remove("hidden");
                    dropZone.classList.remove("hidden");
                }
            }

            // Tag functions
            function addTag() {
                const input = document.getElementById('tag-input');
                const tagName = input.value.trim();

                if (tagName && !tags.includes(tagName)) {
                    tags.push(tagName);
                    updateTagsDisplay();
                    input.value = '';
                }
            }

            function removeTag(tagName) {
                tags = tags.filter(tag => tag !== tagName);
                updateTagsDisplay();
            }

            function updateTagsDisplay() {
                const container = document.getElementById('tags-container');
                container.innerHTML = '';

                tags.forEach(tag => {
                    const tagElement = document.createElement('span');
                    tagElement.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center gap-2';
                    tagElement.innerHTML = `
                    ${tag}
                    <button type="button" onclick="removeTag('${tag}')" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="tags[]" value="${tag}">
                `;
                    container.appendChild(tagElement);
                });
            }

            // Package functions - same as in edit.blade.php
            function addPackage() {
                const container = document.getElementById('packages-container');
                const packageElement = createPackageElement();
                container.appendChild(packageElement);
                packageCount++;
            }

            function createPackageElement(package = null) {
                const category = document.getElementById('product-category').value;
                const packageElement = document.createElement('div');
                packageElement.className = 'mb-4 p-4 border border-gray-200 rounded-lg relative';

                const packageHtml = `
                <button type="button" onclick="removePackage(this)"
                        class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Package Size</label>
                        <select name="packages[${packageCount}][size]" class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg" required>
                            ${getPackageOptions(category)}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Price (€)</label>
                        <input type="number" name="packages[${packageCount}][price]" step="0.01" min="0" required
                               class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg"
                               placeholder="0.00">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm text-gray-700 mb-1">Stock</label>
                        <input type="number" name="packages[${packageCount}][stock]" min="0" required
                               class="w-full bg-gray-200 border border-gray-300 p-2 rounded-lg"
                               placeholder="0">
                    </div>
                </div>
            `;

                packageElement.innerHTML = packageHtml;
                return packageElement;
            }

            function removePackage(button) {
                const container = document.getElementById('packages-container');
                if (container.children.length > 1) {
                    button.closest('.mb-4').remove();
                } else {
                    alert('You must have at least one package.');
                }
            }

            function getPackageOptions(category, selectedSize = null) {
                const options = {
                    minerals: ['30pcs', '60pcs', '120pcs'],
                    vitamins: ['30pcs', '60pcs', '120pcs'],
                    oils: ['10ml', '20ml', '50ml']
                };

                const selectedOptions = options[category] || options.minerals;
                return selectedOptions.map(option =>
                    `<option value="${option}" ${option === selectedSize ? 'selected' : ''}>${option}</option>`
                ).join('');
            }

            function updatePackageOptions() {
                const category = document.getElementById('product-category').value;
                const selects = document.querySelectorAll('select[name^="packages["][name$="[size]"]');

                selects.forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = getPackageOptions(category);
                    // Try to restore previous value if it exists in new options
                    if (select.querySelector(`option[value="${currentValue}"]`)) {
                        select.value = currentValue;
                    }
                });
            }
        </script>
    @endpush
@endsection
