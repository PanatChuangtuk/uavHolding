@extends('main')
@section('title')
    @lang('messages.track_and_trace')
@endsection
@section('stylesheet')
    <style>
        #tracking_result {
            background-color: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            /* font-family: 'Arial', sans-serif; */
        }

        #tracking_result p {
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        #tracking_result table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        #tracking_result th,
        #tracking_result td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1.05em;
        }

        #tracking_result th {
            background-color: #255f4f;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        #tracking_result td {
            color: #333;
        }

        #tracking_result tr:hover {
            background-color: #f1f1f1;
        }

        #tracking_result .text-success {
            color: #28a745;
        }

        #tracking_result .text-danger {
            color: #dc3545;
        }

        #tracking_result .tracking-header {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
        }

        .search-tracking {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-tracking input {
            padding: 10px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 250px;
            margin-right: 10px;
        }

        .search-tracking button {
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 6px;
            background-color: #375B51;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-tracking button:hover {
            background-color: #1e8366;
        }
    </style>
@endsection
@section('content')
    <div class="section p-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(app()->getLocale() . '/ ') }}">@lang('messages.home')</a></li>
                <li class="breadcrumb-item active">@lang('messages.track_and_trace')</li>
            </ol>

            <h1 class="h2 pt-2 text-capitalize text-underline">@lang('messages.track_and_trace')</h1>

            <div class="boxed track-trace my-4">
                <img class="img" src="{{ asset('img/thumb/photo-sammy-delivery.jpg') }}" alt="" />

                <div class="form-group search-tracking">
                    <input type="text" class="form-control" id="tracking_number"
                        placeholder="Enter tracking number..." />
                    <button class="btn btn-secondary" id="btn_track" type="button">Track</button>
                </div>

                <div id="tracking_result" class="tracking-result p-4 bg-light rounded shadow-sm"></div>

                <div class="p-5"></div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $("#btn_track").click(function() {
                let trackingNumber = $("#tracking_number").val().trim();


                if (trackingNumber === "") {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกหมายเลขพัสดุ',
                        text: 'กรุณากรอกหมายเลขพัสดุที่ถูกต้องเพื่อทำการติดตาม',
                        confirmButtonText: 'ตกลง',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }


                $.ajax({
                    url: "/get-track-show",
                    type: "POST",
                    data: {
                        tracking_number: trackingNumber,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $("#tracking_result").html("<p>กำลังโหลดข้อมูล...</p>");
                    },
                    success: function(response) {
                        var statusMap = {
                            'Approve': 'อนุมัติ',
                            'Cancel': 'ยกเลิก',
                            'Processed': 'กำลังดำเนินการ',
                            'Delivery': 'จัดส่งแล้ว',
                            'Default': 'รออนุมัติ'
                        };

                        let resultHtml =
                            `<p><strong>หมายเลขพัสดุ:</strong> ${response.tracking_no}</p>`;
                        resultHtml +=
                            `<p><strong>สถานะ: </strong>${statusMap[response.status] || statusMap['default']}</p>`;
                        resultHtml +=
                            `<p><strong>คาดว่าจะจัดส่ง:</strong> ${response.updated_at}</p>`;

                        resultHtml += `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ชื่อสินค้า</th>
                                    <th>รหัสสินค้า</th>
                                     <th>สถานะสินค้า</th>
                                </tr>
                            </thead>
                            <tbody>`;

                        response.products.forEach(function(product) {

                            const statusMap = {
                                'Canceled': 'ยกเลิก',
                                'Failed': 'ล้มเหลว',
                                'Pending': 'รออนุมัติ',
                                'Processed': 'กำลังดำเนินการ',
                                'Processing': 'เตรียมจัดส่ง',
                                'Shipped': 'ส่งสินค้าแล้ว',
                                'Refunded': 'คืนเงิน',
                                'Complete': 'เสร็จสมบูรณ์',
                                'Expired': 'หมดอายุ'
                            };
                            let statusText = statusMap[product.status_product] ||
                                'รออนุมัติ';
                            resultHtml += `
                            <tr>
                                <td>${product.name}</td>
                                  <td>${product.sku}</td>
                                    <td>${statusText}</td>
                            </tr>`;

                        });

                        resultHtml += `</tbody></table>`;
                        $("#tracking_result").html(resultHtml);
                    },
                    error: function(xhr) {
                        $("#tracking_result").html(
                            `<p class='text-danger'>${xhr.responseJSON?.error || 'เกิดข้อผิดพลาด กรุณาลองใหม่'}</p>`
                        );
                    }
                });
            });
        });
    </script>
@endsection
