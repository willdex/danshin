<?php

if(!function_exists('workreap_get_template_part')){
	add_action( 'get_template_part', 'workreap_get_template_part',10,4 );
	function workreap_get_template_part($slug, $name = null, $templates = array(), $args=array()){
		if(!empty($slug) && strpos($slug, "directory/") === 0 && !empty($templates[0]) ){
			if ( !file_exists( get_stylesheet_directory().'/' . $templates[0] ) ) {
				$template	= WORKREAPPLUGINPATH .$templates[0];
				if(file_exists( $template)){
					load_template($template, FALSE, $args);
				}
			}

		}
	}
}

add_filter( 'theme_page_templates', 'workreap_custom_page_templates' );
function workreap_custom_page_templates(){
	$templates['directory/dashboard.php']		= 'Dashboard';
	$templates['directory/full-width-template.php']		= 'Full width template';
	$templates['directory/full-single-post.php']		= 'Full Width Post';
	$templates['directory/project-search.php']	= 'Search Projects';
	$templates['directory/employer-search.php']	= 'search Employers';
	$templates['directory/freelancer-search.php']	= 'Search Freelancers';
	$templates['directory/portfolio-search.php']	= 'Search Portfolio';
	$templates['directory/project-proposal.php']	= 'Submit Proposal';
	$templates['directory/services-search.php']	= 'Search Services';
	
	return $templates;
}

add_filter( 'template_include', 'workreap_load_page_template' );
function workreap_load_page_template( $template ) {
	$theme_path	= get_stylesheet_directory().'/';
    $page_slug  = get_page_template_slug();
    if ( $page_slug === 'directory/dashboard.php' ) {
		if(file_exists($theme_path.'directory/dashboard.php')){
			$template = $theme_path.'directory/dashboard.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/dashboard.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
        
    } else if ( $page_slug === 'directory/full-width-template.php' ) {
		if(file_exists($theme_path.'full-width-template.php')){
			$template = $theme_path.'full-width-template.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/full-width-template.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/full-single-post.php' ) {
		if(file_exists($theme_path.'full-single-post.php')){
			$template = $theme_path.'full-single-post.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/full-single-post.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/project-search.php' ) {
		if(file_exists($theme_path.'directory/project-search.php')){
			$template = $theme_path.'directory/project-search.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/project-search.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/employer-search.php' ) {
		if(file_exists($theme_path.'directory/employer-search.php')){
			$template = $theme_path.'directory/employer-search.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/employer-search.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/freelancer-search.php' ) {
		if(file_exists($theme_path.'directory/freelancer-search.php')){
			$template = $theme_path.'directory/freelancer-search.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/freelancer-search.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
        	}
		}
    } else if ( $page_slug === 'directory/portfolio-search.php' ) {
		if(file_exists($theme_path.'directory/portfolio-search.php')){
			$template = $theme_path.'directory/portfolio-search.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/portfolio-search.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/project-proposal.php' ) {
		if(file_exists($theme_path.'directory/project-proposal.php')){
			$template = $theme_path.'directory/project-proposal.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/project-proposal.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if ( $page_slug === 'directory/services-search.php' ) {
		if(file_exists($theme_path.'directory/services-search.php')){
			$template = $theme_path.'directory/services-search.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/services-search.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
    } else if(is_attachment()){
		if(file_exists($theme_path.'attachment.php')){
			$template = $theme_path.'attachment.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/attachment.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
	} else if (is_category()) {
		if(file_exists($theme_path.'category.php')){
			$template = $theme_path.'category.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/category.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
	} else if (is_author()) {
		if(file_exists($theme_path.'author.php')){
			$template = $theme_path.'author.php';
		} else {
			$plugin_template = WORKREAPPLUGINPATH.'directory/author.php';
			if ( file_exists( $plugin_template ) ) {
				$template = $plugin_template;
			}
		}
	}
    $term_lists  = array(
        'delivery','department','freelancer_type','languages','locations','project_cat','response_time','service_categories','skills'
    );
    foreach($term_lists as $term_list){
        if ( is_tax( $term_list )  ) {
            if(file_exists($theme_path.'taxonomy-'.$term_list.'.php')){
                $template = $theme_path.'taxonomy-'.$term_list.'.php';
            } else{
                $template = WORKREAPPLUGINPATH. 'directory/taxonomy-'.$term_list.'.php';
            }
        }
    }
    return $template;
}


add_filter( 'single_template', 'workreap_get_custom_post_type_template' );
function workreap_get_custom_post_type_template( $single_template ) {
	global $post;
	$theme_path	= get_stylesheet_directory().'/';
	if ( 'freelancers' === $post->post_type ) {
		if(file_exists($theme_path.'single-freelancers.php')){
			$single_template = $theme_path.'single-freelancers.php';
		} else {
			$single_template = WORKREAPPLUGINPATH . 'directory/single-freelancers.php';
		}
	} else if ( 'micro-services' === $post->post_type ) {
		if(file_exists($theme_path.'project-proposal.php')){
			$single_template = $theme_path.'project-proposal.php';
		} else {
			$single_template = WORKREAPPLUGINPATH . 'directory/single-micro-services.php';
		}
	} else if ( 'employers' === $post->post_type ) {
		if(file_exists($theme_path.'single-employers.php')){
			$single_template = $theme_path.'single-employers.php';
		} else {
			$single_template = WORKREAPPLUGINPATH . 'directory/single-employers.php';
		}
	} else if ( 'wt_portfolio' === $post->post_type ) {
		if(file_exists($theme_path.'single-wt_portfolio.php')){
			$single_template = $theme_path.'single-wt_portfolio.php';
		} else {
			$single_template = WORKREAPPLUGINPATH . 'directory/single-wt_portfolio.php';
		}
	}else if ( 'projects' === $post->post_type ) {
		if(file_exists($theme_path.'single-projects.php')){
			$single_template = $theme_path.'single-projects.php';
		} else {
			$single_template = WORKREAPPLUGINPATH . 'directory/single-projects.php';
		}
	}

	return $single_template;
}