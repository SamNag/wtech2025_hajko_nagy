@extends('admin')

@section('admin-content')
    <div class="py-2">
        <h1 class="text-xl lg:text-2xl font-bold mb-1 inconsolata-bold">Users</h1>
        <h2 class="text-md lg:text-lg text-gray-500 mb-4 inconsolata-regular">Here is your user list data</h2>

        <!-- Search and Filter Section -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $searchTerm }}"
                           placeholder="Search by name, surname, or email..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2">
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="">All Users ({{ $userCounts['total'] }})</option>
                        <option value="admin" {{ $userType == 'admin' ? 'selected' : '' }}>Admins ({{ $userCounts['admin'] }})</option>
                        <option value="customer" {{ $userType == 'customer' ? 'selected' : '' }}>Customers ({{ $userCounts['customer'] }})</option>
                    </select>
                    <button type="submit" class="px-6 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
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
                    <th class="p-4">Full Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Registered</th>
                    <th class="p-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-gray-300 hover:bg-gray-50">
                        <td class="p-4">{{ $user->name }} {{ $user->surname }}</td>
                        <td class="p-4">{{ $user->email }}</td>
                        <td class="p-4">
                                <span class="px-3 py-1 text-sm font-semibold rounded-md {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->is_admin ? 'Admin' : 'Customer' }}
                                </span>
                        </td>
                        <td class="p-4">{{ $user->phone ?? 'Not provided' }}</td>
                        <td class="p-4">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <button onclick="openUserDropdown(event, '{{ $user->id }}', {{ $user->is_admin ? 'true' : 'false' }})"
                                        class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">No users found</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="flex flex-col gap-4 md:hidden">
            @forelse($users as $user)
                <div class="border border-gray-300 rounded-lg p-4 shadow">
                    <div><span class="font-semibold">Full Name:</span> {{ $user->name }} {{ $user->surname }}</div>
                    <div><span class="font-semibold">Email:</span> {{ $user->email }}</div>
                    <div><span class="font-semibold">Role:</span>
                        <span class="px-3 py-1 text-sm font-semibold rounded-md {{ $user->is_admin ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $user->is_admin ? 'Admin' : 'Customer' }}
                        </span>
                    </div>
                    <div><span class="font-semibold">Phone:</span> {{ $user->phone ?? 'Not provided' }}</div>
                    <div><span class="font-semibold">Registered:</span> {{ $user->created_at->format('d M Y') }}</div>

                    <div class="mt-2">
                        <button class="text-gray-600 underline text-sm hover:text-gray-800"
                                onclick="openUserDropdown(event, '{{ $user->id }}', {{ $user->is_admin ? 'true' : 'false' }})">
                            Actions
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">No users found</div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- User Action Dropdown -->
    <div id="user-dropdown" class="hidden absolute bg-white shadow-lg rounded-lg p-3 z-50 border border-gray-200">
        <form id="toggle-admin-form" method="POST" action="">
            @csrf
            @method('PATCH')
        </form>
        <button id="toggle-admin-btn" class="block w-full text-left px-4 py-2 text-blue-600 hover:bg-gray-100">
            Make Admin
        </button>
        @if(auth()->id() !== $user->id)
            <button id="delete-user-btn" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                Delete User
            </button>
        @endif
    </div>

    @push('scripts')
        <script>
            let selectedUserId = null;
            let selectedUserIsAdmin = false;

            function openUserDropdown(event, userId, isAdmin) {
                event.stopPropagation();
                selectedUserId = userId;
                selectedUserIsAdmin = isAdmin;

                const dropdown = document.getElementById('user-dropdown');
                const toggleBtn = document.getElementById('toggle-admin-btn');
                const toggleForm = document.getElementById('toggle-admin-form');

                // Update form action
                toggleForm.action = `/admin/users/${userId}/toggle-admin`;

                // Update button text based on current role
                toggleBtn.textContent = isAdmin ? 'Remove Admin' : 'Make Admin';

                // Position dropdown
                dropdown.classList.remove('hidden');
                dropdown.style.top = `${event.clientY + window.scrollY}px`;
                dropdown.style.left = `${event.clientX}px`;
            }

            // Toggle admin status
            document.getElementById('toggle-admin-btn').addEventListener('click', function() {
                if (selectedUserId) {
                    document.getElementById('toggle-admin-form').submit();
                }
                document.getElementById('user-dropdown').classList.add('hidden');
            });

            // Delete user button (placeholder)
            document.getElementById('delete-user-btn')?.addEventListener('click', function() {
                if (selectedUserId && confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    // Note: You'll need to implement the delete route and controller method
                    // For now, just show an alert
                    alert('Delete functionality to be implemented');
                }
                document.getElementById('user-dropdown').classList.add('hidden');
            });

            // Close dropdown on click outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('user-dropdown');
                if (!dropdown.contains(event.target) && !event.target.closest('button[onclick*="openUserDropdown"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        </script>
    @endpush
@endsection
