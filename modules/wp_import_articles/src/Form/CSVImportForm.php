<?php
namespace Drupal\wp_import_articles\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Class AuthorUpdateForm.
 *
 * @package Drupal\wp_import_articles\Form
 */
class CSVImportForm extends FormBase {
	/**
	* {@inheritdoc}
	*/
	public function getFormId() {
		return 'csv_paragraph_form';
	}
  	

  	/**
   	* {@inheritdoc}
   	*/
	public function buildForm(array $form, FormStateInterface $form_state) {

		$form['field_name'] = [
			'#title' => $this->t('Select field'),
			'#type' => 'select',
			'#options' => [
				'hello' => $this->t('Start'),
			],
		];
		$form['submit'] = [
			'#type' => 'submit',
			'#value' => 'Import',
		];
		return $form;
	}
	
	/**
	* {@inheritdoc}
	*/
  	public function submitForm(array &$form, FormStateInterface $form_state) {

	  	$batch = [
	  		'title' => t('Importing XML...'),
	  		'operations' => [],
	  		'init_message' => t('Creating...'),
	  		'progress_message' => t('Processed @current out of @total.'),
	  		'error_message' => t('An error occurred during processing'),
	  		'finished' => '\Drupal\wp_import_articles\CSVImport::ringlistImportFinishedCallback',
	  	];
	  	$data_send = [];

	  	$data = CSVImportForm::getXMLData();
	  	$post_tag = array_unique($data["all_category"]["post_tag"]);
		$category = array_unique($data["all_category"]["category"]);
	  	
		CSVImportForm::insertTerm( $post_tag, "tags");
		CSVImportForm::insertTerm( $category, "category");
		
	 	//CSVImportForm::deleteArticles();
		CSVImportForm::bulkInsertArticle( $data );
	  	
  	}

  	public static function deleteArticles(){
  		$storage_handler = \Drupal::entityTypeManager()->getStorage("node");
	    $entities = $storage_handler->loadByProperties(["type" => "article"]);
	    $storage_handler->delete($entities);

  	}

  	public static function bulkInsertArticle( $data){
  		unset( $data["all_category"] );
  		foreach ( $data as $row ) {
	  		$batch['operations'][] = [
	  			'\Drupal\wp_import_articles\CSVImport::webformImport',
	  			[ $row ],
	  		];
	  	}
	  		
	  	batch_set($batch);
  	}

  	

  	public static function insertTerm( $term_array, $vid ) {

  		foreach( $term_array as $term ) {
			if ( ! CSVImportForm::doesTermExist( $term, $vid ) ) {
			 	\Drupal\taxonomy\Entity\Term::create([
					'name' => $term, 
					'vid' => $vid,
				])->save();
			}
		}
  	}

  	public static function doesTermExist($name = NULL, $vid = NULL) {
		
		$properties = [];
		if (!empty($name)) {
			$properties['name'] = $name;
		}
		if (!empty($vid)) {
			$properties['vid'] = $vid;
		}
		$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties($properties);
		$term = reset($terms);


		return !empty($term) ? true : false;
	}

  	public static function deleteTerm(){

  		$vids = ["field_tags", "field_category"];
  		foreach( $vids as $vid ) {
			$tids = \Drupal::entityQuery('taxonomy_term')
			->condition('vid', $vid)
			->execute();

			$term_storage = \Drupal::entityTypeManager()
			->getStorage('taxonomy_term');
			$entities = $term_storage->loadMultiple($tids);

			$term_storage->delete($entities);
		}

  	}

  	public static function getXMLData() {


		$feed_url = __DIR__.'/article.xml';
		$content = file_get_contents($feed_url);
		$xml = preg_replace('~(</?|\s)([a-z0-9_]+):~is', '$1$2_', $content);

		$xml = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);

		
		$attachment = [];
		foreach ( $xml->channel->item as  $item ) {
			$post_type = $item->wp_post_type->__toString();;
			
			$key = $item->wp_post_id->__toString();
			if ( "attachment" == $post_type ) {
				$attachment[$key] = $item->wp_attachment_url->__toString();
			}
		}
		
		foreach ( $xml->channel->item as  $item ) {
			
			$post_type = $item->wp_post_type->__toString();;
			
			$key = $item->wp_post_id->__toString();
			
			
			if ( "post" == $post_type ) {
				
				$t = $item->title->__toString();
				$t = str_replace('`', '', $t );
				$t = str_replace('’', '', $t );
				$result[$key]["title"] = $t;
				$result[$key]["content"] = $item->content_encoded->__toString();
				$result[$key]["url_alias"] = $item->wp_post_name->__toString();
				
				$result[$key]["created"] = str_replace( "_",":",$item->wp_post_date->__toString() );

				foreach( $item->wp_postmeta as $post_meta ) {
					if( $post_meta->wp_meta_key == "_thumbnail_id" ) {
						if(  $post_meta->wp_meta_value ) {
							$att_id = $post_meta->wp_meta_value->__toString();
							$result[$key]["field_image"] = $attachment[$att_id];
						}
					}
					
				}
				
				foreach( $item->category as $cat ) {
					$domain = $cat->attributes()->domain->__toString();

					$cat_item = json_decode(json_encode($cat), TRUE);
					$cat_name = trim($cat_item[0] );
					if( $domain == "category" || $domain == "post_tag" ) {
						$result[$key]["taxonomy"][$domain][] = $cat_name;
						$result["all_category"][$domain][] = $cat_name;
					}
				}
			}

		}
		return $result;
  	}


}