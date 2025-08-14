<div class="container mx-auto py-8 max-w-4xl">
    <h1 class="text-3xl font-semibold text-center bg-gradient-to-r from-purple-400 via-green-400 to-blue-400 py-6 rounded-lg shadow-lg mb-6">Add New Offer</h1>
    
    <!-- Add Offer Product Form -->
    <form action="" method="post" enctype="multipart/form-data" class="mx-auto space-y-6">
        
        <!-- Offer Title -->
        <div class="form-group">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="4" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <!-- Discount -->
        <div class="form-group">
            <label for="discount" class="block text-sm font-medium text-gray-700">Discount</label>
            <input type="number" id="discount" name="discount" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Start Date -->
        <div class="form-group">
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- End Date -->
        <div class="form-group">
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <!-- Status -->
        <div class="form-group">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="form-control w-full py-3 px-4 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Submit Offer
            </button>
        </div>
    </form>
</div>
