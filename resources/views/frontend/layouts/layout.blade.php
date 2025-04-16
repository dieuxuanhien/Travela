<!DOCTYPE html>
<html lang="en">

@include('frontend.component.head')

@if(auth()->check())
    @include('frontend.component.top-bar-logged-in')
@else
    @include('frontend.component.top-bar')
@endif

@yield('content')


@include('.frontend.component.script')




<script>
    // $(document).ready(function() {
    $('.target-day').on('click', function () {
        console.log('1232')
        const targetId = $(this).data('data-target');
        console.log(targetId)
        const target = $('#' + targetId);
        const container = $('#schedule-content');

        if (target.length) {
            // Tính vị trí top của div mục tiêu so với wrapper
            const scrollPosition = target.position().top + container.scrollTop();

            container.animate({
                scrollTop: scrollPosition
            }, 600); // thời gian scroll
        }
    });
    // });
</script>

{{--<script>--}}
{{--    document.addEventListener('livewire:load', function () {--}}
{{--        $('.create_conversation').on('click', function() {--}}
{{--            const id = $(this).data('id');--}}
{{--            Livewire.emit('createConversation', id);--}}
{{--            console.log('Sự kiện đã phát: createConversation với ID:', id);--}}

{{--        });--}}
{{--    });--}}
{{--</script>--}}
<script>
    // $(document).ready(function () {
    //     console.log('aaaaa')
    //     $('#searchInput').on('input', function () {
    //         let query = $(this).val().trim();
    //         console.log(query)
    //         console.log('bbbbbb')
    //
    //         if (query.length === 0) {
    //             $('#results').hide();
    //             return;
    //         }
    //
    //         $.ajax({
    //             url: '/api/search',
    //             type: 'GET',
    //             data: { q: query },
    //             success: function (data) {
    //                 let resultsDiv = $('#results');
    //                 resultsDiv.empty();
    //
    //                 if (data.length === 0) {
    //                     resultsDiv.hide();
    //                     return;
    //                 }
    //                 $('#searchInput').addClass('rounded-top rounded-0');
    //
    //                 $.each(data, function (index, item) {
    //                     resultsDiv.append(`<div class="result-item">${item.name}</div>`);
    //                 });
    //
    //                 resultsDiv.show();
    //             },
    //             error: function (xhr) {
    //                 console.error('Lỗi khi gọi API:', xhr);
    //             }
    //         });
    //     });
    //
    //     $(document).on('click', '.result-item', function () {
    //         $('#searchInput').val($(this).text());
    //         $('#searchInput').removeClass('rounded-top rounded-0');
    //         $('#results').hide();
    //     });
    //
    //     $(document).click(function (e) {
    //         if (!$(e.target).closest('#searchInput, #results').length) {
    //             $('#searchInput').removeClass('rounded-top rounded-0');
    //             $('#results').hide();
    //         }
    //     });
    // });
</script>

@if (isset(session('alert_')['alert__type']))
    <script>
        $(document).ready(function (){
            alert_after_load('{{ session('alert_.alert__type') }}', '{{ session('alert_.alert__title') }}', '{{ session('alert_.alert__text') }}', '{{ session('alert_.alert_reload') }}');
            @php
                $userData = Illuminate\Support\Facades\Session::get('alert_', []);
                unset($userData['alert__title']);
                unset($userData['alert__type']);
                unset($userData['alert__text']);
                Illuminate\Support\Facades\Session::put('alert_', $userData);
            @endphp
        });
    </script>
@endif

@if (isset(session('alert_2')['alert_type']))
    <script>
        console.log('1234')
        alert_after_load_2('{{session('alert_2')['alert_title']}}', '{{session('alert_2')['alert_type']}}', '{{session('alert_2')['alert_reload']}}')
        @php
            $userData = Illuminate\Support\Facades\Session::get('alert_2', []);
            unset($userData['alert_title']);
            unset($userData['alert_type']);
            Illuminate\Support\Facades\Session::put('alert_2', $userData);
        @endphp
    </script>
    @endif
    </body>
</html>
