<?php

if ( ! function_exists( 'wp_generate_attachment_metadata' ) )
{
    require ABSPATH . 'wp-admin/includes/image.php';
}

/**
* Plugin Name: auto-scraping
* Plugin URI: https://example.com/
* Description: Auto scraping.
* Version: 0.1
* Author: adrian
* Author URI: https://example.com/
**/

$scrapingServerAddress = '138.201.121.253';
function addTermRelationships($id)
{
  $termRelationships = [
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2073",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2072",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2074",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2092",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2093",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2094",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2096",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2100",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2104",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2108",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2313",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2362",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2374",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2390",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2391",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2393",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2395",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2398",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2403",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2405",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2427",
        "term_order" => "0"
    ],
    [
        "object_id" => $id,
        "term_taxonomy_id" => "2452",
        "term_order" => "0"
    ]
  ];

  global $wpdb;
  for ($i = 1; $i < count($termRelationships); $i++) {
      $wpdb->insert($wpdb->term_relationships, $termRelationships[$i]);
  }
}

function getScrapingData($url)
{
    $response = wp_remote_get($url, [
        'timeout' => 60
    ]);
    if (!is_wp_error($response)) {
        return json_decode($response['body'], true);
    }

    if (ini_get('allow_url_fopen')) {
        return json_decode(file_get_contents($url), true);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, true);
}

