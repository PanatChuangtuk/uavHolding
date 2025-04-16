<!-- resources/views/contact.blade.php -->
<form action="{{ route('sendEmail') }}" method="POST">
    @csrf
    <div>
        <label for="name">ชื่อ</label>
        <input type="text" name="name" id="name" required>
    </div>

    <div>
        <label for="email">อีเมล</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div>
        <label for="message">ข้อความ</label>
        <textarea name="message" id="message" required></textarea>
    </div>

    <button type="submit">ส่ง</button>
</form>
