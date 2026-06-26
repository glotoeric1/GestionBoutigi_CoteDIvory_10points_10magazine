@guest
    <script>window.location = "{{route('/')}}";</script>
@endguest
@auth
    @if (auth()->user()->roles === "Super Admin")
        <script>window.location = "{{route('minidashboard')}}";</script>
    @else
        <script>window.location = "{{route('dashboard')}}";</script>
    @endif

@endauth