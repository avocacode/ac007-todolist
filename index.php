<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    <!-- Tambahkan SweetAlert2 CSS dan JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="style.css?<?= time() ?>">
</head>

<body>
    <div class="d-flex justify-content-center flex-column">
        <div class="wrapper mb-1">
            <h1>To-Do List</h1>

            <!-- Formulir Tambah Tugas -->
            <form action="add_task.php" method="POST">
                <input type="text" name="task_name" class="task_name" placeholder="Tugas Baru" required>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> Tambah
                    </button>
                </div>
            </form>

            <!-- Daftar Tugas yang Belum Selesai -->
            <h2>Daftar Tugas</h2>
            <ul id="task-list">
                <?php
                require 'db.php';

                // Ambil data tugas yang belum selesai
                $stmt = $pdo->query("SELECT * FROM tasks WHERE is_completed = 0 ORDER BY position ASC");
                while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li data-id='{$task['id']}' draggable='true'>
                    <a href='complete_task.php?id={$task['id']}&status=1' class='complete'>
                        <div class='box'></div>
                    </a>
                    <div class='w-100 me-1 d-flex justify-content-between'>
                        {$task['task_name']}
                        <a href='#' class='delete' data-id='{$task['id']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-trash'>
                                <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                <path d='M4 7l16 0' />
                                <path d='M10 11l0 6' />
                                <path d='M14 11l0 6' />
                                <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                            </svg>
                        </a>
                    </div>
                </li>";
                }
                ?>
            </ul>

            <!-- Daftar Tugas yang Sudah Selesai -->
            <h2>Tugas Selesai</h2>
            <ul id="completed-task-list">
                <?php
                // Ambil data tugas yang sudah selesai
                $stmt = $pdo->query("SELECT * FROM tasks WHERE is_completed = 1 ORDER BY position ASC");
                while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li data-id='{$task['id']}' class='completed'>
                    <a href='complete_task.php?id={$task['id']}&status=0' class='incomplete'>
                        <div class='box-completed'></div>
                    </a>
                    <div class='w-100 me-1 d-flex justify-content-between'>
                        {$task['task_name']}
                        <a href='#' class='delete' data-id='{$task['id']}'>
                            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='icon icon-tabler icons-tabler-outline icon-tabler-trash'>
                                <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                <path d='M4 7l16 0' />
                                <path d='M10 11l0 6' />
                                <path d='M14 11l0 6' />
                                <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                            </svg>
                        </a>
                    </div>
                  </li>";
                }
                ?>
            </ul>

        </div>

        <div class="footer mb-1">
            Copyright &copy; 2024 <a href="https://avocacode.id" target="_blank">Avoca Code</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

    <!-- JavaScript untuk Drag-and-Drop -->
    <script>
        const taskList = document.getElementById('task-list');

        // Event listener untuk drag-and-drop
        taskList.addEventListener('dragstart', (event) => {
            event.target.classList.add('dragging');
        });

        taskList.addEventListener('dragend', (event) => {
            event.target.classList.remove('dragging');
        });

        taskList.addEventListener('dragover', (event) => {
            event.preventDefault();
            const afterElement = getDragAfterElement(taskList, event.clientY);
            const dragging = document.querySelector('.dragging');
            if (afterElement == null) {
                taskList.appendChild(dragging);
            } else {
                taskList.insertBefore(dragging, afterElement);
            }
        });

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('li:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return {
                        offset: offset,
                        element: child
                    };
                } else {
                    return closest;
                }
            }, {
                offset: Number.NEGATIVE_INFINITY
            }).element;
        }

        // Simpan urutan setelah drag selesai
        taskList.addEventListener('drop', () => {
            const tasks = [...taskList.querySelectorAll('li')];
            const order = tasks.map((task, index) => ({
                id: task.getAttribute('data-id'),
                position: index + 1
            }));
            fetch('update_task.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(order)
            });
        });

        // Menambahkan event listener untuk tombol delete
        document.querySelectorAll('.delete').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah link default untuk mencegah aksi segera

                const taskId = this.getAttribute('data-id'); // Ambil ID tugas yang akan dihapus

                // Konfirmasi penghapusan dengan SweetAlert2
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Tugas ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d84c10',
                    cancelButtonColor: '#0084ff',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika pengguna mengonfirmasi, redirect ke delete_task.php
                        window.location.href = 'delete_task.php?id=' + taskId;
                    }
                });
            });
        });

        // Menampilkan alert success setelah penghapusan
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            Swal.fire({
                title: 'Tugas berhasil dihapus!',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>

</html>