<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        @if(session()->has('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session()->has('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Select Course</label>
                                    <select wire:model.live="selectedCourse" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Choose a course</option>
                                        @foreach($availableCourses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedCourse') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Number of Seats</label>
                                    <input type="number" wire:model.live="seats" min="1" max="1000" class="mt-1 block w-full rounded-md border-gray-300">
                                    @error('seats') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Duration (months)</label>
                                    <select wire:model.live="duration" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="3">3 months</option>
                                        <option value="6">6 months</option>
                                        <option value="12">12 months</option>
                                        <option value="forever">Lifetime</option>
                                    </select>
                                    @error('duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                @if($totalPrice > 0)
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-lg font-semibold">Total Price: £{{ number_format($totalPrice, 2) }}</p>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button wire:click="purchase" type="button" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                            Purchase
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 