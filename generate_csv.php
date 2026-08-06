<?php
$header = ['sku','parent_sku','locale','attribute_family_code','type','categories','images','name','description','short_description','status','visible_individually','new','featured','guest_checkout','length','width','height','weight','tax_category_name','price','cost','special_price','special_price_from','special_price_to','customer_group_prices','url_key','meta_title','meta_keywords','meta_description','manage_stock','inventories','related_skus','cross_sell_skus','up_sell_skus','configurable_variants','bundle_options','associated_skus'];

$rows = [
    ['JFC-TEE-CONF','','en','default','configurable','Mens','','JFC Signature T-Shirt','Our classic signature tee.','Classic signature tee',1,1,1,0,1,'','','',0.5,'','','','','','','','jfc-signature-t-shirt','Meta Title','meta1, meta2','meta description',0,'','','','','sku=JFC-TEE-BLK-M,color=Hitam,size=M|sku=JFC-TEE-BLK-L,color=Hitam,size=L|sku=JFC-TEE-BLK-XL,color=Hitam,size=XL','',''],
    ['JFC-TEE-BLK-M','JFC-TEE-CONF','en','default','simple','Mens','','JFC Signature T-Shirt - Black M','Our classic signature tee.','Classic signature tee',1,0,0,0,1,'','','',0.5,'',25.00,'','','','','','jfc-signature-t-shirt-black-m','Meta Title','meta1, meta2','meta description',1,'default=50','','','','','',''],
    ['JFC-TEE-BLK-L','JFC-TEE-CONF','en','default','simple','Mens','','JFC Signature T-Shirt - Black L','Our classic signature tee.','Classic signature tee',1,0,0,0,1,'','','',0.5,'',25.00,'','','','','','jfc-signature-t-shirt-black-l','Meta Title','meta1, meta2','meta description',1,'default=50','','','','','',''],
    ['JFC-TEE-BLK-XL','JFC-TEE-CONF','en','default','simple','Mens','','JFC Signature T-Shirt - Black XL','Our classic signature tee.','Classic signature tee',1,0,0,0,1,'','','',0.5,'',25.00,'','','','','','jfc-signature-t-shirt-black-xl','Meta Title','meta1, meta2','meta description',1,'default=50','','','','','','']
];

$fp = fopen('/home/mamy/projects/fashion-store/public/storage/catalog_test_import.csv', 'w');
fputcsv($fp, $header);
foreach ($rows as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
echo "CSV created.\n";
