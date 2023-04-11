<div class="w-full h-full">
    <h2 class="text-center text-3xl">{{$record->prospect_names}} Details</h2>
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="w-full">
            <span class="text-center text-xl capitalize mb-4">PERSONAL INFORMATION</span>
            <div
                class="w-full max-w-sm bg-white border border-gray-200 mt-4 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-end px-4 pt-4">
                    <button id="dropdownButton" data-dropdown-toggle="dropdown"
                        class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5"
                        type="button">
                        <span class="sr-only">Open dropdown</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col items-center pb-10">
                    <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src={{url('/images/user.png')}}
                        alt="Bonnie image" />
                    <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{$record->prospect_names}}</h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{$record->prospect_telephone}}</span>
                </div>
                <div class="flex px-6 justify-between">
                    @if ($record->eligibility_status === \App\Models\Screening::ELIGIBLE)
                    <span style="background-color: green;padding:4px;border-radius:8px;font-size:12px;">
                        {{ $record->eligibility_status }}
                    </span>
                    @else
                    <span style="background-color: brown;padding:4px;border-radius:8px;font-size:12px;">
                        {{ $record->eligibility_status }}
                    </span>
                    @endif

                    @if ($record->confirmation_status === \App\Models\Screening::ACTIVE_CUSTOMER)
                    <span style="background-color: green;padding:4px;border-radius:8px;font-size:12px;">
                        {{ $record->confirmation_status }}
                    </span>
                    @elseif($record->confirmation_status === \App\Models\Screening::PRE_REGISTERED)
                    <span style="background-color: #ff8000;padding:4px;border-radius:8px;font-size:12px;">
                        {{ $record->confirmation_status }}
                    </span>
                    @else
                    <span style="background-color: brown;padding:4px;border-radius:8px;font-size:12px;">
                        {{ $record->confirmation_status }}
                    </span>
                    @endif

                </div>
                <div class="divide-y divide-gray-500">
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">national id</h5>
                            <p style="font-weight:800">{{$record->prospect_national_id}}</p>
                        </div>
                    </div>
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">Code</h5>
                            <p style="font-weight:800">{{$record->prospect_code}}</p>
                        </div>
                    </div>
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">Campaign</h5>
                            <p style="font-weight:800">{{$record->campaign->title}}</p>
                        </div>
                    </div>
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">District</h5>
                            <p style="font-weight:800">{{$record->district}}</p>
                        </div>
                    </div>
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">Sector</h5>
                            <p style="font-weight:800">{{$record->sector}}</p>
                        </div>
                    </div>
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">Cell</h5>
                            <p style="font-weight:800">{{$record->cell}}</p>
                        </div>
                    </div>
                    @if (Auth::user()->role->role !== \App\Models\Role::SECTOR_LEADER_ROLE)
                    <div class="flex justify-center flex-col p-6">
                        <div class="flex justify-between">
                            <h5 style="font-size: 14px;">Screened by</h5>
                            <p style="font-weight:800">{{$record->leader->user->name}}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="w-full">
            <span class="text-center text-xl capitalize mb-4">OTHER INFORMATION</span>
        </div>
    </div>
</div>