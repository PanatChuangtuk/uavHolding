document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("theme-toggle");
    const themeIcon = themeToggle.querySelector(".theme-icon");
    const htmlElement = document.documentElement;
    const bodyElement = document.body;

    // ดึงธีมที่บันทึกไว้ใน localStorage หรือใช้ธีมเริ่มต้นเป็น "light"
    const savedTheme = localStorage.getItem("theme") || "light";

    // ฟังก์ชันสำหรับอัปเดตฟอร์มในธีมมืด
    const updateFormElements = (isDarkMode) => {
        const formElements = document.querySelectorAll("textarea, input, select, .form-control, .areaEditor");

        formElements.forEach((element) => {
            if (isDarkMode) {
                element.style.backgroundColor = "#2b2c40"; // พื้นหลังสีเข้ม
                element.style.color = "#ffffff"; // สีตัวอักษร
                element.style.borderColor = "#666"; // สีขอบ
            } else {
                element.style.backgroundColor = ""; // คืนค่าเดิม
                element.style.color = ""; // คืนค่าเดิม
                element.style.borderColor = ""; // คืนค่าเดิม
            }
        });
    };

    // ฟังก์ชันสำหรับตั้งค่าธีม
    const applyTheme = (isDarkMode) => {
        if (isDarkMode) {
            htmlElement.classList.add("dark");
            bodyElement.classList.add("dark");
            themeIcon.classList.remove("bx-moon");
            themeIcon.classList.add("bx-sun");
            bodyElement.classList.add("dark-mode");
        } else {
            htmlElement.classList.remove("dark");
            bodyElement.classList.remove("dark");
            themeIcon.classList.remove("bx-sun");
            themeIcon.classList.add("bx-moon");
            bodyElement.classList.remove("dark-mode");
        }

        // อัปเดตฟอร์มตามธีม
        updateFormElements(isDarkMode);
    };

    // ตั้งค่าธีมเริ่มต้นเมื่อโหลดหน้า
    setTimeout(() => {
        const isDarkMode = savedTheme === "dark";
        applyTheme(isDarkMode);

        // ตั้งค่าการเปลี่ยนธีมเมื่อคลิกปุ่ม
        themeToggle.addEventListener("click", function () {
            const currentTheme = htmlElement.classList.contains("dark") ? "dark" : "light";
            const newTheme = currentTheme === "dark" ? "light" : "dark";
            const isDarkMode = newTheme === "dark";

            // ใช้ธีมใหม่
            applyTheme(isDarkMode);

            // บันทึกธีมใหม่ใน localStorage
            localStorage.setItem("theme", newTheme);
        });

        // ลบคลาส "loading" หลังจากตั้งค่าธีม
        htmlElement.classList.remove("loading");
    }, 0);
});