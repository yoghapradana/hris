@extends('layouts.app')

@section('content')
<div class="pt-4">
    <div class="container-fluid">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Sticky Topbar Test Page</h5>
                <p class="card-text">Scroll down to see if the topbar stays visible at the top.</p>
            </div>
        </div>

        <div style="height: 2000px;" class="bg-light border text-center">
            <p class="pt-5">This is just filler content to enable scrolling. The topbar should stick as you scroll.</p>
        </div>
    </div>
</div>
@endsection
