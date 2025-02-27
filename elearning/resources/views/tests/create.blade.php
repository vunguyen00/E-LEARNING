<form method="POST" action="{{ route('tests.store', $course->id) }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Tên bài kiểm tra</label>
        <input type="text" class="form-control" name="title" required>
    </div>

    <h3>Câu hỏi</h3>
    <div id="questions">
        <div class="question">
            <input type="text" class="form-control" name="questions[0][question]" placeholder="Nhập câu hỏi" required>
            <div class="answers">
                <input type="text" class="form-control" name="questions[0][answers][]" placeholder="Đáp án 1" required>
                <input type="text" class="form-control" name="questions[0][answers][]" placeholder="Đáp án 2" required>
                <input type="text" class="form-control" name="questions[0][answers][]" placeholder="Đáp án 3" required>
                <label>Đáp án đúng: <input type="number" name="questions[0][correct_answer]" min="0" required></label>
            </div>
        </div>
    </div>

    <button type="button" id="add-question" class="btn btn-secondary mt-2">➕ Thêm câu hỏi</button>
    <button type="submit" class="btn btn-primary mt-2">💾 Lưu bài kiểm tra</button>
    @if(isset($course->test))
    <a href="{{ route('tests.edit', $course->test->id) }}" class="btn btn-warning">✏️ Sửa bài kiểm tra</a>
    @endif
</form>

<script>
document.getElementById('add-question').addEventListener('click', function () {
    let index = document.querySelectorAll('.question').length;
    let newQuestion = document.createElement('div');
    newQuestion.classList.add('question');
    newQuestion.innerHTML = `
        <input type="text" class="form-control" name="questions[\${index}][question]" placeholder="Nhập câu hỏi" required>
        <div class="answers">
            <input type="text" class="form-control" name="questions[\${index}][answers][]" placeholder="Đáp án 1" required>
            <input type="text" class="form-control" name="questions[\${index}][answers][]" placeholder="Đáp án 2" required>
            <input type="text" class="form-control" name="questions[\${index}][answers][]" placeholder="Đáp án 3" required>
            <label>Đáp án đúng: <input type="number" name="questions[\${index}][correct_answer]" min="0" required></label>
        </div>
    `;
    document.getElementById('questions').appendChild(newQuestion);
});
</script>