function addScapingMedia($imgUrls, $pageNum)
{
    write_log('-((((((scraping media-----------------------');
    try {
        $upload_dir = wp_upload_dir();
    $save_path = $upload_dir['basedir'] . '/';
  $media = [];
  foreach ($imgUrls as $imgUrl) {
    $file_path = explode('/', $imgUrl);
    $date=date_create();
    $imageTitle = explode('.', $file_path[count($file_path) - 1]);
    array_pop($imageTitle);
    $imageName = substr($file_path[count($file_path) - 1], 0, -4);
    $media[] = [
      "attachment" => [
        "post_title" => implode(' ', $imageTitle),
        "post_status" => "inherit",
        "post_type" => "attachment",
        "post_mime_type" => "image/jpeg",
        "guid" => 'http://localhost/wp-content/uploads/' . $pageNum . '/' . $file_path[count($file_path) - 1],
        "post_author" => "1",
        "post_date" => date_format($date,"Y/m/d H:i:s"),
        "post_date_gmt" => date_format($date,"Y/m/d H:i:s"),
        "post_content" => "",
        "post_excerpt" => "",
        "comment_status" => "open",
        "ping_status" => "closed",
        "post_password" => "",
        "post_name" => createUrlSlug(implode(' ', $imageTitle)),
        "to_ping" => "",
        "pinged" => "",
        "post_modified" => date_format($date,"Y/m/d H:i:s"),
        "post_modified_gmt" => date_format($date,"Y/m/d H:i:s"),
        "post_content_filtered" => "",
        "post_parent" => "0",
        "menu_order" => "0",
        "comment_count" => "0"
      ],
      "attachment_meta" => [
        [
          "meta_key" => "_wp_attached_file",
          "meta_value" => $pageNum . '/' . $file_path[count($file_path) - 1]
        ],
        [
          "meta_key" => "_wp_attachment_metadata",
          "meta_value" => "a:5:{s:5:\"width\";i:1024;s:6:\"height\";i:800;s:4:\"file\";s:13:\"" . $pageNum . '/' . $file_path[count($file_path) - 1] . "\";s:5:\"sizes\";a:19:{s:6:\"medium\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-300x234.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:234;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:9:\"thumbnail\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:12:\"medium_large\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-768x600.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:600;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:15:\"vehica_167.5_93\";a:4:{s:4:\"file\";s:12:\"" . $imageName . "-167x93.jpg\";s:5:\"width\";i:167;s:6:\"height\";i:93;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"vehica_335_186\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-335x186.jpg\";s:5:\"width\";i:335;s:6:\"height\";i:186;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"vehica_670_372\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-670x372.jpg\";s:5:\"width\";i:670;s:6:\"height\";i:372;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"vehica_100_100\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-100x100.jpg\";s:5:\"width\";i:100;s:6:\"height\";i:100;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"vehica_336_284\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-336x284.jpg\";s:5:\"width\";i:336;s:6:\"height\";i:284;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:14:\"vehica_672_568\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-336x284.jpg\";s:5:\"width\";i:336;s:6:\"height\";i:284;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"vehica_165\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-165x129.jpg\";s:5:\"width\";i:165;s:6:\"height\";i:129;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_335_186\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-335x186.jpg\";s:5:\"width\";i:335;s:6:\"height\";i:186;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_670_372\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-670x372.jpg\";s:5:\"width\";i:670;s:6:\"height\";i:372;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_812_585\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-812x585.jpg\";s:5:\"width\";i:812;s:6:\"height\";i:585;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_148_108\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-148x108.jpg\";s:5:\"width\";i:148;s:6:\"height\";i:108;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_335_402\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-335x402.jpg\";s:5:\"width\";i:335;s:6:\"height\";i:402;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_261_154\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-261x154.jpg\";s:5:\"width\";i:261;s:6:\"height\";i:154;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_592_401\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-592x401.jpg\";s:5:\"width\";i:592;s:6:\"height\";i:401;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_100_100\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-100x100.jpg\";s:5:\"width\";i:100;s:6:\"height\";i:100;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:10:\"mc_670_352\";a:4:{s:4:\"file\";s:13:\"" . $imageName . "-670x352.jpg\";s:5:\"width\";i:670;s:6:\"height\";i:352;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:12:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}}}"
        ],
      ]
    ];
  }
  global $wpdb;
  $imageIds = [];
  for ($i = 1; $i < count($media); $i++) {
    $attachment = $media[$i]['attachment'];
    $attachment_meta = $media[$i]['attachment_meta'];
    $check = $wpdb->insert($wpdb->posts, $attachment);
    $lastid = $wpdb->insert_id;
    $imageIds[] = $lastid;
    if (!$check) {
        echo $wpdb->last_error;
        continue;
    }

    foreach ($attachment_meta as $meta) {
        write_log('.......................................' . $lastid);

      if ($meta['meta_key'] === '_wp_attached_file') {

          $name = $save_path . $meta['meta_value'];
          $source = 'http://' . '138.201.121.253' . '/images/' . $meta['meta_value'];

        write_log('----attachment----');
        write_log($name);
        write_log($source);

          $dir = dirname($name);
          if (!is_dir($dir)) {
        write_log('----mkdir----');
              mkdir($dir, 0777, true);
          }
          $response = wp_remote_get($source, [
              'timeout' => 60
          ]);

          if (is_wp_error($response)) {
        write_log($response->get_error_message());

              echo $response->get_error_message();
          }
        write_log('----attachment-response----');

          $file = $response['body'];
          file_put_contents($name, $file);
        write_log('----save----');
          $metadata = wp_generate_attachment_metadata($lastid, $name);
        write_log('----generate----');
          wp_update_attachment_metadata($lastid, $metadata);
        write_log('----update----');
      }
      $meta['post_id'] = $lastid;
      $wpdb->insert($wpdb->postmeta, $meta);
        write_log('----insert-meta----');
    }
  }
    } catch (Exception $e) {
    write_log('Caught exception: ');
    }
    
  return $imageIds;
}

function addCustomField($postData) {
  global $wpdb;

  $post = $postData['post'];
  $post_meta = $postData['post_meta'];

  $wpdb->insert($wpdb->posts, $post);
  $lastid = $wpdb->insert_id;

  if (is_array($post_meta)) {
      foreach ($post_meta as $key => $meta) {
          $meta['post_id'] = $lastid;
          $wpdb->insert(
              $wpdb->postmeta,
              $meta
          );
      }
  }
  return $lastid;
}

function createUrlSlug($urlString)
{
  $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
  if (substr($slug, -1) == '-')
    $slug = substr($slug, 0, -1);
  return strtolower($slug);
}

