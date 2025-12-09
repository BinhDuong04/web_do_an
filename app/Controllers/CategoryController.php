<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /* =====================================================
       HIỂN THỊ DANH SÁCH + PHÂN TRANG
       ===================================================== */
    public function index()
    {
        $limit = 6;
        $page  = $this->request->getGet('page') ?? 1;

        $total       = $this->categoryModel->countAll();
        $totalPages  = ceil($total / $limit);

        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;

        $categories = $this->categoryModel
            ->orderBy('category_id', 'DESC')
            ->paginate($limit);

        return view('admin/manage_categories', [
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages'  => $totalPages
        ]);
    }

    /* =====================================================
       FILTER AJAX – TÌM KIẾM DANH MỤC
       ===================================================== */
    public function filter()
    {
        if (!$this->request->isAJAX()) return;

        $data = $this->request->getJSON(true);

        $keyword = $data['keyword'] ?? '';

        $query = $this->categoryModel;

        if ($keyword !== '') {
            $query = $query->groupStart()
                    ->like('name', $keyword)
                    ->orLike('description', $keyword)
                    ->groupEnd();
        }

        $categories = $query->orderBy('category_id', 'DESC')->findAll();

        if (empty($categories)) {
            return $this->response->setJSON([
                "table" => "
                    <tr>
                        <td colspan='4' class='text-danger text-center fw-bold'>
                            Không tìm thấy danh mục phù hợp
                        </td>
                    </tr>"
            ]);
        }

        $html = "";
        foreach ($categories as $c) {
            $html .= $this->rowHTML($c);
        }

        return $this->response->setJSON(["table" => $html]);
    }

    /* =====================================================
       TẠO HTML CHO 1 ROW TRONG BẢNG
       ===================================================== */
    private function rowHTML($c)
    {
        return "
        <tr>
            <td>{$c['category_id']}</td>
            <td>" . esc($c['name']) . "</td>
            <td>" . esc($c['description']) . "</td>

            <td>
                <button class='btn btn-warning btn-sm'
                    onclick=\"openEditModal(
                        {$c['category_id']},
                        '" . esc($c['name'], 'js') . "',
                        '" . esc($c['description'], 'js') . "'
                    )\">
                    <i class='fa fa-edit'></i>
                </button>

                <button class='btn btn-danger btn-sm'
                        onclick='deleteCategory({$c["category_id"]})'>
                    <i class='fa fa-trash'></i>
                </button>
            </td>
        </tr>
        ";
    }

    /* =====================================================
       LOAD LẠI TABLE + PHÂN TRANG MỚI NHẤT
       ===================================================== */
    private function loadPageTable($limit = 6)
    {
        $page = $this->request->getGet('page') ?? 1;

        $total      = $this->categoryModel->countAll();
        $totalPages = ceil($total / $limit);

        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;

        $categories = $this->categoryModel
            ->orderBy('category_id', 'DESC')
            ->paginate($limit);

        $html = "";
        foreach ($categories as $c) {
            $html .= $this->rowHTML($c);
        }

        return [
            "html"       => $html,
            "page"       => $page,
            "totalPages" => $totalPages
        ];
    }

    /* =====================================================
       THÊM DANH MỤC (AJAX)
       ===================================================== */
    public function add()
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(['error' => 'Invalid request']);

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $newID = $this->categoryModel->insert($data);

        if (!$newID) {
            return $this->response->setJSON([
                "success" => false,
                "error" => "Thêm danh mục thất bại!"
            ]);
        }

        $result = $this->loadPageTable();

        return $this->response->setJSON([
            "success"     => true,
            "table"       => $result["html"],
            "currentPage" => $result["page"],
            "totalPages"  => $result["totalPages"]
        ]);
    }

    /* =====================================================
       SỬA DANH MỤC (AJAX)
       ===================================================== */
    public function update($id)
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(['error' => 'Invalid request']);

        $data = [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $this->categoryModel->update($id, $data);

        $result = $this->loadPageTable();

        return $this->response->setJSON([
            "success"     => true,
            "table"       => $result["html"],
            "currentPage" => $result["page"],
            "totalPages"  => $result["totalPages"]
        ]);
    }

    /* =====================================================
       XÓA DANH MỤC (AJAX)
       ===================================================== */
    public function delete($id)
    {
        if (!$this->request->isAJAX())
            return $this->response->setJSON(["error" => "Invalid request"]);

        $this->categoryModel->delete($id);

        $result = $this->loadPageTable();

        return $this->response->setJSON([
            "success"     => true,
            "table"       => $result["html"],
            "currentPage" => $result["page"],
            "totalPages"  => $result["totalPages"]
        ]);
    }
}
    