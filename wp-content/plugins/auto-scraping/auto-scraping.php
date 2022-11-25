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
                            $termsResult = $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE name = %s ORDER BY term_id DESC LIMIT 1" , $value['value']));

                            if (!$termsResult) {
                                $wpdb->insert($wpdb->terms, $termsValue);
                                $insertTermsId = $wpdb->insert_id;

                                if ($value['label'] == 'Model') {
                                    $termsMakeResult = $wpdb->get_var( $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE name = %s ORDER BY term_id DESC LIMIT 1" , $value['make']));
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
                                $wpdb->query("UPDATE $wpdb->term_taxonomy SET count=count+1 WHERE term_taxonomy_id=$termTaxonomyResult)");
                                $insertTermTaxonomyId = $termTaxonomyResult;
                            } else {
                                $termTaxonomy = [
                                    "term_id" => $insertTermsId,
                                    "taxonomy" => 'vehica_' . $postResult,
                                    "description" => "",
                                    "parent" => "0",
                                    "count" => "0"
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

   addScrapingPosts();
}

function clearVehicleList() {
    $args = array('post_type' => 'vehica_car', 'numberposts' => -1);
    $posts = get_posts($args);
	for ($i = 0; $i < count($posts); $i ++) {
        wp_delete_post( $posts[$i]->ID );
    }
}

add_action('cronAddScrapingPosts', 'cronAddScrapingPosts');

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
});