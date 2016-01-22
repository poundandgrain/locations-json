<?php
class Locations_JSON_Command extends WP_CLI_Command {
	function __invoke(){

		$args = array(
			'post_type' => 'location',
			'posts_per_page' => -1,
			'orderby' => 'none',
			'fields' => 'ids', // only get the ids so we don't cache all the meta and taxonomy data.
			//'order' => 'ASC'
		);

		$location_query = new \WP_Query($args);
		//wp_die( print_r( $location_query->posts ) );
		$post_ids = $location_query->posts;
		if( ! empty( $post_ids ) ) {

			$locationsArray = array();

			foreach( $post_ids as $id ){
				$latlon = get_field("latitude_longitude", $id);

				if ($latlon){
					$address = get_field("address1", $id);
					if ( get_field("address2", $id) ){
						$address .= ", " . get_field("address2", $id);
					}
					$address .= ", " . get_field("city", $id);
					$address .= ", " . get_field("state", $id);
					$address .= ", " . get_field("country", $id);
					$address .= ", " . get_field("zip_code", $id);


					$locationsArray[] = array(
						//'distance' => $distance,
						//"icon" => get_template_directory_uri() . "/assets/images/map/map-marker.png",
						"lat" => $latlon['lat'],
						"lng" => $latlon['lng'],
						"title" => get_the_title( $id ),
						"address" => $address,
						"phone" => get_field('phone', $id),
						"url" => get_field('website_url', $id),
						"fb" => get_field('facebook_url', $id)
					);
				}
			}

			$locationCount = count($locationsArray);

			$upload_dir = wp_upload_dir();
			$fileName = $upload_dir['basedir'] .'/locations.txt';

			//this is the part returned
			$locationData = '[';
			for ($i=0; $i < $locationCount; $i++) {

				$locationData .= '{';
					//$locationData .= '"icon":"'.$locationsArray[$i]['icon'].'",';
					$locationData .= '"lat":"'.$locationsArray[$i]['lat'].'",';
					$locationData .= '"lng":"'.$locationsArray[$i]['lng'].'",';
					$locationData .= '"title":"'.$locationsArray[$i]['title'].'",';
					$locationData .= '"address":"'.$locationsArray[$i]['address'].'",';
					$locationData .= '"phone":"'.$locationsArray[$i]['phone'].'",';
					$locationData .= '"url":"'.$locationsArray[$i]['url'].'",';
					$locationData .= '"fb":"'.$locationsArray[$i]['fb'].'"';
				$locationData .= '}';
				$locationData .= ($locationCount-1 == $i ? "" : ",");

			}
			$locationData .= ']';

			// Write the contents back to the file
			file_put_contents($fileName, $locationData);

			// Print a success message
			WP_CLI::success( "Document has been generated and saved to " . $fileName );
		}
	}

}

WP_CLI::add_command( 'process_locations_json', 'Locations_JSON_Command' );
