# 🚀 TÀI LIỆU HƯỚNG DẪN KẾT NỐI API BACKEND (DÀNH CHO ĐỘI FRONTEND)

> **Dự án:** Website Đoàn Thanh Niên  
> **Backend Base URL chính thức (Cloud HTTPS 24/7):**  
> 👉 **`https://youth-union.onrender.com/api`**  
> **CORS:** Đã được mở toàn quyền (`*`), Frontend gọi từ Vercel, Localhost (`localhost:3000`, `localhost:5173`) hoàn toàn không bị chặn.

---

## 📌 1. CẤU HÌNH BIẾN MÔI TRƯỜNG TRÊN VERCEL (FRONTEND)
Trong dự án Frontend (React / Vite / Next.js), các bạn cấu hình file `.env` hoặc trong mục **Environment Variables** trên Vercel:

```env
# Dành cho React (Create-React-App)
REACT_APP_API_URL=https://youth-union.onrender.com/api

# Dành cho Vite (React / Vue)
VITE_API_URL=https://youth-union.onrender.com/api

# Dành cho Next.js
NEXT_PUBLIC_API_URL=https://youth-union.onrender.com/api
```

---

## 📡 2. DANH SÁCH CHI TIẾT CÁC API THEO TỪNG TRANG GIAO DIỆN

### 🏠 2.1. TRANG CHỦ (Home Page)
* **Mục đích:** Lấy toàn bộ dữ liệu tổng hợp cho trang chủ chỉ với 1 lần gọi API duy nhất (tối ưu tốc độ).
* **Method:** `GET`
* **URL:** `https://youth-union.onrender.com/api/home`
* **Dữ liệu trả về (JSON) khớp 100% bản thiết kế Figma:**
  * `hero_banner`: **1 Banner chính đầu trang** (Title, Tagline, Description, Ảnh, 2 nút bấm).
  * `activity_images`: **Mảng 3 ảnh hoạt động tiêu biểu** để hiển thị 3 khung ảnh bên phải.
  * `movement_highlight`: Khung phong trào *"Tình nguyện & Đền ơn đáp nghĩa"* (Badge, Title, Description, Tags, Images).
  * `metrics`: 4 ô số liệu thống kê (68 năm truyền thống, 8+ CLB, 9.6K đoàn viên, Sinh viên 5 Tốt).
  * `student_5_criteria`: 5 tiêu chí Sinh viên 5 Tốt (Đạo đức, Học tập, Thể lực, Tình nguyện, Hội nhập).
  * `latest_activities`: Danh sách các bài viết hoạt động mới nhất.
  * `featured_clubs`: 6 Câu lạc bộ tiêu biểu kèm thể loại.
  * `featured_people`: 4 gương mặt đoàn viên / cán bộ tiêu biểu.

---

### 📰 2.2. TRANG HOẠT ĐỘNG & TIN TỨC (Activities)
* **Lấy danh sách hoạt động:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/activities`
  * **Tham số tìm kiếm & phân trang (Query Params tùy chọn):**
    * `?search=tên_bài_viết` (Tìm kiếm theo tiêu đề)
    * `?per_page=6` (Số bài trên mỗi trang)
* **Xem chi tiết 1 bài viết:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/activities/{id}`
  * *Nội dung `content` đã có sẵn định dạng HTML (in đậm, in nghiêng, tiêu đề).*

---

### 🏆 2.3. TRANG CÂU LẠC BỘ (Clubs - Đã nạp 7 CLB thật)
* **Lấy danh sách các Thể loại CLB:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/club-categories`
  * *(Gồm 5 thể loại: Học thuật, Văn hóa – Nghệ thuật, Thể thao – Võ thuật, Truyền thông – Tuyên truyền, Tình nguyện).*
* **Lấy danh sách tất cả Câu Lạc Bộ:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/clubs`
  * **Lọc theo thể loại:** `https://youth-union.onrender.com/api/clubs?category_id=1`
* **Xem chi tiết 1 CLB:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/clubs/{id}`

---

### ⭐ 2.4. TRANG GƯƠNG MẶT TIÊU BIỂU (Outstanding People)
* **Lấy danh sách gương mặt tiêu biểu:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/outstanding-people`
  * **Lọc theo nhóm vai trò:**
    * `?role_group=BI_THU_DOAN` (Bí thư Chi đoàn)
    * `?role_group=DOAN_VIEN` (Đoàn viên xuất sắc, Sinh viên 5 tốt)
    * `?role_group=BGD` (Ban Giám Đốc, Cố vấn)

---

### 🖼️ 2.5. TRANG BANNER SLIDER (Banners)
* **Lấy danh sách banner slider:**
  * **Method:** `GET`
  * **URL:** `https://youth-union.onrender.com/api/banners`

---

### 🔐 2.6. XÁC THỰC & ĐĂNG NHẬP ADMIN (Authentication)
* **Đăng nhập lấy Token Sanctum:**
  * **Method:** `POST`
  * **URL:** `https://youth-union.onrender.com/api/auth/login`
  * **Body (JSON):**
    ```json
    {
      "email": "admin@youthunion.edu.vn",
      "password": "admin123"
    }
    ```
* **Tài khoản test có sẵn:**
  * **Admin:** `admin@youthunion.edu.vn` / Mật khẩu: `admin123`
  * **Cán bộ Đoàn (Editor):** `bithu@youthunion.edu.vn` / Mật khẩu: `123456`

---

## 💻 3. CODE MẪU GỌI API BẰNG JAVASCRIPT (FETCH / AXIOS)

```javascript
// Ví dụ: Lấy dữ liệu Trang chủ bằng Fetch API
async function loadHomePage() {
  try {
    const res = await fetch('https://youth-union.onrender.com/api/home', {
      headers: {
        'Accept': 'application/json'
      }
    });
    const result = await res.json();
    console.log('Dữ liệu Trang chủ:', result.data);
    return result.data;
  } catch (error) {
    console.error('Lỗi khi gọi API:', error);
  }
}
```
