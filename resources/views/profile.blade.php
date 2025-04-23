@extends('layouts.main')

@section('content')

<div id="header-container"></div>
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

            <form action="{{ route('logout') }}" method="POST" class="flex-1 basis-1/4 max-w-1/4 mt-4">
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
                    <div class="content"></div>
                    <button class="edit text-gray-600 text-md md:text-lg absolute top-5 right-6 rounded-lg classic-clicked:hover underline">Edit</button>
                </div>
            </div>

            <div class="flex flex-col gap-8 w-full">
                <form action="" method="post" class="w-full flex flex-wrap sm:flex-nowrap gap-5 mt-4">
                    <input type="password" name="password" placeholder="Old password"
                           class=" inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
                    <input type="password" name="password" placeholder="New password"
                           class=" inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
                    <input type="password" name="password" placeholder="Repeat password"
                           class=" inconsolata-regular w-full h-10 sm:h-12 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 px-3 py-2 text-gray-700 border rounded-lg bg-gray-200 focus:outline-gray-100 border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 border-gray-300">
                </form>

                <div class="flex w-full justify-center sm:gap-8">
                    <div class="w-0 sm:flex-1 sm:basis-2/3 sm:max-w-2/3">
                    </div>
                    <button type="submit"
                            class="inconsolata-bold text-gray-700 text-lg w-40 sm:flex-1 sm:basis-1/3 sm:max-w-1/3 h-12 rounded-lg px-4 py-2 classic-clicked">Change
                    </button>
                </div>

            </div>
        </div>

        <!-- Orders Section (Initially Hidden) -->
        <div id="orders" class="hidden">
            <div class="flex flex-col gap-4 items-center w-full">
                <div class="flex flex-wrap gap-4 sm:gap-0 w-full h-full items-start relative rounded-lg border border-gray-300 p-5">
                    <div class="flex flex-col flex-1 basis-1/2 max-w-1/2 justify-center text-nowrap">
                        <p class="text-start text-lg md:text-xl font-bold">Order id 20241025</p>
                        <p class="text-start text-md md:text-lg">Parkova 24</p>
                        <p class="text-start text-md md:text-lg">95102 Nitra</p>
                        <p class="text-start text-md md:text-lg">Slovakia</p>
                    </div>

                    <div class="flex flex-col flex-auto h-full flex-grow text-nowrap text-end">
                        <p class="text-lg md:text-xl font-bold custom-text">Total order cost:</p>
                        <p class="pe-1  text-md md:text-lg font-bold custom-text">€ 192,63</p>
                        <p class="flex-grow custom-margin text-md md:text-lg custom-text">Status: <span class="text-orange-500">Shipped</span></p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 sm:gap-0 w-full h-full items-start relative rounded-lg border border-gray-300 p-5">
                    <div class="flex flex-col flex-1 basis-1/2 max-w-1/2 justify-center text-nowrap">
                        <p class="text-start text-lg md:text-xl font-bold">Order id 20241025</p>
                        <p class="text-start text-md md:text-lg">Parkova 24</p>
                        <p class="text-start text-md md:text-lg">95102 Nitra</p>
                        <p class="text-start text-md md:text-lg">Slovakia</p>
                    </div>

                    <div class="flex flex-col flex-auto h-full flex-grow text-nowrap text-end">
                        <p class="text-lg md:text-xl font-bold custom-text">Total order cost:</p>
                        <p class="pe-1  text-md md:text-lg font-bold custom-text">€ 192,63</p>
                        <p class="flex-grow custom-margin text-md md:text-lg custom-text">Status: <span class="text-green-500">Delivered</span></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

<script src="https://unpkg.com/@material-tailwind/html@2.3.2/scripts/collapse.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="../script/navbar.js"></script>
<script src="../script/profile.js"></script>
<script src="../script/toggle.js"></script>

