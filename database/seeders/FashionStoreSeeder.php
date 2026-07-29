<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;

class FashionStoreSeeder extends Seeder
{
    protected $attributeRepository;
    protected $attributeFamilyRepository;
    protected $categoryRepository;

    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeFamilyRepository $attributeFamilyRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeFamilyRepository = $attributeFamilyRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function run()
    {
        $this->seedAttributes();
        $this->seedAttributeFamily();
        $this->seedCategories();
    }

    protected function seedAttributes()
    {
        $attributes = [
            [
                'code' => 'gender',
                'admin_name' => 'Gender',
                'type' => 'select',
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 0,
                'en' => ['name' => 'Gender'],
                'options' => [
                    ['admin_name' => 'Men', 'sort_order' => 1, 'en' => ['label' => 'Men']],
                    ['admin_name' => 'Women', 'sort_order' => 2, 'en' => ['label' => 'Women']],
                    ['admin_name' => 'Unisex', 'sort_order' => 3, 'en' => ['label' => 'Unisex']],
                ]
            ],
            [
                'code' => 'collection',
                'admin_name' => 'Collection',
                'type' => 'select',
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 0,
                'en' => ['name' => 'Collection'],
                'options' => [
                    ['admin_name' => 'Spring/Summer', 'sort_order' => 1, 'en' => ['label' => 'Spring/Summer']],
                    ['admin_name' => 'Autumn/Winter', 'sort_order' => 2, 'en' => ['label' => 'Autumn/Winter']],
                    ['admin_name' => 'Essentials', 'sort_order' => 3, 'en' => ['label' => 'Essentials']],
                ]
            ],
            [
                'code' => 'season',
                'admin_name' => 'Season',
                'type' => 'select',
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 0,
                'en' => ['name' => 'Season'],
                'options' => [
                    ['admin_name' => '2024', 'sort_order' => 1, 'en' => ['label' => '2024']],
                    ['admin_name' => '2025', 'sort_order' => 2, 'en' => ['label' => '2025']],
                ]
            ],
            [
                'code' => 'material',
                'admin_name' => 'Material',
                'type' => 'select',
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 0,
                'en' => ['name' => 'Material'],
                'options' => [
                    ['admin_name' => 'Cotton', 'sort_order' => 1, 'en' => ['label' => 'Cotton']],
                    ['admin_name' => 'Polyester', 'sort_order' => 2, 'en' => ['label' => 'Polyester']],
                    ['admin_name' => 'Leather', 'sort_order' => 3, 'en' => ['label' => 'Leather']],
                    ['admin_name' => 'Denim', 'sort_order' => 4, 'en' => ['label' => 'Denim']],
                ]
            ],
        ];

        foreach ($attributes as $attributeData) {
            if (!$this->attributeRepository->findOneByField('code', $attributeData['code'])) {
                $this->attributeRepository->create($attributeData);
            }
        }
    }

    protected function seedAttributeFamily()
    {
        if ($this->attributeFamilyRepository->findOneByField('code', 'fashion')) {
            return; // Already exists
        }

        // We fetch existing attributes to assign them
        $allAttributes = $this->attributeRepository->all();
        $attrMap = $allAttributes->keyBy('code');

        $familyData = [
            'code' => 'fashion',
            'name' => 'Fashion',
            'status' => 1,
            'is_user_defined' => 1,
            'attribute_groups' => [
                [
                    'name' => 'General',
                    'position' => 1,
                    'is_user_defined' => 0,
                    'custom_attributes' => [
                        ['id' => $attrMap['name']->id],
                        ['id' => $attrMap['sku']->id],
                        ['id' => $attrMap['url_key']->id],
                        ['id' => $attrMap['tax_category_id']->id],
                        ['id' => $attrMap['new']->id],
                        ['id' => $attrMap['featured']->id],
                        ['id' => $attrMap['visible_individually']->id],
                        ['id' => $attrMap['status']->id],
                        ['id' => $attrMap['guest_checkout']->id],
                    ]
                ],
                [
                    'name' => 'Description',
                    'position' => 2,
                    'is_user_defined' => 0,
                    'custom_attributes' => [
                        ['id' => $attrMap['short_description']->id],
                        ['id' => $attrMap['description']->id],
                    ]
                ],
                [
                    'name' => 'Meta Description',
                    'position' => 3,
                    'is_user_defined' => 0,
                    'custom_attributes' => [
                        ['id' => $attrMap['meta_title']->id],
                        ['id' => $attrMap['meta_keywords']->id],
                        ['id' => $attrMap['meta_description']->id],
                    ]
                ],
                [
                    'name' => 'Price',
                    'position' => 4,
                    'is_user_defined' => 0,
                    'custom_attributes' => [
                        ['id' => $attrMap['price']->id],
                        ['id' => $attrMap['cost']->id],
                        ['id' => $attrMap['special_price']->id],
                        ['id' => $attrMap['special_price_from']->id],
                        ['id' => $attrMap['special_price_to']->id],
                    ]
                ],
                [
                    'name' => 'Fashion Specifics',
                    'position' => 5,
                    'is_user_defined' => 1,
                    'custom_attributes' => [
                        ['id' => $attrMap['brand']->id],
                        ['id' => $attrMap['gender']->id],
                        ['id' => $attrMap['collection']->id],
                        ['id' => $attrMap['season']->id],
                        ['id' => $attrMap['material']->id],
                    ]
                ],
                [
                    'name' => 'Variants (Configurable)',
                    'position' => 6,
                    'is_user_defined' => 1,
                    'custom_attributes' => [
                        ['id' => $attrMap['color']->id],
                        ['id' => $attrMap['size']->id],
                    ]
                ],
                [
                    'name' => 'Shipping',
                    'position' => 7,
                    'is_user_defined' => 0,
                    'custom_attributes' => [
                        ['id' => $attrMap['weight']->id],
                        ['id' => $attrMap['length']->id],
                        ['id' => $attrMap['width']->id],
                        ['id' => $attrMap['height']->id],
                    ]
                ]
            ]
        ];

        $this->attributeFamilyRepository->create($familyData);
    }

    protected function seedCategories()
    {
        // Root Category usually has ID 1
        $root = $this->categoryRepository->find(1);

        $categories = [
            'Men' => [
                'Tops',
                'Bottoms',
                'Jackets',
                'Shoes'
            ],
            'Women' => [
                'Dresses',
                'Tops',
                'Bottoms',
                'Shoes'
            ],
            'Accessories' => [
                'Bags',
                'Jewelry',
                'Hats'
            ]
        ];

        $position = 1;
        foreach ($categories as $parentName => $children) {
            // Create Parent
            $parent = $this->categoryRepository->create([
                'position' => $position++,
                'status' => 1,
                'parent_id' => $root->id,
                'en' => [
                    'name' => $parentName,
                    'slug' => strtolower($parentName),
                    'description' => $parentName . ' Category',
                    'meta_title' => $parentName,
                    'meta_description' => $parentName . ' Category',
                    'meta_keywords' => strtolower($parentName)
                ]
            ]);

            $childPosition = 1;
            foreach ($children as $childName) {
                $this->categoryRepository->create([
                    'position' => $childPosition++,
                    'status' => 1,
                    'parent_id' => $parent->id,
                    'en' => [
                        'name' => $childName,
                        'slug' => strtolower($parentName) . '-' . strtolower($childName),
                        'description' => $childName . ' Category',
                        'meta_title' => $childName,
                        'meta_description' => $childName . ' Category',
                        'meta_keywords' => strtolower($childName)
                    ]
                ]);
            }
        }
    }
}
