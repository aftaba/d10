<?php

namespace Drupal\wp_import_articles;

use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;
use Drupal\pathauto\PathautoState;


class CSVImport {

	
	public static function webformImport($data, &$context){
		$message = 'Creating Nodes...';
		$results = array();

	    $field_category = [];
		if( isset( $data["taxonomy"]["category"] ) ) {
			foreach( $data["taxonomy"]["category"] as $cat ) {
				$t_id = CSVImport::getTidByName( $cat,"category");
				$field_category[] = ['target_id' => $t_id ];
			}
		}

		$field_tag = [];
		if( isset( $data["taxonomy"]["post_tag"] ) ) {
			foreach( $data["taxonomy"]["post_tag"] as $cat ) {
				$t_id = CSVImport::getTidByName( $cat,"tags");
				$field_tag[] = ['target_id' => $t_id ];
			}
		}
		

		

		$field_image = 0;
		if ( isset( $data["field_image"] ) ) {
			$file_id = CSVImport::upload_file( $data["field_image"] );
			$field_image = ['target_id' => $file_id, 'alt' => $data["title"] ];
		}
		
		$created = strtotime($data["created"]);
		if( $created < 0 ) {
			$created = strtotime("1970-01-01 12:00:00");
		}

		// get node by title
		$nids = \Drupal::entityQuery('node') ->condition('title', $data["title"] ) ->sort('nid', 'DESC') ->execute();

		if( count( $nids) > 0 ) {
			// get first item from array
			$nid = reset($nids);
			// update node id taxonomy only
			$node = Node::load($nid);
			$node->field_tags = $field_tag;
			$node->field_category = $field_category;
			$node->save();

		} else {

			if( isset( $data["title"] ) && $data["title"] != "" ) {
				$node = Node::create([
					'type'                        => "article",
					'title'                       => htmlspecialchars_decode( substr($data["title"],0,255) ),
					'body'                        => ['value' => preg_replace('/<!--(.|\s)*?-->/', '', $data["content"]), 'format' => 'full_html'],
					'field_category'  			  => $field_category,
					'field_tags'  			  	  => $field_tag,
					'field_image' 				  => $field_image, 
					'created'             		  => $created,
					'changed'					  => $created,
					'path' 						  => [
					    'alias' => '/'.substr($data["url_alias"],0,127),
				  	],
				]); 
				$node->save();
			}
		}
		$context['message'] = $message;
		$context['results'] = $results;
	}

	public static function upload_file( $file ) {
		$id=0;
		$file = str_replace( 'http://', 'https://', $file );
		$arrContextOptions=array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);

		$source = file_get_contents( $file, false, stream_context_create($arrContextOptions));
		//$source = file_get_contents( $file );
		
		if( $source !== false AND !empty($source) ) {
			$filename = basename( $file );
			$file = file_save_data($source, 'public://'.$filename, 1);
			$id = $file->id();
		} else {
			\Drupal::logger('xml_document2')->notice( 'File not found'.$file );
		}

		
		return $id;
	}

	public static function getTidByName($name = NULL, $vid = NULL) {
		
		$properties = [];
		if (!empty($name)) {
			$properties['name'] = $name;
		}
		if (!empty($vid)) {
			$properties['vid'] = $vid;
		}
		$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties($properties);
		$term = reset($terms);

		return !empty($term) ? $term->id() : 0;
	}


	public static function ringlistImportFinishedCallback($success, $results, $operations) {
		if ($success) {
			$message = \Drupal::translation()->formatPlural(
				count($results),
				'One post processed.', '@count posts processed.'
			);
		}
		else {
			$message = t('Finished with an error.');
		}
    	
	}
}
