<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="/assets/icon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-200 pt-28 inconsolata-regular">
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

<script src="../script/navbar.js"></script>
</body>
</html>
