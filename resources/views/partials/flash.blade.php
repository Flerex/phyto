@if(Session::has('alert'))
    {{-- We use the non-escaped HTML {!!  !!}} syntax because some flash messages can contain <strong> tags. --}}
    <div id="flashAlert" class="notification is-primary">{!! Session::get('alert') !!}</div>
    <script>
        !function () {
            const flash = document.getElementById('flashAlert');

            if (!flash) return;

            flash.addEventListener('transitionend', function () {
                if (flash.classList.contains('hidden'))
                    flash.remove();
            });

            setTimeout(function () {
                flash.classList.add('hidden');
            }, 3000);

        }();
    </script>
@endif