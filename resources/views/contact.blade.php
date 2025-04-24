@extends('layouts.main')

@section('title', 'Contact - Flakes')

@section('content')
<div id="header-container"></div>
<div class="max-w-screen-lg mx-auto px-4">
  <!-- Contact Form Section -->
  <div class="bg-gray-200 p-6 border border-gray-300 rounded-lg">
    <h1 class="text-xl sm:text-2xl lg:text-3xl inconsolata-bold text-center">Contact Us</h1>
    <form class="mt-6 space-y-4">
      <div>
        <label class="block text-lg inconsolata-bold">Name</label>
        <input type="text" class="w-full p-3 rounded-lg bg-gray-200 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300" placeholder="Your Name">
      </div>
      <div>
        <label class="block text-lg inconsolata-bold">Email</label>
        <input type="email" class="w-full p-3 rounded-lg bg-gray-200 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300" placeholder="Your Email">
      </div>
      <div>
        <label class="block text-lg inconsolata-bold">Message</label>
        <textarea class="w-full p-3 rounded-lg bg-gray-200 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300" rows="5" placeholder="Your Message"></textarea>
      </div>
      <div class="w-full flex justify-center sm:justify-end mt-4">
        <button type="submit" class="classic-clicked-border text-lg lg:text-xl inconsolata-bold text-black rounded-lg p-2 lg:pd-0 h-12 w-40 classic-clicked">
          Send Message
        </button>
      </div>
    </form>
  </div>

  <!-- Contact Details Section -->
  <div class="mt-10 p-6 bg-gray-200 border border-gray-300 rounded-lg">
    <h2 class="text-xl sm:text-2xl font-bold text-center">Our Contact Information</h2>
    <div class="flex flex-col sm:flex-row justify-between mt-6">
      <div class="w-full sm:w-1/2">
        <h3 class="text-lg sm:text-xl font-bold">Address</h3>
        <p class="text-md sm:text-lg mt-2">123 Flakes Street, Cityville, Country, 12345</p>
      </div>
      <div class="w-full sm:w-1/2 inconsolata-regular mt-4 sm:mt-0">
        <h3 class="text-lg sm:text-xl inconsolata-bold">Phone</h3>
        <p class="text-md sm:text-lg mt-2">(123) 456-7890</p>
      </div>
    </div>
  </div>

  <!-- Social Media Section -->
  <div class="mt-10 p-6 bg-gray-200 rounded-lg pb-10">
    <div class="flex justify-center mt-6 space-x-6">
      <a href="https://facebook.com" class="text-2xl sm:text-3xl text-blue-600"><i class="fab fa-facebook" style="color: #666666;"></i></a>
      <a href="https://twitter.com" class="text-2xl sm:text-3xl text-blue-400"><i class="fab fa-twitter" style="color: #666666;"></i></a>
      <a href="https://linkedin.com" class="text-2xl sm:text-3xl text-blue-800"><i class="fab fa-linkedin" style="color: #666666;"></i></a>
      <a href="https://instagram.com" class="text-2xl sm:text-3xl text-pink-600"><i class="fab fa-instagram" style="color: #666666;"></i></a>
    </div>
  </div>
</div>
@endsection
