@extends('layouts.main')

@section('title', 'Profile - Flakes')

@section('content')
    <div class="max-w-screen-lg mx-auto mb-10">
        <div class="flex flex-col bg-gray-200 p-6 gap-5">
            <div class="flex text-md lg:text-lg gap-3 sm:gap-8 w-full items-center">
                <div class="btn-group classic rounded-lg h-12 flex flex-1 basis-3/4 max-w-3/4 mt-4 mb-4 justify-center" data-toggle="buttons">
                    <input id="option-profile" type="radio" name="test-toggle" checked="checked">
                    <label for="option-profile"
                           class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-l-lg text-gray-600">
                        <i class="fa-solid fa-user icon-only text-lg mr-2"></i>
                        <span class="text-only hidden sm:block">Profile</span>
                    </label>
                    <input id="option-orders" type="radio" name="test-toggle">
                    <label for="option-orders"
                           class="btn btn-default-toggle-ghost active w-full flex items-center justify-center rounded-r-lg text-gray-600">
                        <i class="fa-solid fa-box icon-only text-lg mr-2"></i>
                        <span class="text-only hidden sm:block">Orders</span>
                    </label>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="flex-1 basis-1/4 max-w-1/4">
                    @csrf
                    <button type="submit" class="flex text-center items-center classic-clicked w-full h-12 items-center justify-center rounded-lg">
                <span class="icon-button">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                </span>
                        <span class="hidden sm:block">Logout</span>
                    </button>
                </form>
            </div>

            <!-- Profile Section -->
            <div id="profile">
                <div class="flex gap-8 w-full flex-wrap items-center justify-center">
                    <div class="w-32 h-32 flex items-center justify-center rounded-full classic">
                        <i class="fa-regular fa-user text-5xl"></i>
                    </div>

                    <div id="profile-info" class="flex flex-col flex-1 basis-2/3 max-w-2/3 items-start relative rounded-lg border border-gray-300 p-5">
                        <p class="text-start text-lg md:text-xl font-bold">Profile information</p>
                        <div class="content">
                            <p class="text-start text-md md:text-lg">{{ $user->name }} {{ $user->surname }}</p>
                            <p class="text-start text-md md:text-lg">{{ $user->email }}</p>
                            <p class="text-start text-md md:text-lg">{{ $user->phone ?? 'No phone number provided' }}</p>
                        </div>
                        <button type="button" class="edit text-gray-600 text-md md:text-lg absolute top-5 right-6 rounded-lg classic-clicked:hover underline">Edit</button>

                        <!-- Hidden Edit Form -->
                        <div class="edit-form hidden w-full transition-all duration-100">
                            <form action="{{ route('profile.update') }}" method="POST" class="flex flex-col w-full gap-2 pt-2">
                                @csrf
                                @method('PATCH')
                                <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Name</label>
                                        <input type="text" name="name" class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300" value="{{ $user->name }}" placeholder="Enter name">
                                        @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="w-full md:w-1/2">
                                        <label class="block text-md inconsolata-bold">Surname</label>
                                        <input type="text" name="surname" class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300" value="{{ $user->surname }}" placeholder="Enter surname">
                                        @error('surname')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-md inconsolata-bold">Email</label>
                                    <input type="email" name="email" class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300" value="{{ $user->email }}" placeholder="Enter your email address">
                                    @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-md inconsolata-bold">Phone</label>
                                    <input type="text" name="phone" class="edit-input w-full rounded-lg bg-gray-200 border border-gray-300 p-3 h-12 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300" value="{{ $user->phone }}" placeholder="Enter phone number">
                                    @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="save-profile inconsolata-bold text-gray-700 text-lg px-4 py-1 rounded-lg classic-clicked">Save</button>
                                    <button type="button" class="cancel-edit ml-2 text-gray-600 text-md rounded-lg classic-clicked:hover underline px-4 py-1">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-8 w-full mt-6">
                    <form action="{{ route('password.update') }}" method="POST" id="password-form" class="w-full flex flex-wrap sm:flex-nowrap gap-5 mt-4">
                        @csrf
                        @method('PUT')
                        <input type="password" name="current_password" placeholder="Current password"
                               class="inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 @error('current_password', 'updatePassword') border-red-500 @enderror">

                        <input type="password" name="password" placeholder="New password"
                               class="inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300 @error('password', 'updatePassword') border-red-500 @enderror">

                        <input type="password" name="password_confirmation" placeholder="Confirm password"
                               class="inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">

                        <div class="sm:hidden w-full mt-4 flex justify-center">
                            <button type="submit" class="inconsolata-bold text-gray-700 text-lg w-40 h-12 rounded-lg px-4 py-2 classic-clicked">
                                Change Password
                            </button>
                        </div>
                    </form>

                    <div class="hidden sm:flex w-full justify-center sm:gap-8">
                        <div class="w-0 sm:flex-1 sm:basis-2/3 sm:max-w-2/3">
                        </div>
                        <button type="submit" form="password-form"
                                class="inconsolata-bold text-gray-700 text-lg w-40 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 h-12 rounded-lg px-4 py-2 classic-clicked">
                            Change Password
                        </button>
                    </div>

                    <!-- Error messages for password update -->
                    @if ($errors->updatePassword->any())
                        <div class="w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->updatePassword->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success message for password update -->
                    @if (session('status') === 'password-updated')
                        <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                            Password updated successfully!
                        </div>
                    @endif

                    <!-- Success message for profile update -->
                    @if (session('status') === 'profile-updated')
                        <div class="w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                            Profile updated successfully!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Orders Section (Initially Hidden) -->
            <div id="orders" class="hidden">
                <div class="flex flex-col gap-4 items-center w-full">
                    @if(isset($user->orders) && $user->orders->count() > 0)
                        @foreach($user->orders as $order)
                            <div class="flex flex-wrap gap-4 sm:gap-0 w-full h-full items-start relative rounded-lg border border-gray-300 p-5">
                                <div class="flex flex-col flex-1 basis-1/2 max-w-1/2 justify-center text-nowrap">
                                    <p class="text-start text-lg md:text-xl font-bold">Order ID: {{ $order->id }}</p>
                                    <p class="text-start text-md md:text-lg">{{ isset($order->address) ? $order->address->street : 'No address' }}</p>
                                    <p class="text-start text-md md:text-lg">{{ isset($order->address) ? $order->address->zip . ' ' . $order->address->city : '' }}</p>
                                    <p class="text-start text-md md:text-lg">{{ isset($order->address) ? $order->address->country : '' }}</p>
                                </div>

                                <div class="flex flex-col flex-auto h-full flex-grow text-nowrap text-end">
                                    <p class="text-lg md:text-xl font-bold custom-text">Total order cost:</p>
                                    <p class="pe-1 text-md md:text-lg font-bold custom-text">€ {{ isset($order->total_amount) ? number_format($order->total_amount, 2, ',', '.') : '0,00' }}</p>
                                    <p class="flex-grow custom-margin text-md md:text-lg custom-text">Status:
                                        <span class="
                                        @if($order->status == 'created') text-blue-500
                                        @elseif($order->status == 'shipped') text-orange-500
                                        @elseif($order->status == 'delivered') text-green-500
                                        @elseif($order->status == 'canceled') text-red-500
                                        @endif
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full py-10 text-center text-gray-600 inconsolata-regular text-lg md:text-xl">
                            You don't have any orders yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 427px) {
            .custom-text { text-align: start; }
            .custom-margin {margin-top: 0.5rem; }
        }
        @media (min-width: 428px) {
            .custom-text { text-align: end; }
            .custom-margin {margin-top: 1.5rem; }
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle between profile and orders tabs
            const profileSection = document.getElementById("profile");
            const ordersSection = document.getElementById("orders");
            const profileRadio = document.getElementById("option-profile");
            const ordersRadio = document.getElementById("option-orders");

            function toggleSections() {
                if (profileRadio.checked) {
                    profileSection.classList.remove("hidden");
                    ordersSection.classList.add("hidden");
                } else if (ordersRadio.checked) {
                    ordersSection.classList.remove("hidden");
                    profileSection.classList.add("hidden");
                }
            }

            // Initial setup
            toggleSections();

            // Event Listeners for switching tabs
            profileRadio.addEventListener("change", toggleSections);
            ordersRadio.addEventListener("change", toggleSections);

            // Edit profile functionality
            const editBtn = document.querySelector(".edit");
            const contentDiv = document.querySelector(".content");
            const editForm = document.querySelector(".edit-form");
            const cancelBtn = document.querySelector(".cancel-edit");

            editBtn.addEventListener("click", function() {
                contentDiv.classList.add("hidden");
                editForm.classList.remove("hidden");
                editBtn.classList.add("hidden");
            });

            cancelBtn.addEventListener("click", function() {
                contentDiv.classList.remove("hidden");
                editForm.classList.add("hidden");
                editBtn.classList.remove("hidden");
            });
        });
    </script>
@endpush
