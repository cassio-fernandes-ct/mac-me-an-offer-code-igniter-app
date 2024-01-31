<?php
use Bigcommerce\Api\Client as Bigcommerce;

class Webhookcreatemodel extends CI_Model
{
    public function __construct()
    {
        $this->app_token_table = "app_token";
        $this->created_hook_table = "created_hook";
        $this->user_table = "users";
        $this->product_table = "products";
        $this->product_category_table = "product_category";
        $this->product_custom_fields_table = "product_custom_fields";
        $this->product_option_table = "product_option";
        $this->category_table = "category";
        $this->brand_table = "brands";
        $this->setting = "setting";

        include APPPATH . '/third_party/bcapi/vendor/autoload.php';
    }

    /*========== Product Import Start=============*/
    public function WebhookCallProduct($webhookres_id, $webhookres_scope)
    {

       // file_put_contents(APPPATH . 'third_party/hook/action_1.txt', print_r($webhookres_scope, true));
        if (isset($webhookres_scope) && !empty($webhookres_scope) && trim($webhookres_scope) == 'store/product/deleted') {
            $this->WebhookCallRemoveProduct($webhookres_id);
           // file_put_contents(APPPATH . 'third_party/hook/Product_0.txt', print_r($webhookres_id, true));
        } else {

            if (isset($webhookres_id) && !empty($webhookres_id)) {
                $config_data = $this->getBcConfig();
                $bcstoreurl = $config_data['storeurl'];
                $client_id = $config_data['client_id'];
                $store_hash = $config_data['storehas'];
                $auth_token = $config_data['apitoken'];

                Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash)); // Bc class connection
                Bigcommerce::verifyPeer(false);
                Bigcommerce::failOnError();
                $product_data = Bigcommerce::getProduct($webhookres_id);

                $this->WebHookProductsImport($webhookres_scope, $webhookres_id);

            }
        }
    }

    public function WebHookProductsImport($action, $product_id)
    {
        if (isset($action) && !empty($action) && trim($action) != 'store/product/updated') {

            //file_put_contents(APPPATH . 'third_party/hook/action.txt', print_r($action, true));
            $check_product_exist = '';
            $stock = '';
            $product_image = '';
            $product_price = '';
            $brand_data = '';
            $brand_id = 0;
            $brand_name = '';
            $product_name = '';

            $config_data = $this->getBcConfig();
            $bcstoreurl = $config_data['storeurl'];
            $client_id = $config_data['client_id'];
            //$store_hash        = 'z7godtn57o';
            $store_hash = $config_data['storehas'];
            $auth_token = $config_data['apitoken'];

            Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash)); // Bc class connection
            Bigcommerce::verifyPeer(false);
            Bigcommerce::failOnError();

            $product_data = Bigcommerce::getProduct($product_id);
            $productoption = Bigcommerce::getProductoptions($product_id);

            $createdate = date('Y-m-d H:i:s');
            $bc_product_status = 'inactive';
            if (isset($product_data->is_visible) && !empty($product_data->is_visible) && $product_data->is_visible == 1) {
                $bc_product_status = 'active';
            }
            $stock = $product_data->inventory_level;

            $sort_order = '';
            if (isset($product_data->sort_order) && !empty($product_data->sort_order)) {
                $sort_order = $product_data->sort_order;
            }
            if (isset($product_data->name) && !empty($product_data->name)) {$product_name = $product_data->name;}
            if (isset($product_data->primary_image->thumbnail_url) && !empty($product_data->primary_image->thumbnail_url)) {$product_image = $product_data->primary_image->thumbnail_url;}
            if (isset($product_data->price) && !empty($product_data->price)) {$product_price = $product_data->price;}

            $check_product_exist = $this->GetProductID($product_data->id);

            if (isset($check_product_exist) && !empty($check_product_exist)) {
                $data = array(
                    "bc_product_id" => $product_data->id,
                    "product_sku" => $product_data->sku,
                    "product_title" => $product_name,
                    "sort_order" => $sort_order,
                    "image" => $product_image,
                    "price" => $product_price,
                    "brand_id" => $brand_id,
                    "brand_name" => $brand_name,
                    "stock" => $stock,
                    "bc_product_status" => $bc_product_status,
                    "product_url" => $product_data->custom_url,
                    "create_date" => $createdate,
                );
                $this->db->where('bc_product_id', $product_data->id);
                $this->db->update($this->product_table, $data);
                $product_id = $product_data->id;

               // file_put_contents(APPPATH . 'third_party/hook/data_update.txt', print_r($data, true));
            } else {
                $data = array(
                    "bc_product_id" => $product_data->id,
                    "product_sku" => $product_data->sku,
                    "product_title" => $product_name,
                    "image" => $product_image,
                    "price" => $product_price,
                    "sort_order" => $sort_order,
                    "brand_id" => $brand_id,
                    "brand_name" => $brand_name,
                    "stock" => $stock,
                    "bc_product_status" => $bc_product_status,
                    "product_url" => $product_data->custom_url,
                    "create_date" => $createdate,
                );
                $this->db->insert($this->product_table, $data);
                $product_id = $this->db->insert_id();

                //file_put_contents(APPPATH . 'third_party/hook/data_isert.txt', print_r($data, true));
            }

            $this->db->delete($this->product_category_table, array('product_id' => $product_data->id));

           // file_put_contents(APPPATH . 'third_party/hook/categories_1.txt', print_r($product_data->categories, true));
            if (isset($product_data->categories) && !empty($product_data->categories)) {
                foreach ($product_data->categories as $category) {
                    $data_category = array(
                        "product_id" => $product_data->id,
                        "category_id" => $category,
                    );

                   // file_put_contents(APPPATH . 'third_party/hook/insert_1.txt', print_r($data_category, true));
                    $this->db->insert($this->product_category_table, $data_category);
                }
            }
        } else if (isset($action) && !empty($action) && trim($action) == 'store/product/updated') {

            // Stock update call function
            $check_product_exist = '';
            $stock = '';
            $product_image = '';
            $product_price = '';
            $brand_data = '';
            $brand_id = 0;
            $brand_name = '';
            $product_name = '';

            $config_data = $this->getBcConfig();
            $bcstoreurl = $config_data['storeurl'];
            $client_id = $config_data['client_id'];
            $store_hash = $config_data['storehas'];
            $auth_token = $config_data['apitoken'];

            Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash));
            Bigcommerce::verifyPeer(false);
            Bigcommerce::failOnError();

            $product_data = Bigcommerce::getProduct($product_id);
            $productoption = Bigcommerce::getProductoptions($product_id);
            //file_put_contents(APPPATH . 'third_party/hook/update_2.txt', print_r($product_data, true));

            $sort_order = '';
            if (isset($product_data->sort_order) && !empty($product_data->sort_order)) {
                $sort_order = $product_data->sort_order;
            }

            $createdate = date('Y-m-d H:i:s');
            $bc_product_status = 'inactive';
            if (isset($product_data->is_visible) && !empty($product_data->is_visible) && $product_data->is_visible == 1) {
                $bc_product_status = 'active';
            }
            $stock = $product_data->inventory_level;

            if (isset($product_data->name) && !empty($product_data->name)) {$product_name = $product_data->name;}
            if (isset($product_data->primary_image->thumbnail_url) && !empty($product_data->primary_image->thumbnail_url)) {$product_image = $product_data->primary_image->thumbnail_url;}
            if (isset($product_data->price) && !empty($product_data->price)) {$product_price = $product_data->price;}

           // file_put_contents(APPPATH . 'third_party/hook/update_3.txt', $product_data->id);
            $check_product_exist = $this->GetProductID($product_data->id);

            if (isset($check_product_exist) && !empty($check_product_exist)) {
                $data = array(
                    "bc_product_id" => $product_data->id,
                    "product_sku" => $product_data->sku,
                    "product_title" => $product_name,
                    "image" => $product_image,
                    "price" => $product_price,
                    "sort_order" => $sort_order,
                    "brand_id" => $brand_id,
                    "brand_name" => $brand_name,
                    "stock" => $stock,
                    "bc_product_status" => $bc_product_status,
                    "product_url" => $product_data->custom_url,
                    "create_date" => $createdate,
                );
                $this->db->where('bc_product_id', $product_data->id);
                $this->db->update($this->product_table, $data);
                $product_id = $product_data->id;

                //file_put_contents(APPPATH . 'third_party/hook/update_3.txt', $data);
            } else {
                $data = array(
                    "bc_product_id" => $product_data->id,
                    "product_sku" => $product_data->sku,
                    "product_title" => $product_name,
                    "image" => $product_image,
                    "price" => $product_price,
                    "brand_id" => $brand_id,
                    "brand_name" => $brand_name,
                    "sort_order" => $sort_order,
                    "stock" => $stock,
                    "bc_product_status" => $bc_product_status,
                    "product_url" => $product_data->custom_url,
                    "create_date" => $createdate,
                );
                $this->db->insert($this->product_table, $data);
                $product_id = $this->db->insert_id();

            }

            $this->db->delete($this->product_category_table, array('product_id' => $product_data->id));

            // Import category
            if (isset($product_data->categories) && !empty($product_data->categories)) {
                foreach ($product_data->categories as $category) {
                    $data_category = array(
                        "product_id" => $product_data->id,
                        "category_id" => $category,
                    );
                    $this->db->insert($this->product_category_table, $data_category);
                }
            }

            $this->db->delete($this->product_option_table, array('product_id' => $product_data->id));

            //file_put_contents(APPPATH . 'third_party/hook/updateproductoption.txt', 'hiiii');
            if (isset($productoption) && !empty($productoption)) {
                foreach ($productoption as $po) {

                    $getOptionvalue = Bigcommerce::getOptionCValue($po->option_id);

                    if (isset($getOptionvalue) && !empty($getOptionvalue)) {
                        foreach ($getOptionvalue as $pov) {
                            $data_option[] = array(
                                "product_id" => $product_data->id,
                                "option_id" => $po->option_id,
                                "attribut_id" => $po->id,
                                "option_set_name" => $this->db->escape_str($po->display_name),
                                "option_label" => $this->db->escape_str($pov->label),
                                "option_label_value_id" => $this->db->escape_str($pov->id),
                                "option_label_value" => $this->db->escape_str($pov->value),
                            );
                        }
                    }
                }

                $this->db->insert_batch($this->product_option_table, $data_option);
                //file_put_contents(APPPATH . 'third_party/hook/updateproductoption1.txt', print_r($data_option, true));
            }
        }
    }

    public function GetProductID($bc_product_id)
    {
        $query_product = $this->db->query("SELECT bc_product_id FROM " . $this->product_table . " WHERE bc_product_id='" . $bc_product_id . "'");
        return $query_product->num_rows();
    }

    public function getBrandData($brand_id)
    {
        $query = $this->db->get_where($this->brand_table, array('brand_id' => $brand_id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return '';
        }
    }

    public function storeBrandData($data)
    {
        $this->db->insert($this->brand_table, $data);
    }

    public function WebhookCallRemoveProduct($product_id)
    {
       // file_put_contents(APPPATH . 'third_party/hook/delete.txt', 'delete');
        /* Remove Product*/
        $this->db->where('bc_product_id', $product_id);
        $this->db->delete($this->product_table);

        /* Remove Category*/
        $this->db->delete($this->product_category_table, array('product_id' => $product_id));

        /* Remove CustomFiled*/
        //$this->db->delete($this->product_custom_fields_table,array('product_id'=>$product_id));

        /* Remove Options*/
        //$this->db->delete($this->product_option_table,array('product_id'=>$product_id));
    }

    public function RemoveCatCustomOpt($bc_product_id)
    {
        $this->db->delete($this->product_category_table, array('product_id' => $bc_product_id));
        //$this->db->delete($this->product_custom_fields_table,array('product_id'=>$bc_product_id));
        //$this->db->delete($this->product_option_table,array('product_id'=>$bc_product_id));
    }

    /*========== Product Import End =============*/

    /*========== Category Import Start =============*/
    public function WebhookCallCategory($webhookres_id, $webhookres_scope)
    {

        if (isset($webhookres_id) && !empty($webhookres_id)) {
            $config_data = $this->getBcConfig();

            $store = '';
            $bcstoreurl = $config_data['storeurl'];
            $client_id = $config_data['client_id'];
            $store_hash = $config_data['storehas'];
            $auth_token = $config_data['apitoken'];

            Bigcommerce::configure(array('client_id' => $client_id, 'auth_token' => $auth_token, 'store_hash' => $store_hash)); // Bc class connection
            Bigcommerce::verifyPeer(false); // SSL verify False
            Bigcommerce::failOnError(); // Display error exception on

            $category_data = Bigcommerce::getCategory($webhookres_id);

            // Import Product Dashboard
            $this->WebHookCategoryImport($category_data, $store, $webhookres_scope, $webhookres_id);
        }
    }

    public function WebHookCategoryImport($category_data, $store, $action, $category_id)
    {
        if (isset($action) && !empty($action) && trim($action) === 'store/category/deleted') {
            $this->WebhookCallRemoveCategory($category_id);
        } else {
            $insert = array();
            $update = array();       
            $image  = '';
            $checkCategoryExist = $this->checkCategoryExist($category_data->id);
            $categorydata = base64_encode(serialize($category_data));
            $image_base_path='https://cdn11.bigcommerce.com/s-ilhtqzrn07/product_images/';
            if (empty($category_data->image_file)) {
               // $image = asset_url() . 'media/images/default.jpg';
               $image = '';
            } else {
                $image = $image_base_path . $category_data->image_file;
            }
            if (isset($checkCategoryExist) && !empty($checkCategoryExist) && $checkCategoryExist == 'yes') {
                
                
				
                if ($category_data->sort_order == 0) {$sort_order = ' ';} else { $sort_order = $category_data->sort_order;}
                             
                $update[] = array(
                    "category_id" => $category_data->id,
                    "category_data" => $categorydata,
                    "parent_id" => $category_data->parent_id,
                    "name" => $category_data->name,
                    "description" => $category_data->description,
                    "sort_order" => $sort_order,
                    "page_title" => $category_data->page_title,
                    "meta_keywords" => $category_data->meta_keywords,
                    "layout_file" => $category_data->layout_file,
                    "is_visible" => $category_data->is_visible,
                    "search_keywords" => $category_data->search_keywords,
                    "url" => $category_data->url,
                    "image" => $image,
                );

            } else {
                if ($category_data->sort_order == 0) {$sort_order = ' ';} else { $sort_order = $category_data->sort_order;}

                $insert[] = array(
                    "category_id" => $category_data->id,
                    "category_data" => $categorydata,
                    "parent_id" => $category_data->parent_id,
                    "name" => $category_data->name,
                    "description" => $category_data->description,
                    "sort_order" => $sort_order,
                    "page_title" => $category_data->page_title,
                    "meta_keywords" => $category_data->meta_keywords,
                    "layout_file" => $category_data->layout_file,
                    "is_visible" => $category_data->is_visible,
                    "search_keywords" => $category_data->search_keywords,
                    "url" => $category_data->url,
                    "image" => $image,
                );
            }

            if (isset($insert) && !empty($insert)) {
                $this->db->insert_batch($this->category_table, $insert);
            }

            if (isset($update) && !empty($update)) {
                $this->db->update_batch($this->category_table, $update, 'category_id');
            }
        }
    }

    public function WebhookCallRemoveCategory($category_id)
    {
        $this->db->query("DELETE FROM `".$this->category_table."` WHERE `category_id`='".$category_id."'");
        $this->db->query("DELETE FROM `".$this->category_table."` WHERE `parent_id`='".$category_id."'");

    }
    public function checkCategoryExist($category_id)
    {
        $query_check_cat = $this->db->query("SELECT category_id FROM " . $this->category_table . " WHERE category_id ='" . $category_id . "'");
        $check_res_cat = $query_check_cat->num_rows();
        if (isset($check_res_cat) && !empty($check_res_cat)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    public function parentcatslug($category_id)
    {
        $parnet_slug = $this->db->query("SELECT slug FROM " . $this->category_table . " WHERE bc_category_id ='" . $category_id . "'");
        $parentslug_res_cat = $parnet_slug->row_array();

        if (isset($parentslug_res_cat['slug']) && !empty($parentslug_res_cat['slug'])) {
            return $parentslug_res_cat['slug'];
        } else {
            return '';
        }
    }

    /*========== Category Import Start =============*/

    public function RemoveDeteletProducts()
    {
        $query_product = $this->db->query("SELECT bc_product_id FROM " . $this->product_table . "");
        $product_tmp_details = $query_product->result_array();

        foreach ($product_tmp_details as $product_tmp_details_s) {
            $checkidexist = $this->checktmpproductexist($product_tmp_details_s['bc_product_id']);
            if (empty($checkidexist)) {
                $this->db->where('bc_product_id', $product_tmp_details_s['bc_product_id']);
                $this->db->delete('products');
            }
        }

    }

    public function checktmpproductexist($bc_product_id)
    {
        $query_product = $this->db->query("SELECT bc_product_id FROM product_tmp WHERE bc_product_id = '" . $bc_product_id . "'");
        return $query_product->num_rows();
    }

    public function getBcConfig()
    {
        $query = $this->db->get_where($this->setting, array('id' => 1));
        return $query->row_array();
    }

    public function GetwebhookDetails()
    {
        $select_app_token = $this->db->query("SELECT * FROM " . $this->user_table . "");
        $user_details = $select_app_token->row_array();
        return $user_details;
    }

    public function InsertToken($app_data)
    {
        $data_insert = array(
            "access_token" => $app_data['access_token'],
            "scope" => $app_data['scope'],
            "context" => $app_data['context'],
            "user_id" => $app_data['user_id'],
            "user_email" => $app_data['user_email'],
        );
        $this->db->insert($this->app_token_table, $data_insert);
        $app_token_id = $this->db->insert_id();
        return $app_token_id;
    }

    public function EmptyAppTabel()
    {
        $this->db->empty_table($this->app_token_table);
        $this->db->empty_table($this->created_hook_table);
    }
}
