<?php



namespace Myapp;

abstract class Controller
{
    abstract function index();

    public $userAuth = null;
    protected $data = [];

    public function __construct()
    {
        $this->data['error'] = null;
        $this->data[''] = null;
        $this->data['message'] = null;

        $this->userAuth = new UserAuth();

        $this->getFormatOptions();
        $this->getFormatNavigation();
    }

    protected function getFormatOptions()
    {
        $optModel = new OptionsModel();
        $this->data["options"] = [];
        foreach ($optModel->getAllOptions() as $key => $value) {
            $this->data["options"][$value["name"]] = $value;
        }
        unset($optModel);
    }

    protected function getFormatCategories()
    {
        $categoryModel = new CategoryModel();
        $this->data["categories"] = [];
        foreach ($categoryModel->getAllCategories() as $key => $value) {
            $this->data["categories"][$value["category"]] = $value;
        }
        unset($categoryModel);
    }

    protected function getFormatNavigation()
    {
        $navModel = new NavigateModel();
        $this->data["navigate"] = [];

        $result = $navModel->getNavigateData();

        foreach ($result as $key => $navElement) {
            if (is_null($navElement["parent_id"])) {
                $navElement["children"] = [];
                array_push($this->data["navigate"], $navElement);
            }
        }

        foreach ($this->data["navigate"] as $parentIndex => $parent) {
            foreach ($result as $childIndex => $child) {
                if ($child["parent_id"] == $parent["Id"]) {
                    array_push($this->data["navigate"][$parentIndex]["children"], $child);
                }
            }
        }

        unset($navModel);

    }

    public function call($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();

        } else {
            throw new \Exception("Method doesnt exist");
        }
    }
}