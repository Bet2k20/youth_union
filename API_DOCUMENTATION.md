# TÀI LIỆU API BACKEND (TOÀN DIỆN & ĐẦY ĐỦ) - ĐOÀN THANH NIÊN (YOUTH UNION)

> **Base URL:** `http://127.0.0.1:8888/api`  
> **Định dạng dữ liệu:** `JSON` (Header: `Content-Type: application/json`, `Accept: application/json`)  
> **CORS:** Đã được bật sẵn cho tất cả nguồn (`*`), Frontend thoải mái gọi từ bất kỳ port nào (`localhost:3000`, `localhost:5173`,...).

---

## 1. API Tải Ảnh Từ Máy Tính (File Upload)

* **Method:** `POST`
* **URL:** `/upload`
* **Content-Type:** `multipart/form-data`
* **Tham số Form-Data:**
  * `file`: File ảnh từ máy tính (Định dạng: `jpg, jpeg, png, gif, webp, svg` - Dung lượng tối đa: `5MB`)
  * `folder` (Tùy chọn): Tên thư mục con lưu trữ (`activities`, `clubs`, `people`, `banners`, `avatars`, `general`)
* **Response Thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Tải ảnh lên máy chủ thành công!",
  "data": {
    "file_name": "1788313742_rmbziDUIuH.png",
    "relative_path": "/uploads/activities/1788313742_rmbziDUIuH.png",
    "url": "/uploads/activities/1788313742_rmbziDUIuH.png",
    "full_url": "http://127.0.0.1:8888/uploads/activities/1788313742_rmbziDUIuH.png",
    "size": 102400
  }
}
```

---

## 2. API Xác Thực & Tài Khoản (Authentication)

### 2.1. [Đăng Nhập] Đăng nhập lấy Token Sanctum
* **Method:** `POST`
* **URL:** `/auth/login`
* **Body (JSON):**
```json
{
  "email": "admin@youthunion.edu.vn",
  "password": "admin123"
}
```
* **Response Thành công (200 OK):**
```json
{
  "status": "success",
  "message": "Đăng nhập thành công!",
  "data": {
    "token": "1|qaYnCBXbMynApoBFYm5qnf7WQj3DbxmPwyckXhdY",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Quản Trị Viên Tối Cao",
      "email": "admin@youthunion.edu.vn",
      "role": "admin",
      "phone": "0901234567",
      "avatar": null
    }
  }
}
```
> *Sau khi đăng nhập, các request cần quyền Admin sẽ gửi kèm Header: `Authorization: Bearer <token>`.*

### 2.2. [Đăng Xuất] Hủy Token
* **Method:** `POST`
* **URL:** `/auth/logout`

### 2.3. [Thông Tin Cá Nhân]
* **Method:** `GET`
* **URL:** `/auth/me`

### 2.4. [Đổi Mật Khẩu]
* **Method:** `POST`
* **URL:** `/auth/change-password`

---

## 3. API Quản Lý Tài Khoản (Users - CRUD)

* **Danh sách tài khoản:** `GET /users` *(hỗ trợ `?role=admin` hoặc `?search=...`)*
* **Xem chi tiết tài khoản:** `GET /users/{id}`
* **Tạo tài khoản mới:** `POST /users`
* **Cập nhật tài khoản:** `PUT /users/{id}`
* **Xóa tài khoản:** `DELETE /users/{id}`

---

## 4. API Trang Chủ (Home Summary)
* **Method:** `GET`
* **URL:** `/home`
* **Mô tả:** Lấy dữ liệu tổng hợp cho trang chủ (bao gồm: `banners`, `latest_activities`, `featured_clubs`, `featured_people`, và các con số `statistics`).

---

## 5. API Banner Slider (Banners - CRUD)
* **Lấy danh sách:** `GET /banners` *(hỗ trợ `?all=1`)*
* **Xem chi tiết:** `GET /banners/{id}`
* **Thêm mới:** `POST /banners`
```json
{
  "title": "Chào Đón Tân Sinh Viên Khóa 2026",
  "image_url": "/uploads/banners/banner1.jpg",
  "link_url": "/hoat-dong",
  "order": 1,
  "is_active": true
}
```
* **Sửa:** `PUT /banners/{id}`
* **Xóa:** `DELETE /banners/{id}`

---

## 6. API Hoạt Động & Tin Tức (Activities - CRUD)
* **Lấy danh sách:** `GET /activities` *(hỗ trợ `?search=...`, `?per_page=...`, `?all=1`)*
* **Xem chi tiết:** `GET /activities/{id}`
* **Thêm mới:** `POST /activities`
```json
{
  "title": "Hội thao Chào mừng 26/3",
  "thumbnail": "/uploads/activities/anh1.jpg",
  "content": "<p>Nội dung chi tiết chương trình hội thao...</p>",
  "is_active": true
}
```
* **Sửa:** `PUT /activities/{id}`
* **Xóa:** `DELETE /activities/{id}`

---

## 7. API Câu Lạc Bộ & Thể Loại (Clubs - CRUD)
* **Lấy danh sách thể loại:** `GET /club-categories`
* **Lấy danh sách CLB:** `GET /clubs` *(hỗ trợ `?category_id=...`, `?search=...`)*
* **Xem chi tiết CLB:** `GET /clubs/{id}`
* **Thêm mới CLB:** `POST /clubs`
```json
{
  "name": "CLB Tin Học Sinh Viên",
  "logo": "/uploads/clubs/logo_it.png",
  "category_id": 1,
  "founded_date": "2024-03-26",
  "description": "Nghiên cứu lập trình và AI."
}
```
* **Sửa CLB:** `PUT /clubs/{id}`
* **Xóa CLB:** `DELETE /clubs/{id}`

---

## 8. API Gương Mặt Tiêu Biểu (Outstanding People - CRUD)
* **Lấy danh sách:** `GET /outstanding-people` *(hỗ trợ `?role_group=DOAN_VIEN`)*
* **Xem chi tiết:** `GET /outstanding-people/{id}`
* **Thêm mới:** `POST /outstanding-people`
```json
{
  "name": "Hoàng Minh Châu",
  "avatar": "/uploads/people/avatar_chau.jpg",
  "role_group": "DOAN_VIEN",
  "class_unit": "Chi đoàn K17 Ngoại ngữ",
  "achievement": "Sinh viên 5 tốt cấp Thành phố.",
  "is_active": true
}
```
* **Sửa:** `PUT /outstanding-people/{id}`
* **Xóa:** `DELETE /outstanding-people/{id}`
