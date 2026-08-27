@extends('layouts.instructor')

@section('title', 'My Schedule')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- ========================================================= -->
<!-- PAGE HEADER -->
<!-- ========================================================= -->

<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">

    <div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#0E2958]">
            My Schedule
        </h1>

        <p class="text-gray-500 mt-2">
            View and manage your assigned teaching schedule.
        </p>

    </div>

</div>


    <!-- ========================================================= -->
    <!-- SUCCESS MESSAGE -->
    <!-- ========================================================= -->

    @if(session('success'))

        <div
            class="mb-6 flex items-start gap-3
                   bg-green-50
                   border border-green-200
                   text-green-700
                   rounded-xl
                   p-4">

            <x-heroicon-o-check-circle
                class="w-5 h-5 mt-0.5 flex-shrink-0"/>

            <p class="font-medium">
                {{ session('success') }}
            </p>

        </div>

    @endif


    <!-- ========================================================= -->
    <!-- ERROR MESSAGE -->
    <!-- ========================================================= -->

    @if(session('error'))

        <div
            class="mb-6 flex items-start gap-3
                   bg-red-50
                   border border-red-200
                   text-red-700
                   rounded-xl
                   p-4">

            <x-heroicon-o-exclamation-circle
                class="w-5 h-5 mt-0.5 flex-shrink-0"/>

            <p class="font-medium">
                {{ session('error') }}
            </p>

        </div>

    @endif


    <!-- ========================================================= -->
    <!-- SCHEDULE TABLE -->
    <!-- ========================================================= -->

    <div
        class="bg-white
               rounded-2xl
               border border-gray-200
               shadow-sm
               overflow-hidden">


        <!-- Table Header -->

        <div
            class="px-5 sm:px-6 py-5
                   border-b border-gray-200
                   flex flex-col sm:flex-row
                   sm:items-center
                   sm:justify-between
                   gap-3">

            <div>

                <h2 class="text-xl font-bold text-[#0E2958]">
                    Assigned Schedule
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Your current teaching assignments.
                </p>

            </div>

        </div>


        <!-- Horizontal Scroll -->

        <div class="overflow-x-auto">

            <table class="min-w-[1050px] w-full">

                <!-- ================================================= -->
                <!-- TABLE HEAD -->
                <!-- ================================================= -->

                <thead class="bg-[#0E2958] text-white">

                    <tr>

                        <th class="px-5 py-4 text-left text-sm font-semibold">
                            Subject
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Room
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Day
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Time
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Semester
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            School Year
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center text-sm font-semibold">
                            Action
                        </th>

                    </tr>

                </thead>


                <!-- ================================================= -->
                <!-- TABLE BODY -->
                <!-- ================================================= -->

                <tbody class="divide-y divide-gray-100">

                @forelse($schedules as $schedule)

                    <tr
                        class="group
                               hover:bg-[#0E4C6B]/[0.025]
                               transition">


                        <!-- ========================================= -->
                        <!-- SUBJECT -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5">

                            <div class="flex items-start gap-3">

                                <div
                                    class="w-10 h-10
                                           rounded-lg
                                           bg-[#0E4C6B]/10
                                           flex items-center
                                           justify-center
                                           flex-shrink-0">

                                    <x-heroicon-o-book-open
                                        class="w-5 h-5 text-[#0E4C6B]"/>

                                </div>


                                <div class="min-w-0">

                                    <span
                                        class="inline-flex
                                               bg-[#0E4C6B]/10
                                               text-[#0E4C6B]
                                               px-2.5 py-1
                                               rounded-lg
                                               text-xs
                                               font-semibold">

                                        {{ $schedule->subject_code }}

                                    </span>

                                    <p
                                        class="mt-2
                                               font-semibold
                                               text-gray-800
                                               whitespace-normal">

                                        {{ $schedule->subject_name }}

                                    </p>

                                </div>

                            </div>

                        </td>


                        <!-- ========================================= -->
                        <!-- ROOM -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            <div
                                class="inline-flex
                                       items-center gap-2
                                       text-gray-700">

                                <x-heroicon-o-home-modern
                                    class="w-4 h-4 text-gray-400"/>

                                <span class="font-medium">
                                    {{ $schedule->room->room_name }}
                                </span>

                            </div>

                        </td>


                        <!-- ========================================= -->
                        <!-- DAY -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            <span class="text-gray-700 font-medium">
                                {{ $schedule->day }}
                            </span>

                        </td>


                        <!-- ========================================= -->
                        <!-- TIME -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            <div
                                class="inline-flex
                                       items-center gap-2
                                       bg-gray-50
                                       border border-gray-200
                                       rounded-lg
                                       px-3 py-2
                                       text-sm
                                       text-gray-700
                                       whitespace-nowrap">

                                <x-heroicon-o-clock
                                    class="w-4 h-4 text-[#0E4C6B]"/>

                                {{ date('g:i A', strtotime($schedule->start_time)) }}

                                <span class="text-gray-400">
                                    –
                                </span>

                                {{ date('g:i A', strtotime($schedule->end_time)) }}

                            </div>

                        </td>


                        <!-- ========================================= -->
                        <!-- SEMESTER -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            <span
                                class="inline-flex
                                       bg-gray-100
                                       text-gray-700
                                       px-3 py-1.5
                                       rounded-lg
                                       text-sm
                                       font-medium">

                                {{ $schedule->semester }}

                            </span>

                        </td>


                        <!-- ========================================= -->
                        <!-- SCHOOL YEAR -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            <span
                                class="text-gray-700
                                       font-medium
                                       whitespace-nowrap">

                                {{ $schedule->school_year }}

                            </span>

                        </td>


                        <!-- ========================================= -->
                        <!-- STATUS -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">

                            @if($schedule->status == 'scheduled')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           bg-yellow-50
                                           text-yellow-700
                                           border border-yellow-200
                                           px-3 py-1.5
                                           rounded-full
                                           text-xs
                                           font-semibold">

                                    <span
                                        class="w-2 h-2
                                               rounded-full
                                               bg-yellow-500">
                                    </span>

                                    Scheduled

                                </span>


                            @elseif($schedule->status == 'in_progress')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           bg-red-50
                                           text-red-700
                                           border border-red-200
                                           px-3 py-1.5
                                           rounded-full
                                           text-xs
                                           font-semibold">

                                    <span
                                        class="w-2 h-2
                                               rounded-full
                                               bg-red-500">
                                    </span>

                                    In Progress

                                </span>


                            @elseif($schedule->status == 'completed')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           bg-green-50
                                           text-green-700
                                           border border-green-200
                                           px-3 py-1.5
                                           rounded-full
                                           text-xs
                                           font-semibold">

                                    <span
                                        class="w-2 h-2
                                               rounded-full
                                               bg-green-500">
                                    </span>

                                    Completed

                                </span>


                            @elseif($schedule->status == 'cancelled')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           bg-gray-100
                                           text-gray-600
                                           border border-gray-200
                                           px-3 py-1.5
                                           rounded-full
                                           text-xs
                                           font-semibold">

                                    <span
                                        class="w-2 h-2
                                               rounded-full
                                               bg-gray-400">
                                    </span>

                                    Cancelled

                                </span>

                            @endif

                        </td>


                        <!-- ========================================= -->
                        <!-- ACTION -->
                        <!-- ========================================= -->

                        <td class="px-5 py-5 text-center">


                            @if($schedule->status == 'scheduled')

                                <!-- Start Class -->

                                <form
    action="{{ route('instructor.schedule.start', $schedule) }}"
    method="POST"
    class="start-class-form">

    @csrf
    @method('PATCH')

    <button
        type="button"
        onclick="openClassConfirm(
            this.closest('form'),
            'Start Class?',
            'Are you sure you want to start this class?',
            'Start Class'
        )"
        class="inline-flex
               items-center
               justify-center
               gap-2
               bg-[#0E4C6B]
               hover:bg-[#0B3D56]
               text-white
               px-4 py-2.5
               rounded-xl
               text-sm
               font-semibold
               transition
               shadow-sm
               hover:shadow">

        <x-heroicon-o-play class="w-4 h-4"/>

        Start Class

    </button>

</form>


                            @elseif($schedule->status == 'in_progress')

                                <!-- End Class -->

                              <form
    action="{{ route('instructor.schedule.end', $schedule) }}"
    method="POST"
    class="end-class-form">

    @csrf
    @method('PATCH')

    <button
        type="button"
        onclick="openClassConfirm(
            this.closest('form'),
            'End Class?',
            'Are you sure you want to end this class?',
            'End Class'
        )"
        class="inline-flex
               items-center
               justify-center
               gap-2
               bg-red-600
               hover:bg-red-700
               text-white
               px-4 py-2.5
               rounded-xl
               text-sm
               font-semibold
               transition
               shadow-sm
               hover:shadow">

        <x-heroicon-o-stop class="w-4 h-4"/>

        End Class

    </button>

</form>


                            @elseif($schedule->status == 'completed')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           text-green-600
                                           font-semibold
                                           text-sm">

                                    <x-heroicon-o-check-circle
                                        class="w-5 h-5"/>

                                    Completed

                                </span>


                            @elseif($schedule->status == 'cancelled')

                                <span
                                    class="inline-flex
                                           items-center gap-2
                                           text-gray-500
                                           font-semibold
                                           text-sm">

                                    <x-heroicon-o-x-circle
                                        class="w-5 h-5"/>

                                    Cancelled

                                </span>


                            @else

                                <span class="text-gray-400">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="px-6 py-16 text-center">

                            <div
                                class="flex flex-col
                                       items-center
                                       justify-center">

                                <div
                                    class="w-16 h-16
                                           rounded-2xl
                                           bg-gray-100
                                           flex items-center
                                           justify-center
                                           mb-4">

                                    <x-heroicon-o-calendar-days
                                        class="w-8 h-8 text-gray-400"/>

                                </div>

                                <h3
                                    class="font-semibold
                                           text-gray-700">

                                    No schedules assigned

                                </h3>

                                <p
                                    class="text-sm
                                           text-gray-500
                                           mt-1">

                                    Your assigned classes will appear here.

                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>


        <!-- ========================================================= -->
        <!-- PAGINATION -->
        <!-- ========================================================= -->

        @if($schedules->hasPages())

            <div
                class="px-5 sm:px-6 py-4
                       border-t border-gray-200
                       overflow-x-auto">

                {{ $schedules->links() }}

            </div>

        @endif

    </div>

</div>


<!-- ========================================================= -->
<!-- CLASS CONFIRMATION MODAL -->
<!-- ========================================================= -->

<div
    id="classConfirmModal"
    class="fixed inset-0 z-[100] hidden items-center justify-center px-4">

    <!-- Overlay -->
    <div
        id="classConfirmOverlay"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm">
    </div>


    <!-- Modal -->
    <div
        id="classConfirmBox"
        class="relative w-full max-w-md
               bg-white
               rounded-2xl
               shadow-2xl
               border border-gray-200
               p-6 sm:p-7
               transform transition-all">


        <!-- Icon -->

        <div class="flex justify-center mb-5">

            <div
                id="classConfirmIcon"
                class="w-14 h-14
                       rounded-2xl
                       bg-[#0E4C6B]/10
                       flex items-center
                       justify-center">

                <x-heroicon-o-play
                    id="classConfirmIconPlay"
                    class="w-7 h-7 text-[#0E4C6B]"/>

            </div>

        </div>


        <!-- Title -->

        <h2
            id="classConfirmTitle"
            class="text-xl sm:text-2xl
                   font-bold
                   text-[#0E2958]
                   text-center">

            Start Class?

        </h2>


        <!-- Message -->

        <p
            id="classConfirmMessage"
            class="text-gray-500
                   text-center
                   mt-2
                   leading-relaxed">

            Are you sure you want to start this class?

        </p>


        <!-- Buttons -->

        <div
            class="flex flex-col-reverse
                   sm:flex-row
                   gap-3
                   mt-7">

            <button
                type="button"
                onclick="closeClassConfirm()"
                class="flex-1
                       px-5 py-3
                       rounded-xl
                       border border-gray-300
                       text-gray-700
                       font-semibold
                       hover:bg-gray-50
                       transition">

                Cancel

            </button>


            <button
                id="classConfirmButton"
                type="button"
                class="flex-1
                       px-5 py-3
                       rounded-xl
                       bg-[#0E4C6B]
                       hover:bg-[#0B3D56]
                       text-white
                       font-semibold
                       transition">

                Start Class

            </button>

        </div>

    </div>

</div>


<script>

    let classConfirmForm = null;


    function openClassConfirm(form, title, message, actionText)
    {
        classConfirmForm = form;

        const modal = document.getElementById('classConfirmModal');
        const titleElement = document.getElementById('classConfirmTitle');
        const messageElement = document.getElementById('classConfirmMessage');
        const button = document.getElementById('classConfirmButton');
        const icon = document.getElementById('classConfirmIcon');

        titleElement.textContent = title;
        messageElement.textContent = message;
        button.textContent = actionText;


        // Start Class styling
        if (actionText === 'Start Class') {

            button.className =
                'flex-1 px-5 py-3 rounded-xl bg-[#0E4C6B] ' +
                'hover:bg-[#0B3D56] text-white font-semibold transition';

            icon.className =
                'w-14 h-14 rounded-2xl bg-[#0E4C6B]/10 ' +
                'flex items-center justify-center';

        }


        // End Class styling
        else {

            button.className =
                'flex-1 px-5 py-3 rounded-xl bg-red-600 ' +
                'hover:bg-red-700 text-white font-semibold transition';

            icon.className =
                'w-14 h-14 rounded-2xl bg-red-50 ' +
                'flex items-center justify-center';

        }


        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');
    }


    function closeClassConfirm()
    {
        const modal = document.getElementById('classConfirmModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.classList.remove('overflow-hidden');

        classConfirmForm = null;
    }


    document
        .getElementById('classConfirmButton')
        .addEventListener('click', function () {

            if (classConfirmForm) {

                classConfirmForm.submit();

            }

        });


    document
        .getElementById('classConfirmOverlay')
        .addEventListener('click', function () {

            closeClassConfirm();

        });


    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeClassConfirm();

        }

    });

</script>
@endsection