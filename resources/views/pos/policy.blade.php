@extends('pos.layout.app')

@section('content')

<div class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-6">

    <div class="max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">

            <div class="flex items-center justify-between gap-4">

                <div>
                    <h1 class="text-2xl md:text-xl font-bold text-slate-800">
                        Policies
                    </h1>
                </div>

            </div>

        </div>


        <div
            class="bg-white rounded-2xl
                   border border-slate-200
                   shadow-sm overflow-hidden"
        >

            {{-- Table Header --}}
            <div
                class="px-5 py-4
                       border-b border-slate-200
                       flex items-center justify-between"
            >

                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Company Policy
                    </h2>

                </div>

            </div>


            {{-- Desktop Table --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-slate-50">

                        <tr>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                #
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                Name
                            </th>

                            <th
                                class="px-5 py-3 text-left
                                       text-xs font-semibold
                                       text-slate-500 uppercase"
                            >
                                File
                            </th>


                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                     @forelse($policy as $key => $order)

                     <tr>

                    <td class="px-5 py-4 text-slate-500">

                        {{ $policy->firstItem() + $key }}

                    </td>

                    <td class="px-5 py-4">

                        <span
                            class="font-semibold
                                    text-slate-800"
                        >
                            {{ $order->name }}
                        </span>

                    </td>

                    <td class="px-5 py-4">

                        <a
                            href="{{ asset($order->pdf) }}"
                            class=""
                            title="View policy"
                        >
                            {{$order->pdf}}
                        </a>

                    </td>
                    <tr>

                     @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="px-5 py-16 text-center"
                                >

                                    <div
                                        class="w-16 h-16 mx-auto
                                               rounded-2xl
                                               bg-slate-100
                                               flex items-center
                                               justify-center"
                                    >
                                        <i 
    class="fas fa-file-contract 
           text-2xl 
           text-slate-400">
</i>
                                    </div>

                                    <h3
                                        class="mt-4
                                               text-lg
                                               font-semibold
                                               text-slate-700"
                                    >
                                        No Policies Found
                                    </h3>

                                </td>

                            </tr>

                        @endforelse
                        
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection