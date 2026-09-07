# 🚀 TÀI LIỆU KẾT NỐI API BACKEND (CHUẨN 100% THEO THIẾT KẾ FIGMA)

> **Dự án:** Sổ tay sinh viên – Tuổi trẻ PPA (Đoàn Thanh niên Học viện CSND)  
> **Link thiết kế Figma:** [Sổ tay sinh viên - T02](https://www.figma.com/design/c8V1pGXCbm4MQJ5EPbNqJf/S%E1%BB%95-tay-sinh-vi%C3%AAn---T02?node-id=2058-4385)  
> **Backend Base URL chính thức (Cloud HTTPS 24/7):**  
> 👉 **`https://youth-union.onrender.com/api`**  
> **CORS:** Đã mở sẵn toàn quyền (`*`), Frontend gọi từ Vercel hoặc Localhost hoàn toàn không bị chặn.

---

## 📌 1. CẤU HÌNH BIẾN MÔI TRƯỜNG TRÊN VERCEL (FRONTEND)

Trong dự án Frontend (React / Vite / Next.js), cấu hình file `.env` hoặc trong mục **Environment Variables** trên Vercel:

```env
# Dành cho Vite (React)
VITE_API_URL=https://youth-union.onrender.com/api

# Dành cho React (Create-React-App)
REACT_APP_API_URL=https://youth-union.onrender.com/api
```

---

## 🧭 2. DANH SÁCH API KHỚP 100% MENU & MÀN HÌNH FIGMA

| STT | Màn hình trên Figma | Endpoint API (GET) | Mô tả nội dung trả về |
| :---: | :--- | :--- | :--- |
| **1** | 🏠 **Trang chủ** | `https://youth-union.onrender.com/api/home` | 1 Hero Banner, 3 ảnh hoạt động, khung Tình nguyện, 4 ô thống kê (68 năm, 8+ CLB, 9.6K đoàn viên), 5 tiêu chí Sinh viên 5 Tốt. |
| **2** | 🏛️ **Giới thiệu & Đoàn TN Học viện** | `https://youth-union.onrender.com/api/about` | Lịch sử 68 năm truyền thống, Ban Giám Đốc/Đảng ủy, Ban Thường vụ, 4 Ban chuyên môn (Tuyên giáo, Phong trào, Tổ chức - Kiểm tra, Văn phòng). |
| **3** | 🚩 **Phong trào** | `https://youth-union.onrender.com/api/movements` | 4 nhóm phong trào (Tình nguyện, Sáng tạo, Thể lực CAND, Sinh viên 5 Tốt) + Danh sách bài viết phong trào. |
| **4** | 🎯 **Hành trình phấn đấu (Sinh viên 5 Tốt)** | `https://youth-union.onrender.com/api/student-5-good` | Chi tiết 5 Tiêu chuẩn (Đạo đức, Học tập, Thể lực, Tình nguyện, Hội nhập) + Danh sách sinh viên tiêu biểu đạt danh hiệu. |
| **5** | 🏆 **CLB – Đội – Nhóm** | `https://youth-union.onrender.com/api/clubs`<br>`https://youth-union.onrender.com/api/club-categories` | Danh sách 7 CLB thật (Nội san, PPATV, Dân vũ, Sách, Guitar, Karate, Taekwondo) + 5 thể loại bộ lọc. |
| **6** | 📰 **Hoạt động & Tin tức** | `https://youth-union.onrender.com/api/activities`<br>`https://youth-union.onrender.com/api/activities/{id}` | Danh sách bài viết sự kiện (hỗ trợ phân trang `?per_page=9` và tìm kiếm `?search=...`) + Chi tiết bài viết có nội dung HTML. |
| **7** | ⭐ **Gương mặt sinh viên tiêu biểu** | `https://youth-union.onrender.com/api/outstanding-people` | Danh sách tuyên dương gương mặt trẻ tiêu biểu, Bí thư chi đoàn xuất sắc, Sinh viên 5 tốt cấp Thành phố/Bộ Công an. |
| **8** | 📚 **Thư viện** | `https://youth-union.onrender.com/api/media` | Thư viện ảnh hoạt động + 4 biểu mẫu văn bản (Đơn gia nhập CLB, Mẫu Sinh viên 5 Tốt, Kế hoạch Mùa hè xanh...). |
| **9** | 🖼️ **Tải ảnh lên Server (Upload)** | `POST https://youth-union.onrender.com/api/upload` | Upload ảnh từ máy tính (key `file`), trả về URL ảnh ngay lập tức. |
| **10**| 🔐 **Đăng nhập Quản trị Admin** | `POST https://youth-union.onrender.com/api/auth/login` | `{"email": "admin@youthunion.edu.vn", "password": "admin123"}` |

---

## 💻 3. CODE MẪU GỌI API TRÊN REACTJS (FETCH / AXIOS)

```javascript
// Ví dụ 1: Lấy dữ liệu Trang Chủ
export async function getHomeData() {
  const res = await fetch('https://youth-union.onrender.com/api/home');
  const json = await res.json();
  return json.data;
}

// Ví dụ 2: Lấy dữ liệu Hành trình Sinh viên 5 Tốt
export async function getStudent5GoodData() {
  const res = await fetch('https://youth-union.onrender.com/api/student-5-good');
  const json = await res.json();
  return json.data;
}

// Ví dụ 3: Lấy danh sách CLB có lọc theo thể loại
export async function getClubs(categoryId = null) {
  const url = categoryId 
    ? `https://youth-union.onrender.com/api/clubs?category_id=${categoryId}`
    : 'https://youth-union.onrender.com/api/clubs';
  const res = await fetch(url);
  const json = await res.json();
  return json.data;
}
```
