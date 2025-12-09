<?php

namespace App\Controllers;

use App\Models\FoodModel;
use App\Models\CategoryModel;

class FoodController extends BaseController
{
    protected $foodModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->foodModel     = new FoodModel();
        $this->categoryModel = new CategoryModel();
    }

    /* =====================================================
       HIỂN THỊ DANH SÁCH + PHÂN TRANG
       ===================================================== */
    public function index()
    {
        $limit = 6;
        $page  = $this->request->getGet('page') ?? 1;

        $total      = $this->foodModel->countAll();
        $totalPages = ceil($total / $limit);

        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;

        $foods = $this->foodModel
            ->select("foods.*, categories.name AS category_name")
            ->join("categories", "categories.category_id = foods.category_id", "left")
            ->orderBy("foods.food_id", "DESC")
            ->paginate($limit);

        $categories = $this->categoryModel->findAll();

        return view("admin/manage_foods", [
            "foods"       => $foods,
            "categories"  => $categories,
            "currentPage" => $page,
            "totalPages"  => $totalPages
        ]);
    }


    /* =====================================================
       AJAX TÌM KIẾM
       ===================================================== */
    public function filter()
    {
        if (!$this->request->isAJAX()) return;

        $data = $this->request->getJSON(true);
        $keyword = $data['keyword'] ?? '';

        $query = $this->foodModel
            ->select("foods.*, categories.name AS category_name")
            ->join("categories", "categories.category_id = foods.category_id", "left");

        if ($keyword !== '') {
            $query->groupStart()
                ->like("foods.name", $keyword)
                ->orLike("foods.description", $keyword)
                ->orLike("categories.name", $keyword)
                ->groupEnd();
        }

        $foods = $query->orderBy("foods.food_id", "DESC")->findAll();

        if (empty($foods)) {
            return $this->response->setJSON([
                "table" => "<tr><td colspan='7' class='text-danger text-center fw-bold'>
                                Không tìm thấy món ăn phù hợp
                            </td></tr>"
            ]);
        }

        $html = "";
        foreach ($foods as $f) {
            $html .= $this->rowHTML($f);
        }

        return $this->response->setJSON(["table" => $html]);
    }


    /* =====================================================
       TẠO HTML CHO 1 ROW
       ===================================================== */
    private function rowHTML($f)
    {
        $img = base_url("upload/foods/" . $f['image_url']);

        return "
        <tr>
            <td>{$f['food_id']}</td>
            <td><img src='{$img}' width='60' height='60' style='object-fit:cover;border-radius:6px'></td>
            <td>" . esc($f['name']) . "</td>
            <td>" . esc($f['category_name']) . "</td>

            <!-- ⭐ Hiển thị giá đẹp 55.000 -->
            <td>" . number_format($f['price'], 0, ',', '.') . " đ</td>

            <td>{$f['stock_quantity']}</td>

            <td>
                <button class='btn btn-warning btn-sm'
                    onclick=\"openEditModal(
                        {$f['food_id']},
                        '" . esc($f['name'], 'js') . "',
                        '" . esc($f['description'], 'js') . "',
                        {$f['category_id']},

                        /* ⭐ Truyền giá nguyên dạng số 55000 */
                        '" . intval($f['price']) . "',

                        '{$f['stock_quantity']}',
                        '{$f['image_url']}'
                    )\">
                    <i class='fa fa-edit'></i>
                </button>

                <button class='btn btn-danger btn-sm'
                        onclick='deleteFood({$f['food_id']})'>
                    <i class='fa fa-trash'></i>
                </button>
            </td>
        </tr>
        ";
    }



    /* =====================================================
       XỬ LÝ UPLOAD ẢNH
       ===================================================== */
    private function uploadImage()
    {
        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();

            // ⭐ LƯU ẢNH ĐÚNG THƯ MỤC BẠN TẠO: public/upload/foods
            $file->move(ROOTPATH . 'public/upload/foods', $newName);

            return $newName;
        }

        return null;
    }


    /* =====================================================
       THÊM MÓN ĂN
       ===================================================== */
    public function add()
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(["error" => "Invalid request"]);

        $img = $this->uploadImage();

        $data = [
            "name"           => $this->request->getPost("name"),
            "description"    => $this->request->getPost("description"),
            "category_id"    => $this->request->getPost("category_id"),
            "price"          => $this->request->getPost("price"),
            "stock_quantity" => $this->request->getPost("stock_quantity"),
            "image_url"      => $img
        ];

        $this->foodModel->insert($data);

        return $this->response->setJSON(["success" => true]);
    }


    /* =====================================================
       SỬA MÓN ĂN
       ===================================================== */
    public function update($id)
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(["error" => "Invalid request"]);

        $food = $this->foodModel->find($id);
        $img  = $this->uploadImage();

        $data = [
            "name"           => $this->request->getPost("name"),
            "description"    => $this->request->getPost("description"),
            "category_id"    => $this->request->getPost("category_id"),
            "price"          => $this->request->getPost("price"),
            "stock_quantity" => $this->request->getPost("stock_quantity"),
        ];

        // Nếu chọn ảnh mới → xóa ảnh cũ
        if ($img !== null) {
            if (!empty($food['image_url'])) {
                $oldPath = ROOTPATH . "public/upload/foods/" . $food['image_url'];
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $data['image_url'] = $img;
        }

        $this->foodModel->update($id, $data);

        return $this->response->setJSON(["success" => true]);
    }


    /* =====================================================
       XÓA MÓN ĂN
       ===================================================== */
    public function delete($id)
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(["error" => "Invalid request"]);

        $food = $this->foodModel->find($id);

        // Xóa ảnh khỏi thư mục
        if (!empty($food['image_url'])) {
            $path = ROOTPATH . "public/upload/foods/" . $food['image_url'];
            if (file_exists($path)) unlink($path);
        }

        $this->foodModel->delete($id);

        return $this->response->setJSON(["success" => true]);
    }
    /* =====================================================
   LẤY CHI TIẾT MÓN ĂN (API CHO MODAL HOME)
   ===================================================== */
    public function detail($id)
    {
        $food = $this->foodModel->find($id);

        if (!$food) {
            return $this->response->setJSON([
                "error" => "Không tìm thấy món ăn"
            ]);
        }

        // ===============================
        // LẤY KHUYẾN MÃI HỢP LỆ HÔM NAY
        // ===============================
        $db = \Config\Database::connect();
        $today = date("Y-m-d");

        $promo = $db->table('promotions')
                    ->where('food_id', $id)
                    ->where('start_date <=', $today)
                    ->where('end_date >=', $today)
                    ->get()
                    ->getRowArray();

        if ($promo) {
            $discountRate = (int)$promo['discount_rate'];
            $newPrice = $food['price'] - ($food['price'] * $discountRate / 100);
        } else {
            $discountRate = 0;
            $newPrice = $food['price'];
        }

        // ===============================
        // TRẢ JSON CHUẨN CHO JAVASCRIPT
        // ===============================
        return $this->response->setJSON([
            "food_id"        => (int)$food["food_id"],
            "name"           => $food["name"],
            "description"    => $food["description"],
            "price"          => (int)$food["price"],
            "new_price"      => (int)$newPrice,
            "discount_rate"  => $discountRate,
            "stock_quantity" => (int)$food["stock_quantity"],
            "image_url"      => $food["image_url"]
        ]);
    }

}
