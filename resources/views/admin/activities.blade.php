<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Quản Trị Đoàn Trường (ReactJS SPA)</title>
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Quill.js Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- React 18 & ReactDOM -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
    <!-- Babel JSX Compiler -->
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <style>
        body { background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        .sidebar { min-height: 100vh; background: #0f172a; color: #fff; }
        .sidebar .nav-link { color: #94a3b8; padding: 12px 20px; font-weight: 500; border-radius: 8px; margin: 4px 12px; cursor: pointer; transition: all 0.2s; }
        .sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,0.08); }
        .sidebar .nav-link.active { color: #fff; background: #059669; font-weight: bold; }
        .stat-card { border-radius: 12px; border: none; transition: transform 0.2s; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .stat-card:hover { transform: translateY(-2px); }
        .table-card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.04); }
        .badge-react { background: #61dafb; color: #000; font-weight: bold; font-size: 11px; padding: 3px 7px; border-radius: 6px; }
        .ql-editor { min-height: 160px; font-size: 15px; }
        .img-thumb-table { width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; }
        .img-banner-table { width: 100px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

<!-- Root React DOM -->
<div id="react-root"></div>

<!-- ================= ỨNG DỤNG REACTJS CHÍNH ================= -->
@verbatim
<script type="text/babel">
    const { useState, useEffect, useRef } = React;

    // =========================================================================
    // COMPONENT DÙNG CHUNG: NÚT TẢI ẢNH TRỰC TIẾP TỪ MÁY TÍNH
    // =========================================================================
    function ImageUploadInput({ value, onChange, folder = 'general', label = 'Hình ảnh' }) {
        const [uploading, setUploading] = useState(false);
        const fileInputRef = useRef(null);

        const handleFileChange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('folder', folder);

            try {
                setUploading(true);
                const res = await fetch('/api/upload', {
                    method: 'POST',
                    body: formData
                });
                const result = await res.json();
                if (res.ok && result.data?.url) {
                    onChange(result.data.url);
                } else {
                    alert('Lỗi tải ảnh: ' + (result.message || 'Không thể tải ảnh'));
                }
            } catch (err) {
                alert('Lỗi kết nối khi tải ảnh!');
            } finally {
                setUploading(false);
            }
        };

        return (
            <div className="mb-3">
                <label className="form-label fw-bold">{label}</label>
                <div className="input-group">
                    <input
                        type="text"
                        className="form-control"
                        value={value || ''}
                        onChange={(e) => onChange(e.target.value)}
                        placeholder="Dán link ảnh hoặc bấm nút Tải ảnh từ máy ->"
                    />
                    <button
                        type="button"
                        className="btn btn-outline-success fw-bold"
                        onClick={() => fileInputRef.current?.click()}
                        disabled={uploading}
                    >
                        {uploading ? (
                            <>
                                <span className="spinner-border spinner-border-sm me-1"></span> Đang tải...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-cloud-arrow-up-fill me-1"></i> Chọn từ máy tính
                            </>
                        )}
                    </button>
                    <input
                        type="file"
                        ref={fileInputRef}
                        className="d-none"
                        accept="image/png, image/jpeg, image/jpg, image/webp, image/gif, image/svg+xml"
                        onChange={handleFileChange}
                    />
                </div>
                {value && (
                    <div className="mt-2 p-2 bg-light rounded border d-inline-flex align-items-center gap-2">
                        <img
                            src={value}
                            alt="Preview"
                            style={{maxHeight: '75px', maxWidth: '140px', objectFit: 'cover', borderRadius: '4px'}}
                            onError={(e) => { e.target.src = 'https://via.placeholder.com/100x60?text=Anh+Loi'; }}
                        />
                        <div className="small text-muted text-truncate" style={{maxWidth: '220px'}}>{value}</div>
                        <button
                            type="button"
                            className="btn btn-outline-danger btn-sm p-1 py-0"
                            onClick={() => onChange('')}
                            title="Xóa ảnh này"
                        >
                            <i className="bi bi-x"></i>
                        </button>
                    </div>
                )}
            </div>
        );
    }

    // =========================================================================
    // COMPONENT CHÍNH: ADMIN APP
    // =========================================================================
    function AdminApp() {
        const [activeTab, setActiveTab] = useState('activities'); // 'activities' | 'clubs' | 'people' | 'banners' | 'users'
        const [currentUser, setCurrentUser] = useState({
            name: 'Quản Trị Viên Tối Cao',
            email: 'admin@youthunion.edu.vn',
            role: 'admin'
        });

        return (
            <div className="container-fluid">
                <div className="row">
                    {/* SIDEBAR NAVIGATION */}
                    <div className="col-md-3 col-lg-2 px-0 sidebar d-none d-md-block">
                        <div className="p-3 text-center border-bottom border-secondary">
                            <h5 className="fw-bold text-success mb-1">ĐOÀN THANH NIÊN</h5>
                            <span className="badge-react">⚛️ React 18 Dashboard</span>
                        </div>

                        {/* User Profile Mini Card */}
                        <div className="p-3 mx-2 my-2 bg-dark rounded-3 border border-secondary text-center">
                            <div className="fw-bold text-white small">{currentUser.name}</div>
                            <span className="badge bg-success mt-1 text-uppercase">{currentUser.role}</span>
                        </div>

                        <ul className="nav flex-column mt-2">
                            <li className="nav-item">
                                <div 
                                    className={`nav-link ${activeTab === 'activities' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('activities')}
                                >
                                    <i className="bi bi-newspaper me-2"></i> 1. Quản lý Hoạt Động
                                </div>
                            </li>
                            <li className="nav-item">
                                <div 
                                    className={`nav-link ${activeTab === 'clubs' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('clubs')}
                                >
                                    <i className="bi bi-people me-2"></i> 2. Quản lý Câu Lạc Bộ
                                </div>
                            </li>
                            <li className="nav-item">
                                <div 
                                    className={`nav-link ${activeTab === 'people' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('people')}
                                >
                                    <i className="bi bi-star me-2"></i> 3. Gương Mặt Tiêu Biểu
                                </div>
                            </li>
                            <li className="nav-item">
                                <div 
                                    className={`nav-link ${activeTab === 'banners' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('banners')}
                                >
                                    <i className="bi bi-images me-2"></i> 4. Quản lý Banner Slider
                                </div>
                            </li>
                            <li className="nav-item">
                                <div 
                                    className={`nav-link ${activeTab === 'users' ? 'active' : ''}`}
                                    onClick={() => setActiveTab('users')}
                                >
                                    <i className="bi bi-person-gear me-2"></i> 5. Tài Khoản & Phân Quyền
                                </div>
                            </li>
                            <li className="nav-item mt-4 pt-3 border-top border-secondary">
                                <a className="nav-link text-info" href="/hoat-dong" target="_blank">
                                    <i className="bi bi-box-arrow-up-right me-2"></i> Xem Web Sinh Viên
                                </a>
                            </li>
                        </ul>
                    </div>

                    {/* MAIN CONTENT AREA */}
                    <div className="col-md-9 col-lg-10 ms-auto py-4 px-md-4">
                        {activeTab === 'activities' && <ActivitiesManager />}
                        {activeTab === 'clubs' && <ClubsManager />}
                        {activeTab === 'people' && <PeopleManager />}
                        {activeTab === 'banners' && <BannersManager />}
                        {activeTab === 'users' && <UsersManager />}
                    </div>
                </div>
            </div>
        );
    }

    // =========================================================================
    // 1. COMPONENT: QUẢN LÝ HOẠT ĐỘNG (ACTIVITIES)
    // =========================================================================
    function ActivitiesManager() {
        const [activities, setActivities] = useState([]);
        const [loading, setLoading] = useState(true);
        const [modalOpen, setModalOpen] = useState(false);
        const [isEditing, setIsEditing] = useState(false);
        const [currentId, setCurrentId] = useState(null);

        const [title, setTitle] = useState('');
        const [thumbnail, setThumbnail] = useState('');
        const [isActive, setIsActive] = useState(true);

        const quillRef = useRef(null);
        const editorElementRef = useRef(null);

        useEffect(() => {
            fetchActivities();
        }, []);

        useEffect(() => {
            if (modalOpen && editorElementRef.current && !quillRef.current) {
                quillRef.current = new Quill(editorElementRef.current, {
                    theme: 'snow',
                    placeholder: 'Soạn thảo nội dung bài viết...',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['link', 'clean']
                        ]
                    }
                });
            }
        }, [modalOpen]);

        const fetchActivities = async () => {
            try {
                setLoading(true);
                const res = await fetch('/api/activities?all=1');
                const result = await res.json();
                setActivities(result.data.data || result.data || []);
            } catch (err) {
                alert('Lỗi tải danh sách hoạt động!');
            } finally {
                setLoading(false);
            }
        };

        const handleOpenCreate = () => {
            setIsEditing(false);
            setCurrentId(null);
            setTitle('');
            setThumbnail('');
            setIsActive(true);
            if (quillRef.current) quillRef.current.root.innerHTML = '';
            setModalOpen(true);
        };

        const handleOpenEdit = (item) => {
            setIsEditing(true);
            setCurrentId(item.id);
            setTitle(item.title);
            setThumbnail(item.thumbnail || '');
            setIsActive(Boolean(item.is_active));
            setModalOpen(true);
            setTimeout(() => {
                if (quillRef.current) quillRef.current.root.innerHTML = item.content || '';
            }, 100);
        };

        const handleSave = async () => {
            if (!title.trim()) return alert('Vui lòng nhập tiêu đề hoạt động!');
            const content = quillRef.current ? quillRef.current.root.innerHTML : '';
            const url = isEditing ? `/api/activities/${currentId}` : '/api/activities';
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ title: title.trim(), thumbnail: thumbnail.trim(), content, is_active: isActive })
                });
                if (res.ok) {
                    setModalOpen(false);
                    fetchActivities();
                    alert(isEditing ? '✅ Cập nhật bài viết thành công!' : '🎉 Đã thêm bài viết mới!');
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ!');
            }
        };

        const handleDelete = async (id) => {
            if (!confirm(`Xác nhận xóa bài viết #${id}?`)) return;
            try {
                const res = await fetch(`/api/activities/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    fetchActivities();
                    alert('🗑️ Đã xóa bài viết!');
                }
            } catch (e) {
                alert('Lỗi kết nối!');
            }
        };

        return (
            <div>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 className="fw-bold text-dark mb-1">Quản Lý Hoạt Động & Tin Tức</h3>
                        <p className="text-muted mb-0">Quản lý bài viết, có hỗ trợ tải ảnh đại diện từ máy tính</p>
                    </div>
                    <button className="btn btn-success fw-bold px-3 py-2 shadow-sm" onClick={handleOpenCreate}>
                        <i className="bi bi-plus-circle me-1"></i> + Đăng Hoạt Động Mới
                    </button>
                </div>

                <div className="card table-card bg-white p-4">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-bold mb-0">Danh Sách Hoạt Động</h5>
                        <button className="btn btn-outline-secondary btn-sm" onClick={fetchActivities}>
                            <i className="bi bi-arrow-clockwise me-1"></i> Làm mới
                        </button>
                    </div>
                    {loading ? <div className="text-center py-4 text-muted">Đang tải dữ liệu...</div> : (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh Đại Diện</th>
                                        <th>Tiêu Đề</th>
                                        <th>Ngày Đăng</th>
                                        <th>Trạng Thái</th>
                                        <th className="text-end">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {activities.map(a => (
                                        <tr key={a.id}>
                                            <td className="fw-bold text-secondary">#{a.id}</td>
                                            <td>
                                                <img 
                                                    src={a.thumbnail || 'https://via.placeholder.com/55x55?text=Chua+Co+Anh'} 
                                                    alt="" 
                                                    className="img-thumb-table" 
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/55x55?text=Anh+Loi'; }}
                                                />
                                            </td>
                                            <td className="fw-bold text-dark">{a.title}</td>
                                            <td className="small">{a.created_at ? a.created_at.substring(0, 10) : 'Hôm nay'}</td>
                                            <td>
                                                <span className={`badge ${a.is_active ? 'bg-success' : 'bg-secondary'}`}>
                                                    {a.is_active ? 'Hiển thị' : 'Đang ẩn'}
                                                </span>
                                            </td>
                                            <td className="text-end">
                                                <button className="btn btn-outline-warning btn-sm me-1" onClick={() => handleOpenEdit(a)}>
                                                    <i className="bi bi-pencil-square"></i> Sửa
                                                </button>
                                                <button className="btn btn-outline-danger btn-sm" onClick={() => handleDelete(a.id)}>
                                                    <i className="bi bi-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {modalOpen && (
                    <div className="modal show d-block" tabIndex="-1" style={{backgroundColor: "rgba(0,0,0,0.5)"}}>
                        <div className="modal-dialog modal-lg">
                            <div className="modal-content">
                                <div className="modal-header bg-success text-white">
                                    <h5 className="modal-title fw-bold">{isEditing ? `✏️ Sửa Hoạt Động #${currentId}` : '➕ Đăng Hoạt Động Mới'}</h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setModalOpen(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Tiêu đề hoạt động <span className="text-danger">*</span></label>
                                        <input type="text" className="form-control" value={title} onChange={(e) => setTitle(e.target.value)} placeholder="Nhập tiêu đề..." />
                                    </div>

                                    {/* Upload Ảnh Đại Diện Bài Viết */}
                                    <ImageUploadInput
                                        value={thumbnail}
                                        onChange={setThumbnail}
                                        folder="activities"
                                        label="Ảnh đại diện bài viết (Thumbnail)"
                                    />

                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Nội dung chi tiết (Quill Editor)</label>
                                        <div ref={editorElementRef} className="bg-white"></div>
                                    </div>
                                    <div className="form-check form-switch mb-2">
                                        <input className="form-check-input" type="checkbox" id="act_is_active" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} />
                                        <label className="form-check-label fw-bold" htmlFor="act_is_active">Cho phép hiển thị</label>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" onClick={() => setModalOpen(false)}>Hủy</button>
                                    <button type="button" className="btn btn-success fw-bold px-4" onClick={handleSave}>Lưu Bài Viết</button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }

    // =========================================================================
    // 2. COMPONENT: QUẢN LÝ CÂU LẠC BỘ (CLUBS)
    // =========================================================================
    function ClubsManager() {
        const [clubs, setClubs] = useState([]);
        const [categories, setCategories] = useState([]);
        const [loading, setLoading] = useState(true);
        const [modalOpen, setModalOpen] = useState(false);
        const [isEditing, setIsEditing] = useState(false);
        const [currentId, setCurrentId] = useState(null);
        const [activeSubTab, setActiveSubTab] = useState('general');

        // Form states
        const [name, setName] = useState('');
        const [logo, setLogo] = useState('');
        const [imagesList, setImagesList] = useState([]);
        const [newAlbumImage, setNewAlbumImage] = useState('');
        const [categoryId, setCategoryId] = useState(1);
        const [foundedDate, setFoundedDate] = useState('2026-08-26');
        const [description, setDescription] = useState('');

        const [missionsText, setMissionsText] = useState('');
        const [positionsText, setPositionsText] = useState('');
        const [departmentsText, setDepartmentsText] = useState('');
        const [regularActivitiesText, setRegularActivitiesText] = useState('');
        const [achievementsText, setAchievementsText] = useState('');
        const [requirementsText, setRequirementsText] = useState('');
        const [recruitmentText, setRecruitmentText] = useState('');

        useEffect(() => {
            fetchClubs();
            fetchCategories();
        }, []);

        const fetchClubs = async () => {
            try {
                setLoading(true);
                const res = await fetch('/api/clubs');
                const result = await res.json();
                setClubs(result.data || []);
            } catch (err) {
                alert('Lỗi tải danh sách CLB!');
            } finally {
                setLoading(false);
            }
        };

        const fetchCategories = async () => {
            try {
                const res = await fetch('/api/club-categories');
                const result = await res.json();
                setCategories(result.data || []);
            } catch (e) {}
        };

        const handleOpenCreate = () => {
            setIsEditing(false);
            setCurrentId(null);
            setName('');
            setLogo('');
            setImagesList([]);
            setNewAlbumImage('');
            setCategoryId(categories[0]?.id || 1);
            setFoundedDate(new Date().toISOString().substring(0, 10));
            setDescription('');

            setMissionsText('');
            setPositionsText('');
            setDepartmentsText('');
            setRegularActivitiesText('');
            setAchievementsText('');
            setRequirementsText('');
            setRecruitmentText('');

            setActiveSubTab('general');
            setModalOpen(true);
        };

        const handleOpenEdit = (c) => {
            setIsEditing(true);
            setCurrentId(c.id);
            setName(c.name || '');
            setLogo(c.logo || '');
            setImagesList(Array.isArray(c.images) ? c.images : []);
            setNewAlbumImage('');
            setCategoryId(c.category_id || 1);
            setFoundedDate(c.founded_date ? c.founded_date.substring(0, 10) : '');
            setDescription(c.description || '');

            setMissionsText(Array.isArray(c.missions) ? c.missions.join('\n') : '');
            setPositionsText(Array.isArray(c.management_structure?.positions) ? c.management_structure.positions.join('\n') : '');
            setDepartmentsText(Array.isArray(c.management_structure?.departments) ? c.management_structure.departments.join('\n') : '');
            setRegularActivitiesText(Array.isArray(c.regular_activities) ? c.regular_activities.join('\n') : '');
            setAchievementsText(Array.isArray(c.achievements) ? c.achievements.join('\n') : '');
            setRequirementsText(Array.isArray(c.membership_requirements) ? c.membership_requirements.join('\n') : '');
            setRecruitmentText(Array.isArray(c.recruitment_process) ? c.recruitment_process.join('\n') : '');

            setActiveSubTab('general');
            setModalOpen(true);
        };

        const handleAddImageToAlbum = (url) => {
            if (!url) return;
            if (!imagesList.includes(url)) {
                setImagesList([...imagesList, url]);
            }
            setNewAlbumImage('');
        };

        const handleRemoveImageFromAlbum = (index) => {
            const updated = [...imagesList];
            updated.splice(index, 1);
            setImagesList(updated);
        };

        const parseLines = (text) => {
            if (!text) return [];
            return text.split('\n').map(s => s.trim()).filter(Boolean);
        };

        const handleSave = async () => {
            if (!name.trim()) return alert('Vui lòng nhập tên Câu lạc bộ!');
            const url = isEditing ? `/api/clubs/${currentId}` : '/api/clubs';
            const method = isEditing ? 'PUT' : 'POST';

            const payload = {
                name: name.trim(),
                logo: logo.trim(),
                images: imagesList,
                category_id: Number(categoryId),
                founded_date: foundedDate,
                description: description.trim(),
                missions: parseLines(missionsText),
                management_structure: {
                    positions: parseLines(positionsText),
                    departments: parseLines(departmentsText)
                },
                regular_activities: parseLines(regularActivitiesText),
                achievements: parseLines(achievementsText),
                membership_requirements: parseLines(requirementsText),
                recruitment_process: parseLines(recruitmentText)
            };

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok) {
                    setModalOpen(false);
                    fetchClubs();
                    alert(isEditing ? '✅ Cập nhật toàn bộ thông tin CLB thành công!' : '🎉 Đã thêm CLB mới thành công!');
                } else {
                    alert('Lỗi: ' + (data.message || 'Không thể lưu'));
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ!');
            }
        };

        const handleDelete = async (id) => {
            if (!confirm(`Xác nhận xóa CLB #${id}?`)) return;
            try {
                const res = await fetch(`/api/clubs/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    fetchClubs();
                    alert('🗑️ Đã xóa Câu lạc bộ!');
                }
            } catch (e) {
                alert('Lỗi kết nối!');
            }
        };

        return (
            <div>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 className="fw-bold text-dark mb-1">Quản Lý Câu Lạc Bộ</h3>
                        <p className="text-muted mb-0">Quản lý chi tiết: Tôn chỉ, Chức năng, Cơ cấu ban chủ nhiệm, Album ảnh và Quy trình tuyển sinh</p>
                    </div>
                    <button className="btn btn-success fw-bold px-3 py-2 shadow-sm" onClick={handleOpenCreate}>
                        <i className="bi bi-plus-circle me-1"></i> + Thêm Câu Lạc Bộ Mới
                    </button>
                </div>

                <div className="card table-card bg-white p-4">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-bold mb-0">Danh Sách Câu Lạc Bộ ({clubs.length})</h5>
                        <button className="btn btn-outline-secondary btn-sm" onClick={fetchClubs}>
                            <i className="bi bi-arrow-clockwise me-1"></i> Làm mới
                        </button>
                    </div>
                    {loading ? <div className="text-center py-4 text-muted">Đang tải dữ liệu CLB...</div> : (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Logo</th>
                                        <th>Tên Câu Lạc Bộ</th>
                                        <th>Thể Loại</th>
                                        <th>Album Ảnh</th>
                                        <th>Chức Năng & Ban CN</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {clubs.map(c => (
                                        <tr key={c.id}>
                                            <td className="fw-bold text-secondary">#{c.id}</td>
                                            <td>
                                                <img 
                                                    src={c.logo || 'https://via.placeholder.com/55x55?text=Logo'} 
                                                    alt="" 
                                                    className="img-thumb-table" 
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/55x55?text=Logo'; }}
                                                />
                                            </td>
                                            <td>
                                                <div className="fw-bold text-dark">{c.name}</div>
                                                <small className="text-muted">TL: {c.founded_date ? c.founded_date.substring(0, 10) : '-'}</small>
                                            </td>
                                            <td><span className="badge bg-info text-dark">{c.category?.name || 'Chưa phân loại'}</span></td>
                                            <td>
                                                <span className="badge bg-secondary">
                                                    📸 {Array.isArray(c.images) ? c.images.length : 0} ảnh
                                                </span>
                                            </td>
                                            <td className="small">
                                                <span className="badge bg-light text-dark border me-1">
                                                    {Array.isArray(c.missions) ? c.missions.length : 0} nhiệm vụ
                                                </span>
                                                <span className="badge bg-light text-dark border">
                                                    {c.management_structure?.positions?.length || 0} vị trí
                                                </span>
                                            </td>
                                            <td>
                                                <button className="btn btn-outline-warning btn-sm me-1" onClick={() => handleOpenEdit(c)}>
                                                    <i className="bi bi-pencil-square"></i> Sửa chi tiết
                                                </button>
                                                <button className="btn btn-outline-danger btn-sm" onClick={() => handleDelete(c.id)}>
                                                    <i className="bi bi-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {modalOpen && (
                    <div className="modal show d-block" tabIndex="-1" style={{backgroundColor: "rgba(0,0,0,0.5)"}}>
                        <div className="modal-dialog modal-xl modal-dialog-scrollable">
                            <div className="modal-content">
                                <div className="modal-header bg-success text-white">
                                    <h5 className="modal-title fw-bold">
                                        {isEditing ? `✏️ Chỉnh Sửa Chi Tiết CLB: ${name}` : '➕ Thêm Câu Lạc Bộ Mới'}
                                    </h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setModalOpen(false)}></button>
                                </div>
                                <div className="modal-body p-4">
                                    {/* Sub Navigation Tabs */}
                                    <ul className="nav nav-pills nav-fill mb-4 p-1 bg-light rounded border">
                                        <li className="nav-item">
                                            <button 
                                                className={`nav-link fw-bold ${activeSubTab === 'general' ? 'active bg-success' : 'text-dark'}`}
                                                onClick={() => setActiveSubTab('general')}
                                                type="button"
                                            >
                                                <i className="bi bi-info-circle me-1"></i> 1. Thông tin chung & Logo
                                            </button>
                                        </li>
                                        <li className="nav-item">
                                            <button 
                                                className={`nav-link fw-bold ${activeSubTab === 'missions' ? 'active bg-success' : 'text-dark'}`}
                                                onClick={() => setActiveSubTab('missions')}
                                                type="button"
                                            >
                                                <i className="bi bi-shield-check me-1"></i> 2. Nhiệm vụ & Cơ cấu
                                            </button>
                                        </li>
                                        <li className="nav-item">
                                            <button 
                                                className={`nav-link fw-bold ${activeSubTab === 'activities' ? 'active bg-success' : 'text-dark'}`}
                                                onClick={() => setActiveSubTab('activities')}
                                                type="button"
                                            >
                                                <i className="bi bi-calendar-check me-1"></i> 3. Hoạt động & Thành tích
                                            </button>
                                        </li>
                                        <li className="nav-item">
                                            <button 
                                                className={`nav-link fw-bold ${activeSubTab === 'recruitment' ? 'active bg-success' : 'text-dark'}`}
                                                onClick={() => setActiveSubTab('recruitment')}
                                                type="button"
                                            >
                                                <i className="bi bi-person-plus me-1"></i> 4. Tuyển thành viên
                                            </button>
                                        </li>
                                        <li className="nav-item">
                                            <button 
                                                className={`nav-link fw-bold ${activeSubTab === 'album' ? 'active bg-success' : 'text-dark'}`}
                                                onClick={() => setActiveSubTab('album')}
                                                type="button"
                                            >
                                                <i className="bi bi-images me-1"></i> 5. Album Khoảnh khắc ({imagesList.length})
                                            </button>
                                        </li>
                                    </ul>

                                    {/* TAB 1: THÔNG TIN CHUNG & LOGO */}
                                    {activeSubTab === 'general' && (
                                        <div>
                                            <div className="mb-3">
                                                <label className="form-label fw-bold">Tên Câu Lạc Bộ <span className="text-danger">*</span></label>
                                                <input type="text" className="form-control form-control-lg" value={name} onChange={(e) => setName(e.target.value)} placeholder="Ví dụ: CLB Karate PPA..." />
                                            </div>

                                            <div className="row g-3 mb-3">
                                                <div className="col-md-6">
                                                    <label className="form-label fw-bold">Thể loại CLB</label>
                                                    <select className="form-select" value={categoryId} onChange={(e) => setCategoryId(e.target.value)}>
                                                        {categories.map(cat => (
                                                            <option key={cat.id} value={cat.id}>{cat.name}</option>
                                                        ))}
                                                    </select>
                                                </div>
                                                <div className="col-md-6">
                                                    <label className="form-label fw-bold">Ngày thành lập</label>
                                                    <input type="date" className="form-control" value={foundedDate} onChange={(e) => setFoundedDate(e.target.value)} />
                                                </div>
                                            </div>

                                            <div className="mb-3">
                                                <label className="form-label fw-bold">Tôn chỉ, Mục đích (Giới thiệu tóm tắt)</label>
                                                <textarea className="form-control" rows="3" value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Giới thiệu sứ mệnh, tôn chỉ của CLB..."></textarea>
                                            </div>

                                            <ImageUploadInput
                                                value={logo}
                                                onChange={setLogo}
                                                folder="clubs"
                                                label="Ảnh Thumbnail / Logo đại diện chính"
                                            />
                                        </div>
                                    )}

                                    {/* TAB 2: NHIỆM VỤ & CƠ CẤU */}
                                    {activeSubTab === 'missions' && (
                                        <div>
                                            <div className="mb-4">
                                                <label className="form-label fw-bold text-success">
                                                    <i className="bi bi-card-checklist me-1"></i> Chức năng, Nhiệm vụ (Mỗi dòng một nhiệm vụ)
                                                </label>
                                                <textarea 
                                                    className="form-control" 
                                                    rows="4" 
                                                    value={missionsText} 
                                                    onChange={(e) => setMissionsText(e.target.value)}
                                                    placeholder="Dòng 1: Rèn luyện thể lực và phẩm chất đạo đức người chiến sĩ CAND&#10;Dòng 2: Bồi dưỡng lực lượng tham gia thi đấu các giải thể thao toàn quốc&#10;Dòng 3: ..."
                                                ></textarea>
                                                <small className="text-muted">Nhấn Enter để xuống dòng cho mỗi nhiệm vụ mới.</small>
                                            </div>

                                            <div className="row g-3">
                                                <div className="col-md-6">
                                                    <label className="form-label fw-bold text-primary">
                                                        <i className="bi bi-people-fill me-1"></i> Cơ cấu Ban Chủ nhiệm - Vị trí
                                                    </label>
                                                    <textarea 
                                                        className="form-control" 
                                                        rows="4" 
                                                        value={positionsText} 
                                                        onChange={(e) => setPositionsText(e.target.value)}
                                                        placeholder="Chủ nhiệm: Đ/c Lê Tuấn Anh (Khóa D47)&#10;Phó Chủ nhiệm Huấn luyện: Đ/c Hoàng Long&#10;Phó Chủ nhiệm Phong trào: Đ/c Trần Minh Đức"
                                                    ></textarea>
                                                    <small className="text-muted">Mỗi dòng là một chức danh / nhân sự.</small>
                                                </div>

                                                <div className="col-md-6">
                                                    <label className="form-label fw-bold text-primary">
                                                        <i className="bi bi-diagram-3 me-1"></i> Các Ban Chuyên Môn
                                                    </label>
                                                    <textarea 
                                                        className="form-control" 
                                                        rows="4" 
                                                        value={departmentsText} 
                                                        onChange={(e) => setDepartmentsText(e.target.value)}
                                                        placeholder="Ban Huấn luyện & Chuyên môn&#10;Ban Phong trào & Sự kiện&#10;Ban Truyền thông & Kỷ luật"
                                                    ></textarea>
                                                    <small className="text-muted">Mỗi dòng là một ban chuyên môn trực thuộc.</small>
                                                </div>
                                            </div>
                                        </div>
                                    )}

                                    {/* TAB 3: HOẠT ĐỘNG & THÀNH TÍCH */}
                                    {activeSubTab === 'activities' && (
                                        <div>
                                            <div className="mb-4">
                                                <label className="form-label fw-bold text-primary">
                                                    <i className="bi bi-calendar-event me-1"></i> Hoạt động thường xuyên (Lịch sinh hoạt/tập luyện)
                                                </label>
                                                <textarea 
                                                    className="form-control" 
                                                    rows="4" 
                                                    value={regularActivitiesText} 
                                                    onChange={(e) => setRegularActivitiesText(e.target.value)}
                                                    placeholder="Tập luyện chuyên môn 3 buổi/tuần (Thứ 2, Thứ 4, Thứ 6) tại Nhà thi đấu Học viện&#10;Tập huấn kỹ năng tự vệ định kỳ cho học viên mới&#10;Tham gia biểu diễn võ thuật trong các chương trình lễ hội của Học viện"
                                                ></textarea>
                                                <small className="text-muted">Nhấn Enter xuống dòng để nhập từng hoạt động.</small>
                                            </div>

                                            <div>
                                                <label className="form-label fw-bold text-warning text-dark">
                                                    <i className="bi bi-trophy-fill text-warning me-1"></i> Thành tích nổi bật (Huy chương, Bằng khen)
                                                </label>
                                                <textarea 
                                                    className="form-control" 
                                                    rows="4" 
                                                    value={achievementsText} 
                                                    onChange={(e) => setAchievementsText(e.target.value)}
                                                    placeholder="Giải Nhất toàn đoàn Hội thao thanh niên CAND năm 2025&#10;03 Huy chương Vàng Giải Vô địch Sinh viên toàn quốc&#10;Giấy khen của Giám đốc Học viện CSND"
                                                ></textarea>
                                                <small className="text-muted">Nhấn Enter xuống dòng để nhập từng thành tích.</small>
                                            </div>
                                        </div>
                                    )}

                                    {/* TAB 4: TUYỂN THÀNH VIÊN */}
                                    {activeSubTab === 'recruitment' && (
                                        <div>
                                            <div className="mb-4">
                                                <label className="form-label fw-bold text-success">
                                                    <i className="bi bi-check2-circle me-1"></i> Điều kiện tham gia
                                                </label>
                                                <textarea 
                                                    className="form-control" 
                                                    rows="4" 
                                                    value={requirementsText} 
                                                    onChange={(e) => setRequirementsText(e.target.value)}
                                                    placeholder="Đoàn viên, học viên hệ chính quy đang học tập tại Học viện CSND&#10;Có đam mê, sức khỏe tốt và tinh thần kỷ luật cao&#10;Cam kết tham gia tập luyện đều đặn theo lịch CLB"
                                                ></textarea>
                                                <small className="text-muted">Mỗi dòng là một tiêu chuẩn tham gia.</small>
                                            </div>

                                            <div>
                                                <label className="form-label fw-bold text-info text-dark">
                                                    <i className="bi bi-list-ol text-info me-1"></i> Quy trình tuyển thành viên
                                                </label>
                                                <textarea 
                                                    className="form-control" 
                                                    rows="4" 
                                                    value={recruitmentText} 
                                                    onChange={(e) => setRecruitmentText(e.target.value)}
                                                    placeholder="Bước 1: Đăng ký đơn online qua cổng thông tin Đoàn trường&#10;Bước 2: Kiểm tra thể lực và phỏng vấn trực tiếp&#10;Bước 3: Thử thách tập luyện 02 tuần cùng Ban Huấn luyện&#10;Bước 4: Chính thức kết nạp hội viên"
                                                ></textarea>
                                                <small className="text-muted">Mỗi dòng là một bước trong quy trình tuyển chọn.</small>
                                            </div>
                                        </div>
                                    )}

                                    {/* TAB 5: ALBUM KHOẢNH KHẮC */}
                                    {activeSubTab === 'album' && (
                                        <div>
                                            <div className="d-flex justify-content-between align-items-center mb-3">
                                                <h6 className="fw-bold mb-0">Danh sách ảnh trong Album ({imagesList.length} ảnh)</h6>
                                                <span className="badge bg-info text-dark">Đã nạp sẵn ảnh thật</span>
                                            </div>

                                            {/* Thêm ảnh mới vào album */}
                                            <div className="p-3 bg-light rounded border mb-4">
                                                <h6 className="fw-bold text-success mb-2">➕ Tải thêm ảnh mới vào Album:</h6>
                                                <div className="row g-2 align-items-end">
                                                    <div className="col-md-9">
                                                        <ImageUploadInput
                                                            value={newAlbumImage}
                                                            onChange={(url) => {
                                                                setNewAlbumImage(url);
                                                                handleAddImageToAlbum(url);
                                                            }}
                                                            folder="clubs"
                                                            label="Chọn ảnh từ máy tính để thêm vào Album"
                                                        />
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Lưới hiển thị các ảnh trong Album */}
                                            {imagesList.length === 0 ? (
                                                <div className="text-center py-4 text-muted bg-white border rounded">Chưa có ảnh nào trong album này.</div>
                                            ) : (
                                                <div className="row g-3">
                                                    {imagesList.map((imgUrl, idx) => (
                                                        <div key={idx} className="col-6 col-md-3 col-lg-2">
                                                            <div className="card h-100 shadow-sm position-relative border">
                                                                <img 
                                                                    src={imgUrl} 
                                                                    alt="" 
                                                                    className="card-img-top" 
                                                                    style={{height: '110px', objectFit: 'cover'}}
                                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/150x110?text=Anh+Loi'; }}
                                                                />
                                                                <div className="p-2 text-center bg-white">
                                                                    <small className="text-muted d-block text-truncate" title={imgUrl}>#{idx + 1}</small>
                                                                    <button 
                                                                        type="button" 
                                                                        className="btn btn-outline-danger btn-sm p-1 py-0 mt-1"
                                                                        onClick={() => handleRemoveImageFromAlbum(idx)}
                                                                        title="Xóa ảnh này khỏi Album"
                                                                    >
                                                                        <i className="bi bi-trash"></i> Xóa
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </div>
                                <div className="modal-footer bg-light">
                                    <button type="button" className="btn btn-secondary" onClick={() => setModalOpen(false)}>Hủy</button>
                                    <button type="button" className="btn btn-success fw-bold px-4 shadow" onClick={handleSave}>
                                        <i className="bi bi-save me-1"></i> Lưu Toàn Bộ Thông Tin CLB
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }

    // =========================================================================
    // 3. COMPONENT: QUẢN LÝ GƯƠNG MẶT TIÊU BIỂU (PEOPLE)
    // =========================================================================
    function PeopleManager() {
        const [people, setPeople] = useState([]);
        const [loading, setLoading] = useState(true);
        const [modalOpen, setModalOpen] = useState(false);
        const [isEditing, setIsEditing] = useState(false);
        const [currentId, setCurrentId] = useState(null);

        const [name, setName] = useState('');
        const [avatar, setAvatar] = useState('');
        const [roleGroup, setRoleGroup] = useState('DOAN_VIEN');
        const [classUnit, setClassUnit] = useState('');
        const [achievement, setAchievement] = useState('');
        const [isActive, setIsActive] = useState(true);

        useEffect(() => {
            fetchPeople();
        }, []);

        const fetchPeople = async () => {
            try {
                setLoading(true);
                const res = await fetch('/api/outstanding-people?all=1');
                const result = await res.json();
                setPeople(result.data || []);
            } catch (err) {
                alert('Lỗi tải danh sách gương mặt tiêu biểu!');
            } finally {
                setLoading(false);
            }
        };

        const handleOpenCreate = () => {
            setIsEditing(false);
            setCurrentId(null);
            setName('');
            setAvatar('');
            setRoleGroup('DOAN_VIEN');
            setClassUnit('');
            setAchievement('');
            setIsActive(true);
            setModalOpen(true);
        };

        const handleOpenEdit = (p) => {
            setIsEditing(true);
            setCurrentId(p.id);
            setName(p.name);
            setAvatar(p.avatar || '');
            setRoleGroup(p.role_group || 'DOAN_VIEN');
            setClassUnit(p.class_unit || '');
            setAchievement(p.achievement || '');
            setIsActive(Boolean(p.is_active));
            setModalOpen(true);
        };

        const handleSave = async () => {
            if (!name.trim()) return alert('Vui lòng nhập họ và tên!');
            const url = isEditing ? `/api/outstanding-people/${currentId}` : '/api/outstanding-people';
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        name: name.trim(),
                        avatar: avatar.trim(),
                        role_group: roleGroup,
                        class_unit: classUnit.trim(),
                        achievement: achievement.trim(),
                        is_active: isActive
                    })
                });

                const data = await res.json();
                if (res.ok) {
                    setModalOpen(false);
                    fetchPeople();
                    alert(isEditing ? '✅ Cập nhật thông tin thành công!' : '🎉 Đã thêm gương mặt mới!');
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ!');
            }
        };

        const handleDelete = async (id) => {
            if (!confirm(`Xác nhận xóa gương mặt #${id}?`)) return;
            try {
                const res = await fetch(`/api/outstanding-people/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    fetchPeople();
                    alert('🗑️ Đã xóa gương mặt tiêu biểu!');
                }
            } catch (e) {
                alert('Lỗi kết nối!');
            }
        };

        const getRoleBadge = (role) => {
            switch(role) {
                case 'BGD': return <span className="badge bg-danger">BGD / Đảng Ủy</span>;
                case 'BI_THU_DOAN': return <span className="badge bg-primary">Bí Thư Đoàn</span>;
                case 'DOAN_VIEN': return <span className="badge bg-success">Đoàn Viên Xuất Sắc</span>;
                default: return <span className="badge bg-secondary">{role}</span>;
            }
        };

        return (
            <div>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 className="fw-bold text-dark mb-1">Quản Lý Gương Mặt Tiêu Biểu</h3>
                        <p className="text-muted mb-0">Tuyên dương cán bộ Đoàn, sinh viên xuất sắc kèm Ảnh chân dung</p>
                    </div>
                    <button className="btn btn-success fw-bold px-3 py-2 shadow-sm" onClick={handleOpenCreate}>
                        <i className="bi bi-plus-circle me-1"></i> + Tuyên Dương Gương Mặt Mới
                    </button>
                </div>

                <div className="card table-card bg-white p-4">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-bold mb-0">Danh Sách Tuyên Dương</h5>
                        <button className="btn btn-outline-secondary btn-sm" onClick={fetchPeople}>
                            <i className="bi bi-arrow-clockwise me-1"></i> Làm mới
                        </button>
                    </div>
                    {loading ? <div className="text-center py-4 text-muted">Đang tải dữ liệu...</div> : (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh Chân Dung</th>
                                        <th>Họ Và Tên</th>
                                        <th>Danh Hiệu / Nhóm</th>
                                        <th>Chi Đoàn / Đơn Vị</th>
                                        <th>Thành Tích Nổi Bật</th>
                                        <th>Trạng Thái</th>
                                        <th className="text-end">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {people.map(p => (
                                        <tr key={p.id}>
                                            <td className="fw-bold text-secondary">#{p.id}</td>
                                            <td>
                                                <img 
                                                    src={p.avatar || 'https://via.placeholder.com/55x55?text=Avatar'} 
                                                    alt="" 
                                                    className="img-thumb-table rounded-circle" 
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/55x55?text=Avatar'; }}
                                                />
                                            </td>
                                            <td className="fw-bold text-dark">{p.name}</td>
                                            <td>{getRoleBadge(p.role_group)}</td>
                                            <td className="small text-muted">{p.class_unit || '-'}</td>
                                            <td className="small" style={{maxWidth: "260px"}}>{p.achievement || '-'}</td>
                                            <td><span className={`badge ${p.is_active ? 'bg-success' : 'bg-secondary'}`}>{p.is_active ? 'Hiển thị' : 'Ẩn'}</span></td>
                                            <td className="text-end">
                                                <button className="btn btn-outline-warning btn-sm me-1" onClick={() => handleOpenEdit(p)}>
                                                    <i className="bi bi-pencil-square"></i> Sửa
                                                </button>
                                                <button className="btn btn-outline-danger btn-sm" onClick={() => handleDelete(p.id)}>
                                                    <i className="bi bi-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {modalOpen && (
                    <div className="modal show d-block" tabIndex="-1" style={{backgroundColor: "rgba(0,0,0,0.5)"}}>
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <div className="modal-header bg-success text-white">
                                    <h5 className="modal-title fw-bold">{isEditing ? `✏️ Sửa Thông Tin #${currentId}` : '➕ Tuyên Dương Gương Mặt Mới'}</h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setModalOpen(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Họ và Tên <span className="text-danger">*</span></label>
                                        <input type="text" className="form-control" value={name} onChange={(e) => setName(e.target.value)} placeholder="Nhập họ và tên..." />
                                    </div>

                                    {/* Upload Ảnh Chân Dung */}
                                    <ImageUploadInput
                                        value={avatar}
                                        onChange={setAvatar}
                                        folder="people"
                                        label="Ảnh chân dung sinh viên (Avatar)"
                                    />

                                    <div className="row g-2 mb-3">
                                        <div className="col-6">
                                            <label className="form-label fw-bold">Danh hiệu / Nhóm</label>
                                            <select className="form-select" value={roleGroup} onChange={(e) => setRoleGroup(e.target.value)}>
                                                <option value="DOAN_VIEN">Đoàn Viên Xuất Sắc</option>
                                                <option value="BI_THU_DOAN">Bí Thư Chi Đoàn</option>
                                                <option value="BGD">Ban Giám Đốc / Đảng Ủy</option>
                                            </select>
                                        </div>
                                        <div className="col-6">
                                            <label className="form-label fw-bold">Chi đoàn / Lớp</label>
                                            <input type="text" className="form-control" value={classUnit} onChange={(e) => setClassUnit(e.target.value)} placeholder="VD: Chi đoàn K15 CNTT" />
                                        </div>
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Thành tích nổi bật</label>
                                        <textarea className="form-control" rows="3" value={achievement} onChange={(e) => setAchievement(e.target.value)} placeholder="Sinh viên 5 tốt..."></textarea>
                                    </div>
                                    <div className="form-check form-switch mb-2">
                                        <input className="form-check-input" type="checkbox" id="person_is_active" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} />
                                        <label className="form-check-label fw-bold" htmlFor="person_is_active">Cho phép hiển thị</label>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" onClick={() => setModalOpen(false)}>Hủy</button>
                                    <button type="button" className="btn btn-success fw-bold px-4" onClick={handleSave}>Lưu Thông Tin</button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }

    // =========================================================================
    // 4. COMPONENT: QUẢN LÝ BANNER SLIDER (BANNERS)
    // =========================================================================
    function BannersManager() {
        const [banners, setBanners] = useState([]);
        const [loading, setLoading] = useState(true);
        const [modalOpen, setModalOpen] = useState(false);
        const [isEditing, setIsEditing] = useState(false);
        const [currentId, setCurrentId] = useState(null);

        const [title, setTitle] = useState('');
        const [imageUrl, setImageUrl] = useState('');
        const [linkUrl, setLinkUrl] = useState('');
        const [order, setOrder] = useState(1);
        const [isActive, setIsActive] = useState(true);

        useEffect(() => {
            fetchBanners();
        }, []);

        const fetchBanners = async () => {
            try {
                setLoading(true);
                const res = await fetch('/api/banners?all=1');
                const result = await res.json();
                setBanners(result.data || []);
            } catch (err) {
                alert('Lỗi tải danh sách Banner!');
            } finally {
                setLoading(false);
            }
        };

        const handleOpenCreate = () => {
            setIsEditing(false);
            setCurrentId(null);
            setTitle('');
            setImageUrl('');
            setLinkUrl('/hoat-dong');
            setOrder(banners.length + 1);
            setIsActive(true);
            setModalOpen(true);
        };

        const handleOpenEdit = (b) => {
            setIsEditing(true);
            setCurrentId(b.id);
            setTitle(b.title);
            setImageUrl(b.image_url);
            setLinkUrl(b.link_url || '');
            setOrder(b.order || 0);
            setIsActive(Boolean(b.is_active));
            setModalOpen(true);
        };

        const handleSave = async () => {
            if (!title.trim()) return alert('Vui lòng nhập tiêu đề banner!');
            if (!imageUrl.trim()) return alert('Vui lòng chọn hoặc dán link ảnh banner!');

            const url = isEditing ? `/api/banners/${currentId}` : '/api/banners';
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        title: title.trim(),
                        image_url: imageUrl.trim(),
                        link_url: linkUrl.trim(),
                        order: parseInt(order) || 0,
                        is_active: isActive
                    })
                });

                const data = await res.json();
                if (res.ok) {
                    setModalOpen(false);
                    fetchBanners();
                    alert(isEditing ? '✅ Cập nhật banner thành công!' : '🎉 Đã thêm banner mới!');
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ!');
            }
        };

        const handleDelete = async (id) => {
            if (!confirm(`Xác nhận xóa Banner #${id}?`)) return;
            try {
                const res = await fetch(`/api/banners/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    fetchBanners();
                    alert('🗑️ Đã xóa Banner thành công!');
                }
            } catch (e) {
                alert('Lỗi kết nối!');
            }
        };

        return (
            <div>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 className="fw-bold text-dark mb-1">Quản Lý Banner Slider</h3>
                        <p className="text-muted mb-0">Hỗ trợ tải ảnh banner độ phân giải cao trực tiếp từ máy tính</p>
                    </div>
                    <button className="btn btn-success fw-bold px-3 py-2 shadow-sm" onClick={handleOpenCreate}>
                        <i className="bi bi-plus-circle me-1"></i> + Thêm Banner Mới
                    </button>
                </div>

                <div className="card table-card bg-white p-4">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-bold mb-0">Danh Sách Banner Slider</h5>
                        <button className="btn btn-outline-secondary btn-sm" onClick={fetchBanners}>
                            <i className="bi bi-arrow-clockwise me-1"></i> Làm mới
                        </button>
                    </div>
                    {loading ? <div className="text-center py-4 text-muted">Đang tải dữ liệu Banner...</div> : (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh Banner</th>
                                        <th>Tiêu Đề Banner</th>
                                        <th>Link Chuyển Hướng</th>
                                        <th>Thứ Tự</th>
                                        <th>Trạng Thái</th>
                                        <th className="text-end">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {banners.map(b => (
                                        <tr key={b.id}>
                                            <td className="fw-bold text-secondary">#{b.id}</td>
                                            <td>
                                                <img 
                                                    src={b.image_url} 
                                                    alt={b.title} 
                                                    className="img-banner-table" 
                                                    onError={(e) => { e.target.src = 'https://via.placeholder.com/100x50?text=Anh+Loi'; }}
                                                />
                                            </td>
                                            <td className="fw-bold text-dark">{b.title}</td>
                                            <td className="small text-muted">{b.link_url || '-'}</td>
                                            <td><span className="badge bg-light text-dark border">#{b.order}</span></td>
                                            <td><span className={`badge ${b.is_active ? 'bg-success' : 'bg-secondary'}`}>{b.is_active ? 'Hiển thị' : 'Ẩn'}</span></td>
                                            <td className="text-end">
                                                <button className="btn btn-outline-warning btn-sm me-1" onClick={() => handleOpenEdit(b)}>
                                                    <i className="bi bi-pencil-square"></i> Sửa
                                                </button>
                                                <button className="btn btn-outline-danger btn-sm" onClick={() => handleDelete(b.id)}>
                                                    <i className="bi bi-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {modalOpen && (
                    <div className="modal show d-block" tabIndex="-1" style={{backgroundColor: "rgba(0,0,0,0.5)"}}>
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <div className="modal-header bg-success text-white">
                                    <h5 className="modal-title fw-bold">{isEditing ? `✏️ Sửa Banner #${currentId}` : '➕ Thêm Banner Mới'}</h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setModalOpen(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Tên / Tiêu đề Banner <span className="text-danger">*</span></label>
                                        <input type="text" className="form-control" value={title} onChange={(e) => setTitle(e.target.value)} placeholder="VD: Chào đón Tân sinh viên 2026..." />
                                    </div>

                                    {/* Upload Ảnh Banner */}
                                    <ImageUploadInput
                                        value={imageUrl}
                                        onChange={setImageUrl}
                                        folder="banners"
                                        label="Hình ảnh Banner (Slider)"
                                    />

                                    <div className="row g-2 mb-3">
                                        <div className="col-8">
                                            <label className="form-label fw-bold">Link chuyển hướng khi click</label>
                                            <input type="text" className="form-control" value={linkUrl} onChange={(e) => setLinkUrl(e.target.value)} placeholder="VD: /hoat-dong" />
                                        </div>
                                        <div className="col-4">
                                            <label className="form-label fw-bold">Thứ tự (Order)</label>
                                            <input type="number" className="form-control" value={order} onChange={(e) => setOrder(e.target.value)} min="0" />
                                        </div>
                                    </div>
                                    <div className="form-check form-switch mb-2">
                                        <input className="form-check-input" type="checkbox" id="banner_is_active" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} />
                                        <label className="form-check-label fw-bold" htmlFor="banner_is_active">Bật hiển thị trên trang chủ</label>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" onClick={() => setModalOpen(false)}>Hủy</button>
                                    <button type="button" className="btn btn-success fw-bold px-4" onClick={handleSave}>Lưu Banner</button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }

    // =========================================================================
    // 5. COMPONENT: QUẢN LÝ TÀI KHOẢN & PHÂN QUYỀN (USERS & ROLES)
    // =========================================================================
    function UsersManager() {
        const [users, setUsers] = useState([]);
        const [loading, setLoading] = useState(true);
        const [modalOpen, setModalOpen] = useState(false);
        const [isEditing, setIsEditing] = useState(false);
        const [currentId, setCurrentId] = useState(null);

        const [name, setName] = useState('');
        const [email, setEmail] = useState('');
        const [password, setPassword] = useState('');
        const [role, setRole] = useState('editor');
        const [phone, setPhone] = useState('');
        const [isActive, setIsActive] = useState(true);

        useEffect(() => {
            fetchUsers();
        }, []);

        const fetchUsers = async () => {
            try {
                setLoading(true);
                const res = await fetch('/api/users');
                const result = await res.json();
                setUsers(result.data || []);
            } catch (err) {
                alert('Lỗi tải danh sách tài khoản!');
            } finally {
                setLoading(false);
            }
        };

        const handleOpenCreate = () => {
            setIsEditing(false);
            setCurrentId(null);
            setName('');
            setEmail('');
            setPassword('123456');
            setRole('editor');
            setPhone('');
            setIsActive(true);
            setModalOpen(true);
        };

        const handleOpenEdit = (u) => {
            setIsEditing(true);
            setCurrentId(u.id);
            setName(u.name);
            setEmail(u.email);
            setPassword('');
            setRole(u.role || 'editor');
            setPhone(u.phone || '');
            setIsActive(Boolean(u.is_active));
            setModalOpen(true);
        };

        const handleSave = async () => {
            if (!name.trim()) return alert('Vui lòng nhập họ và tên!');
            if (!email.trim()) return alert('Vui lòng nhập email!');
            if (!isEditing && !password.trim()) return alert('Vui lòng nhập mật khẩu khởi tạo!');

            const url = isEditing ? `/api/users/${currentId}` : '/api/users';
            const method = isEditing ? 'PUT' : 'POST';

            const payload = {
                name: name.trim(),
                email: email.trim(),
                role: role,
                phone: phone.trim(),
                is_active: isActive
            };

            if (password.trim()) {
                payload.password = password.trim();
            }

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok) {
                    setModalOpen(false);
                    fetchUsers();
                    alert(isEditing ? '✅ Cập nhật tài khoản thành công!' : '🎉 Tạo tài khoản mới thành công!');
                }
            } catch (e) {
                alert('Lỗi kết nối máy chủ!');
            }
        };

        const handleDelete = async (id) => {
            if (!confirm(`Xác nhận xóa tài khoản #${id}?`)) return;
            try {
                const res = await fetch(`/api/users/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    fetchUsers();
                    alert('🗑️ Đã xóa tài khoản!');
                }
            } catch (e) {
                alert('Lỗi kết nối!');
            }
        };

        const getRoleBadge = (r) => {
            switch(r) {
                case 'admin': return <span className="badge bg-danger"><i className="bi bi-shield-lock-fill me-1"></i> Quản Trị Viên (Admin)</span>;
                case 'editor': return <span className="badge bg-primary"><i className="bi bi-pen-fill me-1"></i> Cán Bộ Đoàn (Editor)</span>;
                case 'student': return <span className="badge bg-secondary"><i className="bi bi-person-fill me-1"></i> Đoàn Viên / Sinh Viên</span>;
                default: return <span className="badge bg-dark">{r}</span>;
            }
        };

        return (
            <div>
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 className="fw-bold text-dark mb-1">Quản Lý Tài Khoản & Phân Quyền</h3>
                        <p className="text-muted mb-0">Quản lý danh sách tài khoản Ban Quản Trị, Cán bộ Đoàn và Sinh viên</p>
                    </div>
                    <button className="btn btn-success fw-bold px-3 py-2 shadow-sm" onClick={handleOpenCreate}>
                        <i className="bi bi-person-plus-fill me-1"></i> + Cấp Tài Khoản Mới
                    </button>
                </div>

                <div className="card table-card bg-white p-4">
                    <div className="d-flex justify-content-between align-items-center mb-3">
                        <h5 className="fw-bold mb-0">Danh Sách Tài Khoản Trong Hệ Thống</h5>
                        <button className="btn btn-outline-secondary btn-sm" onClick={fetchUsers}>
                            <i className="bi bi-arrow-clockwise me-1"></i> Làm mới
                        </button>
                    </div>
                    {loading ? <div className="text-center py-4 text-muted">Đang tải dữ liệu tài khoản...</div> : (
                        <div className="table-responsive">
                            <table className="table table-hover align-middle">
                                <thead className="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Họ Và Tên</th>
                                        <th>Email Đăng Nhập</th>
                                        <th>Vai Trò (Phân Quyền)</th>
                                        <th>Số Điện Thoại</th>
                                        <th>Trạng Thái</th>
                                        <th className="text-end">Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users.map(u => (
                                        <tr key={u.id}>
                                            <td className="fw-bold text-secondary">#{u.id}</td>
                                            <td className="fw-bold text-dark">{u.name}</td>
                                            <td><code>{u.email}</code></td>
                                            <td>{getRoleBadge(u.role)}</td>
                                            <td className="small text-muted">{u.phone || '-'}</td>
                                            <td><span className={`badge ${u.is_active ? 'bg-success' : 'bg-danger'}`}>{u.is_active ? 'Hoạt động' : 'Bị khóa'}</span></td>
                                            <td className="text-end">
                                                <button className="btn btn-outline-warning btn-sm me-1" onClick={() => handleOpenEdit(u)}>
                                                    <i className="bi bi-pencil-square"></i> Sửa
                                                </button>
                                                <button className="btn btn-outline-danger btn-sm" onClick={() => handleDelete(u.id)}>
                                                    <i className="bi bi-trash"></i> Xóa
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {modalOpen && (
                    <div className="modal show d-block" tabIndex="-1" style={{backgroundColor: "rgba(0,0,0,0.5)"}}>
                        <div className="modal-dialog">
                            <div className="modal-content">
                                <div className="modal-header bg-success text-white">
                                    <h5 className="modal-title fw-bold">{isEditing ? `✏️ Sửa Tài Khoản #${currentId}` : '➕ Cấp Tài Khoản Mới'}</h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setModalOpen(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Họ và Tên <span className="text-danger">*</span></label>
                                        <input type="text" className="form-control" value={name} onChange={(e) => setName(e.target.value)} placeholder="VD: Nguyễn Văn A..." />
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">Email Đăng Nhập <span className="text-danger">*</span></label>
                                        <input type="email" className="form-control" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="email@youthunion.edu.vn" />
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label fw-bold">
                                            {isEditing ? 'Mật khẩu mới (Để trống nếu không muốn đổi)' : 'Mật khẩu khởi tạo *'}
                                        </label>
                                        <input type="password" className="form-control" value={password} onChange={(e) => setPassword(e.target.value)} placeholder="Tối thiểu 6 ký tự..." />
                                    </div>
                                    <div className="row g-2 mb-3">
                                        <div className="col-6">
                                            <label className="form-label fw-bold">Vai Trò (Role)</label>
                                            <select className="form-select" value={role} onChange={(e) => setRole(e.target.value)}>
                                                <option value="admin">Quản Trị Viên (Admin)</option>
                                                <option value="editor">Cán Bộ Đoàn (Editor)</option>
                                                <option value="student">Đoàn Viên (Student)</option>
                                            </select>
                                        </div>
                                        <div className="col-6">
                                            <label className="form-label fw-bold">Số Điện Thoại</label>
                                            <input type="text" className="form-control" value={phone} onChange={(e) => setPhone(e.target.value)} placeholder="0901234567" />
                                        </div>
                                    </div>
                                    <div className="form-check form-switch mb-2">
                                        <input className="form-check-input" type="checkbox" id="user_is_active" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} />
                                        <label className="form-check-label fw-bold" htmlFor="user_is_active">Kích hoạt tài khoản</label>
                                    </div>
                                </div>
                                <div className="modal-footer">
                                    <button type="button" className="btn btn-secondary" onClick={() => setModalOpen(false)}>Hủy</button>
                                    <button type="button" className="btn btn-success fw-bold px-4" onClick={handleSave}>Lưu Tài Khoản</button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }

    // Gắn Component chính vào DOM
    const root = ReactDOM.createRoot(document.getElementById('react-root'));
    root.render(<AdminApp />);
</script>
@endverbatim

</body>
</html>
