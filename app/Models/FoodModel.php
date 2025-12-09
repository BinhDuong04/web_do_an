<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
    protected $table      = 'foods';       // Tên bảng
    protected $primaryKey = 'food_id';     // Khóa chính

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    // Các cột được phép insert/update
    protected $allowedFields = [
        'name',
        'description',
        'image_url',
        'category_id',
        'price',
        'stock_quantity'
    ];

    // Không dùng created_at / updated_at
    protected $useTimestamps = false;

    /* =====================================================
       LẤY MÓN ĂN THEO ID
       ===================================================== */
    public function getFoodById($id)
    {
        return $this->where('food_id', $id)->first();
    }

    /* =====================================================
       LẤY TẤT CẢ MÓN ĂN + JOIN TÊN DANH MỤC
       ===================================================== */
    public function getAllFoods()
    {
        return $this->select('foods.*, categories.name AS category_name')
                    ->join('categories', 'categories.category_id = foods.category_id', 'left')
                    ->orderBy('foods.food_id', 'DESC')
                    ->findAll();
    }

    /* =====================================================
       TÌM KIẾM MÓN ĂN
       ===================================================== */
    public function searchFoods($keyword)
    {
        return $this->select('foods.*, categories.name AS category_name')
                    ->join('categories', 'categories.category_id = foods.category_id', 'left')
                    ->groupStart()
                        ->like('foods.name', $keyword)
                        ->orLike('foods.description', $keyword)
                        ->orLike('categories.name', $keyword)
                    ->groupEnd()
                    ->orderBy('foods.food_id', 'DESC')
                    ->findAll();
    }

    /* =====================================================
       LẤY MÓN ĂN THEO DANH MỤC
       ===================================================== */
    public function getFoodsByCategory($categoryId)
    {
        return $this->select('foods.*, categories.name AS category_name')
                    ->join('categories', 'categories.category_id = foods.category_id', 'left')
                    ->where('foods.category_id', $categoryId)
                    ->orderBy('foods.food_id', 'DESC')
                    ->findAll();
    }
        /* =====================================================
    LẤY DANH SÁCH MÓN NỔI BẬT (GIỚI HẠN 6 MÓN)
    ===================================================== */
    public function getFeaturedFoods()
    {
        return $this->select('foods.*, categories.name AS category_name')
                    ->join('categories', 'categories.category_id = foods.category_id', 'left')
                    ->orderBy('foods.food_id', 'DESC')
                    ->limit(6)
                    ->findAll();
    }

}