function addScrapingPosts()
{
    // return 'success';
    global $wpdb;
    for (;;) {
        $datas = getScrapingData('http://' . '138.201.121.253' . '/api/getVehicleList/scraping');

        $posts = $datas['list'];
        if (count($posts) == 0) {
            break;
        }
        $count = 0;
        for ($i = 0; $i < count($posts); $i++) {
            write_log('-------------------------------------------');
            $post = $posts[$i]['post'];
            write_log('post_title: ' . $post['post_title']);
            $post_meta = $posts[$i]['post_meta'];
            $post_meta[] = [
            "label" => "Condition",
            "value" => "New"
            ];
            $postExistsResult = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='vehica_car' ORDER BY ID DESC LIMIT 1" , $post['post_title']));
            if ($postExistsResult && $post['deleted']) {
                wp_delete_post( $postExistsResult );
                write_log('//////////////////////');
                continue;
            }
            // if ($count ==)
            $count ++;
            // if (in_array($post['ID'], $exclude, true)) {
            //     continue;
            // }

            // if (!$this->officialDemo) {
            //     $post['post_author'] = get_current_user_id();
            // }
            $imgUrls = addScapingMedia($post['images'], $post['page_num']);
            $date=date_create();

            $wpdb->insert($wpdb->posts, [
            "post_author" => 1,
            "post_content" => $post['post_content'],
            "post_title" => $post['post_title'],
            "post_status" => $post['post_status'],
            "post_type" => $post['post_type'],
            "post_date" => date_format($date,"Y/m/d H:i:s"),
            "post_date_gmt" => date_format($date,"Y/m/d H:i:s"),
            "comment_status" => "closed",
            "ping_status" => "closed",
            "post_password" => "",
            "to_ping" => "",
            "pinged" => "",
            "post_modified" => date_format($date,"Y/m/d H:i:s"),
            "post_content_filtered" => "",
            "post_parent" => "0",
            "menu_order" => "0",
            "post_mime_type" => "",
            "comment_count" => "0",
            "post_modified_gmt" => date_format($date,"Y/m/d H:i:s"),
            "post_name" => createUrlSlug($post['post_title']),
            "post_excerpt" => ""
            ]);
            $lastid = $wpdb->insert_id;
            addTermRelationships($lastid);
            if (is_array($post_meta)) {
                write_log('---post-meta-----------------------');
                foreach ($post_meta as $key => $value) {
                    $meta = [
                        "meta_value" => $value['value']
                    ];
                    
                    $postResult = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='vehica_custom_field' ORDER BY ID DESC LIMIT 1" , $value['label']));
                    if ($postResult) {
                        $meta['meta_key'] = 'vehica_' . $postResult;
                        if ($value['label'] == 'Model' || $value['label'] == 'Make') {
                            $termsValue = [
                                "name" => $value['value'],
                                "slug" => sanitize_title($value['value']),
                                "term_group" => "0"
                            ];
                            $termsResult = $wpdb->get_var( $wpdb->prepare("SELECT {$wpdb->terms}.term_id FROM $wpdb->terms INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id= {$wpdb->term_taxonomy}.term_id WHERE {$wpdb->terms}.name = %s AND {$wpdb->term_taxonomy}.taxonomy = %s ORDER BY {$wpdb->terms}.term_id DESC LIMIT 1" , $value['value'], 'vehica_' . $postResult));

                            if (!$termsResult) {
                                $wpdb->insert($wpdb->terms, $termsValue);
                                $insertTermsId = $wpdb->insert_id;

                                if ($value['label'] == 'Model') {
                                    $termsMakeResult = $wpdb->get_var( $wpdb->prepare("SELECT {$wpdb->terms}.term_id FROM $wpdb->terms INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id= {$wpdb->term_taxonomy}.term_id WHERE {$wpdb->terms}.name = %s AND {$wpdb->term_taxonomy}.taxonomy = %s ORDER BY {$wpdb->terms}.term_id DESC LIMIT 1" , $value['make'], 'vehica_' . $postResult));
                                    $termmetaValue = [[
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_label_text_color",
                                        "meta_value" => "#000000"
                                    ],
                                    [
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_label_background_color",
                                        "meta_value" => "default"
                                    ],
                                    [
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_parent_term",
                                        "meta_value" => "a:1:{i:0;i:" . $termsMakeResult . ";}"
                                    ],
                                    [
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_alias",
                                        "meta_value" => $value['make'] . " " . $value['value']
                                    ]];
                                } else {
                                    $termmetaValue = [[
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_parent_term",
                                        "meta_value" => "a:1:{i:0;i:2452;}"
                                    ],
                                    [
                                        "term_id" => $insertTermsId,
                                        "meta_key" => "vehica_alias",
                                        "meta_value" => "Car " . $value['value']
                                    ]];
                                }

                                for ($index = 0; $index < count($termmetaValue); $index++) {
                                    $wpdb->insert($wpdb->termmeta, $termmetaValue[$index]);
                                }
                            } else {
                                $insertTermsId = $termsResult;
                            }
                            $termTaxonomyResult = $wpdb->get_var( $wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = %s AND taxonomy = %s ORDER BY term_taxonomy_id DESC LIMIT 1" , $insertTermsId, 'vehica_' . $postResult));

                            if ($termTaxonomyResult) {
                                $wpdb->query("UPDATE $wpdb->term_taxonomy SET count=count+1 WHERE term_taxonomy_id = $termTaxonomyResult");
                                $insertTermTaxonomyId = $termTaxonomyResult;
                            } else {
                                $termTaxonomy = [
                                    "term_id" => $insertTermsId,
                                    "taxonomy" => 'vehica_' . $postResult,
                                    "description" => "",
                                    "parent" => "0",
                                    "count" => "1"
                                ];

                                $wpdb->insert($wpdb->term_taxonomy, $termTaxonomy);
                                $insertTermTaxonomyId = $wpdb->insert_id;
                            }

                            $termRelationshipsResult = $wpdb->get_var( $wpdb->prepare("SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %s AND object_id = %s ORDER BY object_id DESC LIMIT 1" , $insertTermTaxonomyId, $lastid));

                            if (!$termRelationshipsResult) {
                                $termRelationship = [
                                    "object_id" => $lastid,
                                    "term_taxonomy_id" => $insertTermTaxonomyId,
                                    "term_order" => "0"
                                ];
                                $wpdb->insert($wpdb->term_relationships, $termRelationship);
                            }

                            continue;
                        }
                    } else {
                        $date=date_create();
                        $customField = [
                        "post" => [
                            "post_title" => $value['label'],
                            "post_status" => "publish",
                            "post_type" => "vehica_custom_field",
                            "post_author" => "1",
                            "post_date" => date_format($date,"Y/m/d H:i:s"),
                            "post_date_gmt" => date_format($date,"Y/m/d H:i:s"),
                            "post_content" => "",
                            "post_excerpt" => "",
                            "comment_status" => "closed",
                            "ping_status" => "closed",
                            "post_password" => "",
                            "post_name" => createUrlSlug($value['label']),
                            "to_ping" => "",
                            "pinged" => "",
                            "post_modified" => date_format($date,"Y/m/d H:i:s"),
                            "post_modified_gmt" => date_format($date,"Y/m/d H:i:s"),
                            "post_content_filtered" => "",
                            "post_parent" => "0",
                            "menu_order" => "0",
                            "post_mime_type" => "",
                            "guid" => "http://localhost/vehica_custom_field/" . createUrlSlug($value['label']) . "/",
                            "comment_count" => "0"
                        ],
                        "post_meta" => [
                            [
                                "meta_key" => "vehica_type",
                                "meta_value" => "text"
                            ],
                            [
                                "meta_key" => "vehica_object_type",
                                "meta_value" => "vehica_object_type_car"
                            ]
                        ]
                        ];
                        $meta['meta_key'] = 'vehica_' . addCustomField($customField);
                    }
                    
                    $meta['post_id'] = $lastid;
                    if ($meta['meta_key'] === '_menu_item_url' && $post['post_type'] === 'nav_menu_item') {
                        // $meta['meta_value'] = str_replace($this->getDemoUrl(), site_url(), $meta['meta_value']);
                    }
                    if ($meta['meta_key'] == 'vehica_6673') {
                        
                        write_log('post_images: ' . implode(',', array_unique($imgUrls)));
                        $meta['meta_value'] = implode(',', array_unique($imgUrls));
                    }

                    if ($meta['meta_key'] == 'vehica_6656') {
                        $meta['meta_key'] = "vehica_currency_6656_2316";
                    }

                    $wpdb->insert(
                        $wpdb->postmeta,
                        $meta
                    );
                }
                write_log('----post-meta--end-----------');
            }
            write_log('================================');
        }

        if (count($posts) < 10) {
            break;
        }
    }
}

add_action('rest_api_init', function () {
    register_rest_route('secret/v1', '/scraping/', [
        'methods' => ['GET'],
        'callback' => 'addScrapingPosts',
        'permission_callback' => '__return_true',
    ]);
});

function cronAddScrapingPosts() {
	if (!function_exists('write_log')) {

		function write_log($log) {
			if (true === WP_DEBUG) {
				if (is_array($log) || is_object($log)) {
					error_log(print_r($log, true));
				} else {
					error_log($log);
				}
			}
		}

	}

	write_log('THIS IS THE START OF MY CUSTOM DEBUG');
    
    //addScrapingPosts();
}

function repairVehicleTerms() {
    
    $args = array('post_type' => 'vehica_car', 'numberposts' => -1);
    global $wpdb;
    $posts = get_posts($args);

    $makeList = [["id" => "137",
            "name" => "Abarth",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "5",
            "name" => "Alfa Romeo",
            "selected" => true,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "70",
            "name" => "Aston Martin",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "1",
            "name" => "Audi",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "54",
            "name" => "Austin",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "46",
            "name" => "Bentley",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "2",
            "name" => "BMW",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "42",
            "name" => "Chevrolet",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "3",
            "name" => "Chrysler",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "4",
            "name" => "Citroen",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "297999814",
            "name" => "Cupra",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "793",
            "name" => "Dacia",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "34",
            "name" => "Daihatsu",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "48",
            "name" => "Dodge",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "170978731",
            "name" => "DS",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "43",
            "name" => "Ferrari",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "6",
            "name" => "Fiat",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "7",
            "name" => "Ford",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "116307351",
            "name" => "Great Wall",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "8",
            "name" => "Honda",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "9",
            "name" => "Hyundai",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "799",
            "name" => "Infiniti",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "10",
            "name" => "Isuzu",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "11",
            "name" => "Jaguar",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "12",
            "name" => "Jeep",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "13",
            "name" => "Kia",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "65",
            "name" => "Lamborghini",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "14",
            "name" => "Land Rover",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "36",
            "name" => "Lexus",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "51",
            "name" => "Maserati",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "15",
            "name" => "Mazda",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "167026535",
            "name" => "McLaren",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "16",
            "name" => "Mercedes",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "17",
            "name" => "MG",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "18",
            "name" => "MINI",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "19",
            "name" => "Mitsubishi",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "20",
            "name" => "Nissan",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "21",
            "name" => "Opel",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "22",
            "name" => "Peugeot",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "311279897",
            "name" => "Polestar",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "23",
            "name" => "Porsche",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "24",
            "name" => "Renault",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "44",
            "name" => "Rolls-Royce",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "26",
            "name" => "SAAB",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "27",
            "name" => "Seat",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "28",
            "name" => "Skoda",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "47",
            "name" => "Smart",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "41",
            "name" => "SsangYong",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "40",
            "name" => "Subaru",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "29",
            "name" => "Suzuki",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "807",
            "name" => "TESLA",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "30",
            "name" => "Toyota",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "50",
            "name" => "TVR",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "31",
            "name" => "Vauxhall",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "32",
            "name" => "Volkswagen",
            "selected" => false,
            "extra" => null,
            "type" => 0
        ], [
            "id" => "33",
            "name" => "Volvo",
            "selected" => false,
            "extra" => null,
            "type" => 0
    ]];
    
    $wpdb->query("DELETE terms FROM $wpdb->terms AS terms INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON terms.term_id = term_taxonomy.term_id WHERE term_taxonomy.taxonomy = 'vehica_6659'");
    $wpdb->query("DELETE terms FROM $wpdb->terms AS terms INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON terms.term_id = term_taxonomy.term_id WHERE term_taxonomy.taxonomy = 'vehica_6660'");
    $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = 'vehica_6659'");
    $wpdb->query("DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = 'vehica_6660'");
    $wpdb->query("DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_taxonomy)");
    $wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key = 'vehica_label_text_color' AND meta_value = '#000000'");
    $wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key = 'vehica_label_background_color' AND meta_value = 'default'");
    $wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key = 'vehica_parent_term' AND meta_value LIKE 'a:1:{i:0;i:%'");
    $wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key = 'vehica_alias'");

    foreach($makeList as $eachMake) {
        $termMake = [
            "name" => $eachMake['name'],
            "slug" => sanitize_title($eachMake['name']),
            "term_group" => "0"
        ];

        $termsMakeResult = $wpdb->get_var( $wpdb->prepare("SELECT {$wpdb->terms}.term_id FROM $wpdb->terms INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id= {$wpdb->term_taxonomy}.term_id WHERE {$wpdb->terms}.name = %s AND {$wpdb->term_taxonomy}.taxonomy = %s ORDER BY {$wpdb->terms}.term_id DESC LIMIT 1" , $eachMake['name'], 'vehica_6659'));
        if ($termsMakeResult) {
            return;
        }

        $wpdb->insert($wpdb->terms, $termMake);
        $termInsertedId = $wpdb->insert_id;

        $termTaxonomy = [
            "term_id" => $termInsertedId,
            "taxonomy" => 'vehica_6659',
            "description" => "",
            "parent" => "0",
            "count" => "0"
        ];

        $wpdb->insert($wpdb->term_taxonomy, $termTaxonomy);
        $termTaxonomyInsertedId = $wpdb->insert_id;

        $termmetaValues = [[
            "term_id" => $termInsertedId,
            "meta_key" => "vehica_parent_term",
            "meta_value" => "a:1:{i:0;i:2452;}"
        ],
        [
            "term_id" => $termInsertedId,
            "meta_key" => "vehica_alias",
            "meta_value" => "Car " . $eachMake['name']
        ]];

        foreach ($termmetaValues as $eachTermmeta) {
            $wpdb->insert($wpdb->termmeta, $eachTermmeta);
        }

        foreach ($posts as $post) {
            if (str_contains($post->post_title, $eachMake['name'])) {
                $termRelationship = [
                    "object_id" => $post->ID,
                    "term_taxonomy_id" => $termTaxonomyInsertedId,
                    "term_order" => "0"
                ];

                $wpdb->insert($wpdb->term_relationships, $termRelationship);

                $wpdb->query("UPDATE $wpdb->term_taxonomy SET count=count+1 WHERE term_taxonomy_id=$termTaxonomyInsertedId)");

                $temp = substr($post->post_title, strpos($post->post_title, $eachMake['name']) + strlen($eachMake['name']) + 1);

                if (strpos($temp, '  ') === false) $postModel = $temp;
                else $temp = substr($temp, 0, strpos($temp, '  '));

                if (strpos($temp, ' ') === false) $postModel = $temp;
                else $postModel = substr($temp, 0, strrpos($temp, ' '));
                if ($eachMake['name'] == 'Aston Martin') {
                    write_log('-=-=-=-=-=-====================');
                    write_log($post->post_title);
                    $temp1 = substr($post->post_title, strpos($post->post_title, $eachMake['name']) + strlen($eachMake['name']) + 1);
                    write_log($temp1);
                    $temp1 = substr($temp1, 0, strpos($temp1, '  '));
                    write_log($temp1);
                    if (strpos($temp1, ' ') === false) $postModel1 = $temp1;
                    else $postModel1 = substr($temp1, 0, strrpos($temp1, ' '));
                    write_log($postModel1);
                    write_log('-eeeeeeeeeeeeeeeeee============');
                }
                $termModel = [
                    "name" => $postModel,
                    "slug" => sanitize_title($postModel),
                    "term_group" => "0"
                ];

                $termsResult = $wpdb->get_var( $wpdb->prepare("SELECT {$wpdb->terms}.term_id FROM $wpdb->terms INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id= {$wpdb->term_taxonomy}.term_id WHERE {$wpdb->terms}.name = %s AND {$wpdb->term_taxonomy}.taxonomy = %s ORDER BY {$wpdb->terms}.term_id DESC LIMIT 1" , $postModel, 'vehica_6660'));

                $termmetaResult = $termsResult ? $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->termmeta WHERE term_id = %s AND meta_value='a:1:{i:0;i:" . $termInsertedId . ";}' ORDER BY term_id DESC LIMIT 1", $termsResult)) : null;

                if ($termsResult && $termmetaResult) {
                    $termModelInsertedId = $termsResult;
                } else {
                    $wpdb->insert($wpdb->terms, $termModel);
                    $termModelInsertedId = $wpdb->insert_id;

                    $termmetaModelValues = [[
                        "term_id" => $termModelInsertedId,
                        "meta_key" => "vehica_label_text_color",
                        "meta_value" => "#000000"
                    ],
                    [
                        "term_id" => $termModelInsertedId,
                        "meta_key" => "vehica_label_background_color",
                        "meta_value" => "default"
                    ],
                    [
                        "term_id" => $termModelInsertedId,
                        "meta_key" => "vehica_parent_term",
                        "meta_value" => "a:1:{i:0;i:" . $termInsertedId . ";}"
                    ],
                    [
                        "term_id" => $termModelInsertedId,
                        "meta_key" => "vehica_alias",
                        "meta_value" => $eachMake['name'] . " " . $postModel
                    ]];

                    foreach ($termmetaModelValues as $eachTermmetaModel) {
                        $wpdb->insert($wpdb->termmeta, $eachTermmetaModel);
                    }
                }

                $termModelTaxonomy = [
                    "term_id" => $termModelInsertedId,
                    "taxonomy" => 'vehica_6660',
                    "description" => "",
                    "parent" => "0",
                    "count" => "1"
                ];

                $termTaxonomyResult = $wpdb->get_var( $wpdb->prepare("SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE term_id = %s AND taxonomy = %s ORDER BY term_taxonomy_id DESC LIMIT 1" , $termModelInsertedId, 'vehica_6660'));

                if ($termTaxonomyResult) {
                    $wpdb->query("UPDATE $wpdb->term_taxonomy SET count=count+1 WHERE term_taxonomy_id = $termTaxonomyResult");
                    $termModelTaxonomyInsertedId = $termTaxonomyResult;
                } else {
                    $wpdb->insert($wpdb->term_taxonomy, $termModelTaxonomy);
                    $termModelTaxonomyInsertedId = $wpdb->insert_id;
                }

                $termRelationshipsResult = $wpdb->get_var( $wpdb->prepare("SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %s AND object_id = %s ORDER BY object_id DESC LIMIT 1" , $termModelTaxonomyInsertedId, $post->ID));

                if (!$termRelationshipsResult) {
                    $termModelRelationship = [
                        "object_id" => $post->ID,
                        "term_taxonomy_id" => $termModelTaxonomyInsertedId,
                        "term_order" => "0"
                    ];
                    $wpdb->insert($wpdb->term_relationships, $termModelRelationship);
                }
            }
        }
    }
    
    // $termsResult = $wpdb->get_results("
    //     SELECT * FROM $wpdb->terms AS terms INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON terms.term_id = term_taxonomy.term_id WHERE term_taxonomy.taxonomy = 'vehica_6659' GROUP BY terms.name
    // ");

    // foreach($termsResult as $eachTerm) {

    //     $term_id = $wpdb->get_var( $wpdb->prepare("SELECT {$wpdb->terms}.term_id FROM $wpdb->terms INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->terms}.term_id= {$wpdb->term_taxonomy}.term_id WHERE {$wpdb->terms}.name = %s AND {$wpdb->term_taxonomy}.taxonomy = %s ORDER BY {$wpdb->terms}.term_id DESC LIMIT 1" , $eachTerm->name, 'vehica_6659'));

    //     $term_id_and_taxonomys = $wpdb->get_results("SELECT terms.term_id AS term_id, term_taxonomy.term_taxonomy_id AS term_taxonomy_id FROM $wpdb->terms AS terms INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON terms.term_id = term_taxonomy.term_id WHERE term_taxonomy.taxonomy = 'vehica_6659' AND terms.name = '" . $eachTerm->name . "' AND terms.term_id != " . $eachTerm->term_id . "");

    //     $term_ids = ['0'];
    //     $term_taxonomys = ['0'];

    //     foreach($term_id_and_taxonomys as $eachTermIdAndTaxonomy) {
    //         $term_ids[] = $eachTermIdAndTaxonomy->term_id;
    //         $term_taxonomys[] = $eachTermIdAndTaxonomy->term_taxonomy_id;
    //     }

    //     $wpdb->query("UPDATE $wpdb->term_taxonomy AS term_taxonomy SET term_taxonomy.count = term_taxonomy.count + ( SELECT COUNT(*) FROM $wpdb->term_relationships AS term_relationships  WHERE term_relationships.term_taxonomy_id IN (SELECT term_taxonomy.term_taxonomy_id FROM $wpdb->term_taxonomy AS term_taxonomy WHERE term_taxonomy.term_taxonomy_id IN (SELECT term_taxonomy.term_taxonomy_id FROM $wpdb->terms AS terms INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON terms.`term_id` = term_taxonomy.term_id WHERE term_taxonomy.taxonomy = 'vehica_6659' AND terms.`name` = '" . $eachTerm->name . "'))) WHERE term_taxonomy.term_id = " . $eachTerm->term_id . " AND term_taxonomy.taxonomy = 'vehica_6659'");

    //     $wpdb->query("UPDATE $wpdb->term_relationships AS term_relationships SET term_relationships.term_taxonomy_id = " . $eachTerm->term_id . " WHERE term_relationships.term_taxonomy_id IN (" . implode(',', $term_taxonomys) . ")");

    //     $wpdb->query("UPDATE $wpdb->termmeta AS termmeta SET termmeta.meta_value = 'a:1:{i:0;i:" . $term_id . ";}' WHERE termmeta.meta_key = 'vehica_parent_term' termmeta.meta_value LIKE 'a:1:{i:0;i:" . $eachTerm->term_id . ";}'");

    //     $wpdb->query("DELETE FROM $wpdb->terms WHERE term_id IN (" . implode(',', $term_ids) . ")");

    //     $wpdb->query("DELETE FROM $wpdb->term_taxonomy AS term_taxonomy WHERE term_taxonomy.term_taxonomy_id IN (" . implode(',', $term_taxonomys) . ")");

    //     $wpdb->query("DELETE FROM $wpdb->termmeta AS termmeta WHERE termmeta.term_id IN (" . implode(',', $term_ids) . ")");
    // }
}

function clearVehicleList() {
    // $args = array('post_type' => 'vehica_car', 'numberposts' => -1);
    // $posts = get_posts($args);
	// for ($i = 0; $i < count($posts); $i ++) {
    //     wp_delete_post( $posts[$i]->ID );
    // }
}

add_action('cronAddScrapingPosts', 'cronAddScrapingPosts');

add_action('repairVehicleTerms', 'repairVehicleTerms');
add_action('clearVehicleList', 'clearVehicleList');

add_filter( 'cron_schedules', function ( $schedules ) {
   $schedules['per_4_minute'] = array(
       'interval' => 240,
       'display' => __( '4 Minutes' )
   );

   $schedules['per_year'] = array(
       'interval' => 365 * 24 * 60 * 60,
       'display' => __( 'Per Year' )
   );
   return $schedules;
} );

add_action('admin_init', static function () {
    if (!wp_next_scheduled('cronAddScrapingPosts')) {
        wp_schedule_event(time(), 'per_4_minute', 'cronAddScrapingPosts');
    }

    if (!wp_next_scheduled('clearVehicleList')) {
        wp_schedule_event(time(), 'per_year', 'clearVehicleList');
    }

    if (!wp_next_scheduled('repairVehicleTerms')) {
        wp_schedule_event(time(), 'per_year', 'repairVehicleTerms');
    }
});