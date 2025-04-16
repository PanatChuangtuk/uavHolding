document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById("provinceSelect");
    const districtSelect = document.getElementById("districtSelect");
    const subdistrictSelect = document.getElementById("subdistrictSelect");
    const postalCodeSelect = document.getElementById("postalCodeSelect");

    function loadProvinces() {
        $.ajax({
            url: "/get-provinces",
            type: "GET",
            dataType: "json",
            success: function (data) {
                provinceSelect.innerHTML = `<option value="">${
                    provinceSelect.dataset.lang === "en"
                        ? "Select Province"
                        : "เลือกจังหวัด"
                }</option>`;
                data.forEach(function (province) {
                    const provinceName =
                        provinceSelect.dataset.lang === "en"
                            ? province.name_en
                            : province.name_th;
                    provinceSelect.innerHTML += `<option value="${province.id}">${provinceName}</option>`;
                });
            },
        });
    }

    function loadDistricts(provinceId) {
        if (!provinceId) {
            districtSelect.innerHTML = `<option value="">${
                districtSelect.dataset.lang === "en"
                    ? "Select District"
                    : "เลือกอำเภอ"
            }</option>`;
            subdistrictSelect.innerHTML = `<option value="">${
                subdistrictSelect.dataset.lang === "en"
                    ? "Select Subdistrict"
                    : "เลือกตำบล"
            }</option>`;
            postalCodeSelect.innerHTML = `<option value="">${
                postalCodeSelect.dataset.lang === "en"
                    ? "Select Postal Code"
                    : "เลือกไปรษณีย์"
            }</option>`;
            return;
        }

        $.ajax({
            url: `/get-districts/${provinceId}`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                districtSelect.innerHTML = `<option value="">${
                    districtSelect.dataset.lang === "en"
                        ? "Select District"
                        : "เลือกอำเภอ"
                }</option>`;
                data.forEach(function (district) {
                    districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                });
            },
        });
    }

    function loadSubdistricts(districtId) {
        if (!districtId) {
            subdistrictSelect.innerHTML = `<option value="">${
                subdistrictSelect.dataset.lang === "en"
                    ? "Select Subdistrict"
                    : "เลือกตำบล"
            }</option>`;
            postalCodeSelect.innerHTML = `<option value="">${
                postalCodeSelect.dataset.lang === "en"
                    ? "Select Postal Code"
                    : "เลือกไปรษณีย์"
            }</option>`;
            return;
        }

        $.ajax({
            url: `/get-subdistricts/${districtId}`,
            type: "GET",
            dataType: "json",
            success: function (data) {
                subdistrictSelect.innerHTML = `<option value="">${
                    subdistrictSelect.dataset.lang === "en"
                        ? "Select Subdistrict"
                        : "เลือกตำบล"
                }</option>`;
                data.forEach(function (subdistrict) {
                    subdistrictSelect.innerHTML += `<option value="${subdistrict.id}" data-postcode="${subdistrict.zip_code}">${subdistrict.name}</option>`;
                });
            },
        });
    }

    function updatePostalCode() {
        const selectedOption =
            subdistrictSelect.options[subdistrictSelect.selectedIndex];
        const postalCode = selectedOption.getAttribute("data-postcode") || "";
        postalCodeSelect.innerHTML = `<option value="${postalCode}">${postalCode}</option>`;
    }

    provinceSelect.addEventListener("change", function () {
        loadDistricts(provinceSelect.value);
    });

    districtSelect.addEventListener("change", function () {
        loadSubdistricts(districtSelect.value);
    });

    subdistrictSelect.addEventListener("change", function () {
        updatePostalCode();
    });

    loadProvinces();
});
