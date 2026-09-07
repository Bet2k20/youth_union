<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Quản Trị & Kiểm Thử CRUD Từng Mục | Đoàn Thanh Niên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        pre { background: #1e293b; color: #38bdf8; padding: 15px; border-radius: 8px; max-height: 480px; overflow: auto; font-size: 13px; }
        .badge-method { font-size: 11px; font-weight: bold; padding: 4px 8px; border-radius: 6px; }
        .badge-get { background-color: #0284c7; color: white; }
        .badge-post { background-color: #16a34a; color: white; }
        .badge-put { background-color: #d97706; color: white; }
        .badge-delete { background-color: #dc2626; color: white; }
        .nav-pills .nav-link.active { background-color: #059669; }
        .nav-pills .nav-link { color: #334155; font-weight: bold; }
    </style>
</head>
<body class="py-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h2 class="fw-bold text-success mb-1">🛠️ QUẢN TRỊ & KIỂM THỬ CRUD TỪNG MỤC</h2>
            <p class="text-muted mb-0">Thêm, Sửa, Xóa riêng biệt cho từng mục: <strong>Hoạt Động</strong>, <strong>Câu Lạc Bộ</strong>, và <strong>Gương Mặt Tiêu Biểu</strong></p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm" onclick="callApi('GET', '/api/home')">
                <span class="badge-method badge-get me-1">GET</span> Xem Dashboard Tổng Hợp
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Cột điều khiển test CRUD từng mục -->
        <div class="col-lg-6 mb-4">

            <!-- Tab chuyển đổi giữa 3 mục -->
            <ul class="nav nav-pills nav-fill mb-3 bg-white p-2 rounded-3 shadow-sm" id="crudTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-activities" data-bs-toggle="pill" data-bs-target="#panel-activities" type="button">
                        📰 1. Hoạt Động
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-clubs" data-bs-toggle="pill" data-bs-target="#panel-clubs" type="button">
                        🏆 2. Câu Lạc Bộ
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-people" data-bs-toggle="pill" data-bs-target="#panel-people" type="button">
                        ⭐ 3. Gương Mặt Tiêu Biểu
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="crudTabContent">
                
                <!-- ================= MỤC 1: HOẠT ĐỘNG (ACTIVITIES) ================= -->
                <div class="tab-pane fade show active" id="panel-activities">
                    <!-- Read -->
                    <div class="card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Danh sách Hoạt Động</span>
                            <button class="btn btn-outline-primary btn-sm" onclick="callApi('GET', '/api/activities')">
                                <span class="badge-method badge-get me-1">GET</span> Lấy tất cả bài viết
                            </button>
                        </div>
                    </div>

                    <!-- Create -->
                    <div class="card p-3 mb-3 border-success border-2">
                        <h6 class="fw-bold text-success mb-2">➕ [C] Thêm Hoạt Động / Bài Viết Mới</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Tiêu đề bài viết:</label>
                            <input type="text" id="act_title" class="form-control form-control-sm" placeholder="VD: Hội thi cắm hoa 20/10">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Nội dung chi tiết:</label>
                            <textarea id="act_content" class="form-control form-control-sm" rows="2" placeholder="Nội dung hoạt động..."></textarea>
                        </div>
                        <button class="btn btn-success btn-sm fw-bold w-100" onclick="createActivity()">
                            <span class="badge-method badge-post me-1">POST</span> Lưu Bài Viết Hoạt Động Vào Database
                        </button>
                    </div>

                    <!-- Update & Delete -->
                    <div class="card p-3">
                        <h6 class="fw-bold text-warning mb-2">✏️ [U/D] Sửa / Xóa Hoạt Động</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <label class="form-label small fw-bold">ID bài viết:</label>
                                <input type="number" id="act_id" class="form-control form-control-sm" value="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-bold">Tiêu đề mới (khi sửa):</label>
                                <input type="text" id="act_new_title" class="form-control form-control-sm" placeholder="Tiêu đề cần sửa">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning btn-sm flex-fill fw-bold text-dark" onclick="updateActivity()">
                                <span class="badge-method badge-put me-1">PUT</span> Sửa
                            </button>
                            <button class="btn btn-danger btn-sm flex-fill fw-bold" onclick="deleteActivity()">
                                <span class="badge-method badge-delete me-1">DELETE</span> Xóa
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= MỤC 2: CÂU LẠC BỘ (CLUBS) ================= -->
                <div class="tab-pane fade" id="panel-clubs">
                    <!-- Read -->
                    <div class="card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Danh sách Câu Lạc Bộ</span>
                            <button class="btn btn-outline-primary btn-sm" onclick="callApi('GET', '/api/clubs')">
                                <span class="badge-method badge-get me-1">GET</span> Lấy tất cả CLB
                            </button>
                        </div>
                    </div>

                    <!-- Create Club -->
                    <div class="card p-3 mb-3 border-success border-2">
                        <h6 class="fw-bold text-success mb-2">➕ [C] Thêm Câu Lạc Bộ Mới</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Tên Câu Lạc Bộ:</label>
                            <input type="text" id="club_name" class="form-control form-control-sm" placeholder="VD: CLB Nhiếp Ảnh Sinh Viên">
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Thể loại CLB:</label>
                                <select id="club_category_id" class="form-select form-select-sm">
                                    <option value="1">1. Học thuật</option>
                                    <option value="2">2. Văn hóa - Nghệ thuật</option>
                                    <option value="3">3. Thể thao</option>
                                    <option value="4">4. Tình nguyện</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Ngày thành lập:</label>
                                <input type="date" id="club_date" class="form-control form-control-sm" value="2026-08-26">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Mô tả tôn chỉ hoạt động:</label>
                            <textarea id="club_desc" class="form-control form-control-sm" rows="2" placeholder="CLB dành cho các bạn đam mê chụp ảnh và quay phim..."></textarea>
                        </div>
                        <button class="btn btn-success btn-sm fw-bold w-100" onclick="createClub()">
                            <span class="badge-method badge-post me-1">POST</span> Lưu Câu Lạc Bộ Vào Database
                        </button>
                    </div>

                    <!-- Update & Delete Club -->
                    <div class="card p-3">
                        <h6 class="fw-bold text-warning mb-2">✏️ [U/D] Sửa / Xóa Câu Lạc Bộ</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <label class="form-label small fw-bold">ID CLB:</label>
                                <input type="number" id="club_id" class="form-control form-control-sm" value="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-bold">Tên CLB mới (khi sửa):</label>
                                <input type="text" id="club_new_name" class="form-control form-control-sm" placeholder="Tên CLB cần sửa">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning btn-sm flex-fill fw-bold text-dark" onclick="updateClub()">
                                <span class="badge-method badge-put me-1">PUT</span> Sửa CLB
                            </button>
                            <button class="btn btn-danger btn-sm flex-fill fw-bold" onclick="deleteClub()">
                                <span class="badge-method badge-delete me-1">DELETE</span> Xóa CLB
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= MỤC 3: GƯƠNG MẶT TIÊU BIỂU (OUTSTANDING PEOPLE) ================= -->
                <div class="tab-pane fade" id="panel-people">
                    <!-- Read -->
                    <div class="card p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Gương Mặt Tiêu Biểu</span>
                            <button class="btn btn-outline-primary btn-sm" onclick="callApi('GET', '/api/outstanding-people')">
                                <span class="badge-method badge-get me-1">GET</span> Lấy tất cả gương mặt
                            </button>
                        </div>
                    </div>

                    <!-- Create Person -->
                    <div class="card p-3 mb-3 border-success border-2">
                        <h6 class="fw-bold text-success mb-2">➕ [C] Thêm Gương Mặt Tiêu Biểu Mới</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">Họ và Tên:</label>
                                <input type="text" id="person_name" class="form-control form-control-sm" placeholder="VD: Hoàng Minh Châu">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">Nhóm chức vụ / Danh hiệu:</label>
                                <select id="person_role" class="form-select form-select-sm">
                                    <option value="DOAN_VIEN">Đoàn viên tiêu biểu</option>
                                    <option value="BI_THU_DOAN">Bí thư Chi đoàn</option>
                                    <option value="BGD">Ban Giám Đốc / Đảng Ủy</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Chi đoàn / Lớp / Đơn vị:</label>
                            <input type="text" id="person_class" class="form-control form-control-sm" placeholder="VD: Chi đoàn K16 Quản trị Kinh doanh">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Thành tích nổi bật:</label>
                            <textarea id="person_achievement" class="form-control form-control-sm" rows="2" placeholder="Đạt danh hiệu Sinh viên 5 tốt cấp Trường, Thủ khoa đầu ra..."></textarea>
                        </div>
                        <button class="btn btn-success btn-sm fw-bold w-100" onclick="createPerson()">
                            <span class="badge-method badge-post me-1">POST</span> Lưu Gương Mặt Tiêu Biểu Vào Database
                        </button>
                    </div>

                    <!-- Update & Delete Person -->
                    <div class="card p-3">
                        <h6 class="fw-bold text-warning mb-2">✏️ [U/D] Sửa / Xóa Gương Mặt Tiêu Biểu</h6>
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <label class="form-label small fw-bold">ID cá nhân:</label>
                                <input type="number" id="person_id" class="form-control form-control-sm" value="1">
                            </div>
                            <div class="col-8">
                                <label class="form-label small fw-bold">Họ tên mới (khi sửa):</label>
                                <input type="text" id="person_new_name" class="form-control form-control-sm" placeholder="Họ tên cần sửa">
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-warning btn-sm flex-fill fw-bold text-dark" onclick="updatePerson()">
                                <span class="badge-method badge-put me-1">PUT</span> Sửa
                            </button>
                            <button class="btn btn-danger btn-sm flex-fill fw-bold" onclick="deletePerson()">
                                <span class="badge-method badge-delete me-1">DELETE</span> Xóa
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Cột hiển thị kết quả JSON -->
        <div class="col-lg-6">
            <div class="card p-3 sticky-top" style="top: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">📡 Kết Quả Phản Hồi Từ Backend (JSON Response)</h5>
                    <span id="http_status" class="badge bg-secondary">Chưa gửi request</span>
                </div>
                <p class="small text-muted mb-2">Đường link API vừa gọi: <code id="api_url">-</code></p>
                <pre id="json_output">// Chọn một tab bên trái và bấm Thêm / Sửa / Xóa để xem phản hồi JSON tại đây...</pre>
            </div>
        </div>
    </div>
</div>

<!-- Nhúng Bootstrap JS cho Tabs -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    async function callApi(method, endpoint, body = null) {
        const fullUrl = 'http://127.0.0.1:8888' + endpoint;
        document.getElementById('api_url').innerText = method + ' ' + fullUrl;
        document.getElementById('http_status').className = 'badge bg-warning text-dark';
        document.getElementById('http_status').innerText = 'Đang xử lý...';

        try {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            };
            if (body) {
                options.body = JSON.stringify(body);
            }

            const response = await fetch(fullUrl, options);
            const data = await response.json();

            document.getElementById('http_status').className = response.ok ? 'badge bg-success' : 'badge bg-danger';
            document.getElementById('http_status').innerText = 'Mã HTTP: ' + response.status + ' (' + (response.ok ? 'Thành công' : 'Lỗi') + ')';
            document.getElementById('json_output').innerText = JSON.stringify(data, null, 2);
        } catch (err) {
            document.getElementById('http_status').className = 'badge bg-danger';
            document.getElementById('http_status').innerText = 'Lỗi kết nối máy chủ';
            document.getElementById('json_output').innerText = 'Không thể kết nối đến máy chủ Backend.\nHãy đảm bảo bạn đã chạy file chay_web.bat!';
        }
    }

    // --- XỬ LÝ HOẠT ĐỘNG ---
    function createActivity() {
        const title = document.getElementById('act_title').value;
        const content = document.getElementById('act_content').value;
        if (!title) return alert('Vui lòng nhập tiêu đề bài viết!');
        callApi('POST', '/api/activities', { title, content, is_active: true });
    }
    function updateActivity() {
        const id = document.getElementById('act_id').value;
        const title = document.getElementById('act_new_title').value;
        if (!id) return alert('Vui lòng nhập ID!');
        callApi('PUT', '/api/activities/' + id, { title });
    }
    function deleteActivity() {
        const id = document.getElementById('act_id').value;
        if (!id) return alert('Vui lòng nhập ID!');
        if (confirm('Xóa hoạt động ID = ' + id + '?')) callApi('DELETE', '/api/activities/' + id);
    }

    // --- XỬ LÝ CÂU LẠC BỘ ---
    function createClub() {
        const name = document.getElementById('club_name').value;
        const category_id = document.getElementById('club_category_id').value;
        const founded_date = document.getElementById('club_date').value;
        const description = document.getElementById('club_desc').value;
        if (!name) return alert('Vui lòng nhập tên CLB!');
        callApi('POST', '/api/clubs', { name, category_id, founded_date, description });
    }
    function updateClub() {
        const id = document.getElementById('club_id').value;
        const name = document.getElementById('club_new_name').value;
        if (!id) return alert('Vui lòng nhập ID!');
        callApi('PUT', '/api/clubs/' + id, { name });
    }
    function deleteClub() {
        const id = document.getElementById('club_id').value;
        if (!id) return alert('Vui lòng nhập ID!');
        if (confirm('Xóa CLB ID = ' + id + '?')) callApi('DELETE', '/api/clubs/' + id);
    }

    // --- XỬ LÝ GƯƠNG MẶT TIÊU BIỂU ---
    function createPerson() {
        const name = document.getElementById('person_name').value;
        const role_group = document.getElementById('person_role').value;
        const class_unit = document.getElementById('person_class').value;
        const achievement = document.getElementById('person_achievement').value;
        if (!name) return alert('Vui lòng nhập họ tên!');
        callApi('POST', '/api/outstanding-people', { name, role_group, class_unit, achievement, is_active: true });
    }
    function updatePerson() {
        const id = document.getElementById('person_id').value;
        const name = document.getElementById('person_new_name').value;
        if (!id) return alert('Vui lòng nhập ID!');
        callApi('PUT', '/api/outstanding-people/' + id, { name });
    }
    function deletePerson() {
        const id = document.getElementById('person_id').value;
        if (!id) return alert('Vui lòng nhập ID!');
        if (confirm('Xóa gương mặt tiêu biểu ID = ' + id + '?')) callApi('DELETE', '/api/outstanding-people/' + id);
    }
</script>

</body>
</html>
