@extends('main')
@section('title')
    @lang('messages.my_favourite')
@endsection
@section('stylesheet')
@endsection
@section('content')
    <div class="section section-profile bg-light pt-0">
        <div class="container has-sidebar">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">@lang('messages.profile')</li>
            </ol>
            <x-nav-profile />
            <div class="content">
                <div class="card-info main px-5">
                    <h2 class="title-md">@lang('messages.my_favourite')</h2>
                    <div class="row g-3 g-sm-4 gx-md-5 pt-4">
                        @if (count($isFavorite) === 0)
                            <div class="empty-cart-message">
                                <h3 style="color: #375B51;">@lang('messages.item_is_empty')</h3>
                            </div>
                        @else
                            @foreach ($isFavorite as $item)
                                <div class="col-md-4 col-6" id="product-{{ $item->id }}">
                                    <div class="card-product thumb">
                                        <a class="card-link"
                                            href="{{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->id]) }}"></a>
                                        <div class="card-photo">
                                            <div class="photo"
                                                style="background-image: url(img/thumb/photo-600x600--3.jpg);">
                                                <img src="img/thumb/frame-100x100.png" alt="">
                                            </div>
                                        </div>
                                        <div class="card-body px-0">
                                            <h3>{{ $item->name }}</h3>
                                            <p class="code">@lang('messages.product_code') : {{ $item->code }}</p>
                                        </div>
                                        <div class="button-block">
                                            <div class="dropdown pd-delete">
                                                <button class="btn btn-action" type="button" data-bs-toggle="dropdown"
                                                    data-bs-display="static">
                                                    <span class="icons"></span>
                                                </button>
                                                <form id="favorite-form"
                                                    action="{{ route('product.favourite', ['lang' => app()->getLocale(), 'id' => $item->id]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <div class="dropdown-menu">
                                                        <button class="btn" id="favorite-button"
                                                            data-product-id="{{ $item->id }}"
                                                            type="submit">@lang('messages.remove_from_favorites')
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            <a class="btn btn-cart"
                                                href="{{ route('product.detail', ['lang' => app()->getLocale(), 'id' => $item->id]) }}">
                                                <span class="icons"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('[id^="favorite-button"]').click(function(event) {
                event.preventDefault();
                var productId = $(this).data('product-id');
                var productBlock = $('#product-' + productId);
                Swal.fire({
                    icon: 'warning',
                    title: '@lang('messages.remove_from_favorites')',
                    text: '@lang('messages.item_removed_from_favorites')',
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: '@lang('messages.ok')',
                    cancelButtonText: '@lang('messages.cancel')',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product.favourite', ['lang' => app()->getLocale(), 'id' => ':id']) }}"
                                .replace(':id', productId),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.status === 'removed') {
                                    productBlock.hide();
                                    Swal.fire({
                                        icon: 'success',
                                        title: '@lang('messages.item_removed_from_favorites')',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("AJAX error: " + status + ": " + error);
                            }
                        });
                    } else if (result.isDismissed) {}
                });
            });
        });
    </script>
@endsection
