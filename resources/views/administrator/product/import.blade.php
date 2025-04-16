@extends('administrator.layouts.main')

@section('title')

@section('stylesheet')
    <style>
        #deleteButton {
            padding: 15px 30px;
            background-color: red;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        #deleteButton:hover {
            background-color: darkred;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
            transform: scale(1.05);
        }

        #deleteButton:active {
            background-color: crimson;
            transform: scale(1);
        }

        #deleteButton:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(255, 0, 0, 0.3);
        }

        /* .text-danger-custom {color: red; } */
    </style>
@endsection

@section('content')
    <!-- แจ้งเตือน -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <x-bread-crumb />
        <div class=" d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-4 text-danger"></i>
            <span class="fw-semibold fs-7 text-danger me-2">คำเตือน </span>
            <span class="fs-7"> : นำเข้าข้อมูลด้วยไฟล์ Excel<span class="fw-semibold fs-7 text-danger"> (.CSV)
                    เท่านั้น</span>
                ก่อนนำเข้ากรุณาตรวจสอบรูปแบบข้อมูลในไฟล์ให้ถูกต้อง</span>
        </div>
    </div>
    {{-- <button id="deleteButton">Clear DATA</button> --}}

    <div class="card p-5 shadow-lg border-0 rounded-4 bg-light ">
        {{-- <form action="{{ route('administrator.product.import.submit') }}" method="POST" enctype="multipart/form-data"> --}}
        {{-- @csrf --}}

        <div class="mb-4">
            <label for="type" class="form-label fs-5 fw-medium">Select Product Type</label>
            <select name="type" id="type" class="form-select shadow-sm border-0 rounded-3">
                <option value="">-- Select Product Type --</option>
                @foreach ($productTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="file" class="form-label fs-5 fw-medium">Choose File</label>
            <input type="file" name="file" id="file" class="form-control shadow-sm border-0 rounded-3"
                accept=".csv">
            @error('file')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="text-center">
            <button type="submit" id="import-button" class="btn btn-success px-5 py-3 shadow-sm rounded-3">
                <i class="bi bi-upload me-2"></i> Import
            </button>
        </div>

    </div>

@endsection



@section('script')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'ตกลง',
            });
            // .then(function() {
            //     Swal.close();
            //     window.location.href = '{{ route('administrator.product') }}';
            // });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $('#import-button').on('click', function(e) {
                // event.preventDefault();
                Swal.fire({
                    title: 'คุณแน่ใจไหม?',
                    text: 'คุณต้องการนำเข้าข้อมูลนี้หรือไม่?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ใช่, นำเข้าข้อมูล!',
                    cancelButtonText: 'ไม่, ยกเลิก!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'กำลังนำเข้าข้อมูล...',
                            text: 'กรุณารอสักครู่',
                            icon: 'info',
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        const type = $('#type').val();
                        const file = $('#file')[0].files[0];

                        if (!type || !file) {
                            Swal.close();
                            Swal.fire({
                                title: 'ข้อมูลไม่ครบถ้วน',
                                text: 'กรุณาเลือกประเภทสินค้าและไฟล์ที่ต้องการนำเข้า',
                                icon: 'warning',
                                confirmButtonText: 'ตกลง'
                            });
                            return;
                        }

                        const formData = new FormData();
                        formData.append('type', type);
                        formData.append('file', file);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                        $.ajax({
                            url: '/administrator/product/import/submit',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.close();
                                Swal.fire(
                                    'สำเร็จ!',
                                    'ข้อมูลถูกนำเข้าเรียบร้อยแล้ว!',
                                    'success'
                                );
                            },
                            error: function(xhr, status, error) {
                                Swal.close();
                                Swal.fire(
                                    'เกิดข้อผิดพลาด!',
                                    'มีปัญหาในการนำเข้าข้อมูล',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
    {{-- <script>
        $('#deleteButton').click(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to undo this action!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/administrator/product/detonate',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            );
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'There was an issue deleting the data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script> --}}
@endsection
